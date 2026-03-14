<?php

// Menentukan namespace controller

namespace App\Http\Controllers;

// Mengimpor model Product
use App\Models\Product;
// Mengimpor model StockTransaction
use App\Models\StockTransaction;
// use App\Models\Transaction; // REMOVED: Redundant

// Digunakan untuk menangkap request dari form
use Illuminate\Http\RedirectResponse;
// Digunakan untuk transaksi database (agar aman & konsisten)
use Illuminate\Http\Request;
// Digunakan untuk tipe return View
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

// Controller untuk mengelola transaksi stok
class StockController extends Controller
{
    /**
     * Menampilkan daftar transaksi stok
     */
    public function index(Request $request): View
    {
        // Mengambil transaksi stok beserta relasi product dan user
        $transactions = StockTransaction::with(['product', 'user'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('sku', 'like', '%'.$request->search.'%');
                })->orWhere('nosur', 'like', '%'.$request->search.'%');
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $groupedTransactions = collect($transactions->items())->groupBy(function ($item) {
            return $item->date->format('Y-m-d');
        });
        $editTransaction = null;

        // Ambil semua produk beserta total stok masuk dan keluar untuk form modal
        $products = Product::withSum(['transactions as stock_in' => function ($q) {
            $q->where('type', 'in');
        }], 'quantity')
            ->withSum(['transactions as stock_out' => function ($q) {
                $q->where('type', 'out');
            }], 'quantity')
            ->get();

        foreach ($products as $product) {
            $product->calculated_stock = ($product->stock_in ?? 0) - ($product->stock_out ?? 0);
        }

        // Menghitung total saldo akhir (total stok semua produk)
        $totalSaldoAkhir = $products->sum('calculated_stock');

        // Menghitung total nilai persediaan (stok × harga)
        $grandTotal = $products->sum(function ($product) {
            return ($product->calculated_stock ?? 0) * ($product->price ?? 0);
        });

        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        $view = 'stock.index';

        // Kirim data ke view
        return view($view, compact(
            'transactions',
            'groupedTransactions',
            'editTransaction',
            'totalSaldoAkhir',
            'grandTotal',
            'products',
            'singkatanOpd'
        ));
    }

    /**
     * Show form for adding stock transaction
     */
    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get();
        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('stock.create', compact('products', 'singkatanOpd'));
    }

    /**
     * Menyimpan transaksi stok baru
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (empty($data['type']) && ! empty($data['type_radio'])) {
            $data['type'] = $data['type_radio'];
        }

        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'nosur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            return DB::transaction(function () use ($data) {
                // Lock product for update to ensure stock consistency
                $product = Product::lockForUpdate()->findOrFail($data['product_id']);

                // Jika tipe transaksi adalah stok keluar, cek apakah stok mencukupi
                if (($data['type'] ?? '') === 'out' && $product->stock < (int) $data['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk transaksi keluar ini.');
                }

                // Simpan transaksi stok ke database
                StockTransaction::create([
                    'product_id' => $product->id,
                    'type' => $data['type'],
                    'quantity' => (int) $data['quantity'],
                    'date' => $data['date'],
                    'nosur' => $data['nosur'] ?? null,
                    'notes' => $data['notes'] ?? null,
                    'user_id' => auth()->id(),
                ]);

                // Update physical stock in product table
                if (($data['type'] ?? '') === 'in') {
                    $product->increment('stock', (int) $data['quantity']);
                } else {
                    $product->decrement('stock', (int) $data['quantity']);
                }

                return redirect()->route('stock.index')
                    ->with('success', 'Transaksi berhasil disimpan.');
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $transaction = StockTransaction::with('product')->findOrFail($id);
        $products = Product::orderBy('name')->get();
        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('stock.edit', compact('transaction', 'products', 'singkatanOpd'));
    }

    public function update(Request $request, $id)
    {
        $transaction = StockTransaction::findOrFail($id);

        $data = $request->all();
        if (empty($data['type']) && ! empty($data['type_radio'])) {
            $data['type'] = $data['type_radio'];
        }

        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'nosur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            return DB::transaction(function () use ($data, $transaction) {
                // Revert old product stock
                $oldProduct = Product::lockForUpdate()->find($transaction->product_id);
                if ($oldProduct) {
                    if ($transaction->type === 'in') {
                        $oldProduct->decrement('stock', $transaction->quantity);
                    } else {
                        $oldProduct->increment('stock', $transaction->quantity);
                    }
                }

                // Load target product (could be the same)
                $newProduct = (($data['product_id'] ?? null) == $transaction->product_id)
                    ? $oldProduct
                    : Product::lockForUpdate()->findOrFail($data['product_id']);

                // Check if stock enough for "out" transaction
                if (($data['type'] ?? '') === 'out') {
                    if ($newProduct->stock < (int) $data['quantity']) {
                        throw new \Exception('Stok tidak mencukupi untuk transaksi keluar ini.');
                    }
                }

                // Update transaction
                $transaction->update([
                    'product_id' => $data['product_id'],
                    'date' => $data['date'],
                    'type' => $data['type'],
                    'quantity' => (int) $data['quantity'],
                    'nosur' => $data['nosur'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);

                // Apply new stock
                if (($data['type'] ?? '') === 'in') {
                    $newProduct->increment('stock', (int) $data['quantity']);
                } else {
                    $newProduct->decrement('stock', (int) $data['quantity']);
                }

                return redirect()->route('stock.index')
                    ->with('success', 'Transaksi berhasil diperbarui.');
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Redirect show requests to index
     */
    public function show($id)
    {
        return redirect()->route('stock.index');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->ids;
        if (! $ids || ! is_array($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        try {
            DB::transaction(function () use ($ids) {
                foreach ($ids as $id) {
                    $transaction = StockTransaction::find($id);
                    if ($transaction) {
                        // Lock product for update
                        $product = Product::lockForUpdate()->find($transaction->product_id);
                        if ($product) {
                            // Revert stock
                            if ($transaction->type === 'in') {
                                $product->decrement('stock', $transaction->quantity);
                            } else {
                                $product->increment('stock', $transaction->quantity);
                            }
                        }
                        $transaction->delete();
                    }
                }
            });

            return redirect()->route('stock.index')
                ->with('success', count($ids).' transaksi berhasil dihapus dan stok telah disesuaikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: '.$e->getMessage());
        }
    }

    /**
     * Menghapus transaksi stok
     */
    public function destroy($id): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($id) {
                $transaction = StockTransaction::findOrFail($id);
                $product = Product::withTrashed()->lockForUpdate()->findOrFail($transaction->product_id);

                // Kembalikan stok seperti sebelum transaksi ini ada
                if ($transaction->type === 'in') {
                    $product->decrement('stock', $transaction->quantity);
                } else {
                    $product->increment('stock', $transaction->quantity);
                }

                $transaction->delete();

                return redirect()->route('stock.index')
                    ->with('success', 'Transaksi berhasil dihapus dan stok telah disesuaikan.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: '.$e->getMessage());
        }
    }
}
