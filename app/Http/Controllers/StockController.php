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
    use \App\Traits\AutoStock;

    /**
     * Menampilkan daftar transaksi stok
     */
    public function index(Request $request): View
    {
        $search = $request->search;
        $dateFilter = $request->date;

        // 1. Get unique dates that have transactions, considering the filters
        $datePaginator = StockTransaction::query()
            ->select('date')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('sku', 'like', '%'.$search.'%');
                })->orWhere('nosur', 'like', '%'.$search.'%');
            })
            ->when($dateFilter, function ($query) use ($dateFilter) {
                $query->whereDate('date', $dateFilter);
            })
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(1) // One day per page
            ->withQueryString();

        // 2. Fetch transactions for the current date(s) in the paginator
        $currentDateItems = $datePaginator->items();
        $transactions = collect();
        $groupedTransactions = collect();

        if (!empty($currentDateItems)) {
            $targetDate = $currentDateItems[0]->date;
            
            $transactions = StockTransaction::with(['product', 'user'])
                ->whereDate('date', $targetDate)
                ->when($search, function ($query) use ($search) {
                    $query->whereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%')
                            ->orWhere('sku', 'like', '%'.$search.'%');
                    })->orWhere('nosur', 'like', '%'.$search.'%');
                })
                ->orderBy('id', 'desc')
                ->get();

            // Calculate running balance for each transaction
            foreach ($transactions as $tx) {
                $tx->running_balance = StockTransaction::where('product_id', '=', $tx->product_id)
                    ->where(function($q) use ($tx) {
                        $q->where('date', '<', $tx->date)
                          ->orWhere(function($q2) use ($tx) {
                              $q2->where('date', '=', $tx->date)
                                 ->where('id', '<=', $tx->id);
                          });
                    })
                    ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE -quantity END) as total')
                    ->value('total') ?? 0;
            }

            $groupedTransactions = $transactions->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            });
        }

        // --- Keep the rest of original logic for products and totals ---
        $editTransaction = null;
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

        $totalSaldoAkhir = $products->sum('calculated_stock');
        $grandTotal = $products->sum(function ($product) {
            return ($product->calculated_stock ?? 0) * ($product->price ?? 0);
        });

        $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
        $singkatanOpd = strtoupper($opdSetting->singkatan_opd ?? 'DISKOMINFO');

        return view('stock.index', compact(
            'transactions',
            'datePaginator',
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

    public function syncFromDocs(Request $request)
    {
        try {
            $userId = auth()->id();
            $disk = \Illuminate\Support\Facades\Storage::disk('local');
            
            // 1. Sync from PHP BAP Penerimaan (BASTB)
            $pDir = "users/{$userId}/bap-penerimaan";
            if ($disk->exists($pDir)) {
                foreach ($disk->files($pDir) as $file) {
                    if (!str_ends_with($file, '.json')) continue;
                    $data = json_decode($disk->get($file), true);
                    if ($data && !empty($data['items']) && !empty($data['nomor'])) {
                        $this->recordItemsToStock($data['items'], $data['nomor'], $data['tanggal'] ?? now()->toDateString(), 'Otomatis dari BASTB');
                    }
                }
            }

            // 2. Sync from Kwitansi
            $kDir = "users/{$userId}/kwitansi";
            if ($disk->exists($kDir)) {
                foreach ($disk->files($kDir) as $file) {
                    if (!str_ends_with($file, '.json')) continue;
                    $data = json_decode($disk->get($file), true);
                    if ($data && !empty($data['nomor_kwt'])) {
                        // Kwitansi usually takes items from a BAP Penerimaan
                        // But let's check its own data if possible. 
                        // Actually kwitansi saves the pRef.
                        $pRef = $data['penerimaan_nomor'] ?? null;
                        if ($pRef) {
                            // Find that BAP
                            $penerimaan = null;
                            if ($disk->exists($pDir)) {
                                foreach ($disk->files($pDir) as $pf) {
                                    $pdata = json_decode($disk->get($pf), true);
                                    if (($pdata['nomor'] ?? null) === $pRef) { 
                                        $penerimaan = $pdata; break; 
                                    }
                                }
                            }
                            if ($penerimaan && !empty($penerimaan['items'])) {
                                $this->recordItemsToStock($penerimaan['items'], $pRef, $penerimaan['tanggal'] ?? ($data['tanggal'] ?? now()->toDateString()), 'Otomatis dari Kwitansi');
                            }
                        }
                    }
                }
            }

            return redirect()->route('stock.index')->with('success', 'Sinkronisasi stok dari BASTB & Kwitansi berhasil selesai.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Sync Error: " . $e->getMessage());
            return back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
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
                    ->with('success', 'Transaksi ' . ($data['type'] === 'in' ? 'masuk' : 'keluar') . ' "' . $product->name . '" berhasil disimpan.');
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
                    ->with('success', 'Transaksi ' . ($data['type'] === 'in' ? 'masuk' : 'keluar') . ' "' . $newProduct->name . '" berhasil diperbarui.');
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
                        // PREVENT DELETION OF AUTOMATIC TRANSACTIONS
                        if ($transaction->notes === 'Otomatis dari Kwitansi') {
                            throw new \Exception('Transaksi "' . ($transaction->product->name ?? 'Barang') . '" tidak bisa dihapus manual karena dibuat otomatis dari Kwitansi. Silakan hapus Nota Pesanan terkait untuk menghapus transaksi ini.');
                        }

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

            $msg = count($ids) . ' transaksi berhasil dihapus dan stok telah disesuaikan.';
            return redirect()->route('stock.index')->with('success', $msg);
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

                // PREVENT DELETION OF AUTOMATIC TRANSACTIONS
                if ($transaction->notes === 'Otomatis dari Kwitansi') {
                    throw new \Exception('Transaksi tidak bisa dihapus manual karena dibuat otomatis dari Kwitansi. Silakan hapus Nota Pesanan terkait untuk menghapus transaksi ini.');
                }

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
