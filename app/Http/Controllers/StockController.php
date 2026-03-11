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
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                      ->orWhere('sku', 'like', '%'.$request->search.'%');
                })->orWhere('nosur', 'like', '%'.$request->search.'%');
            })
            ->latest()        // Urutkan dari terbaru
            ->paginate(20)   // Tampilkan 20 data per halaman
            ->withQueryString(); 

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
        $singkatanOpd = $opdSetting->singkatan_opd ?? 'DISKOMINFO';

        // Kirim data ke view
        return view('stock.index', compact(
            'transactions',
            'totalSaldoAkhir',
            'grandTotal',
            'products',
            'singkatanOpd'
        ));
    }

    /**
     * Menampilkan form tambah transaksi stok
     */
    public function create()
    {
        // Mengambil semua produk beserta total stok masuk
        $products = Product::withSum(['transactions as stock_in' => function ($q) {
                $q->where('type', 'in'); // Filter transaksi masuk
            }], 'quantity')

            // Mengambil total stok keluar
            ->withSum(['transactions as stock_out' => function ($q) {
                $q->where('type', 'out'); // Filter transaksi keluar
            }], 'quantity')

            ->get();

        // Menghitung stok aktual berdasarkan transaksi (masuk - keluar)
        foreach ($products as $product) {
            $product->calculated_stock =
                ($product->stock_in ?? 0) - ($product->stock_out ?? 0);
        }

        // Kirim data ke view
        return view('stock.create', compact('products'));
    }

    /**
     * Menyimpan transaksi stok baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id', // Produk harus ada
            'type' => 'required|in:in,out', // Hanya boleh "in" atau "out"
            'quantity' => 'required|integer|min:1', // Jumlah minimal 1
            'date' => 'required|date', // Tanggal wajib valid
        ]);

        // Ambil produk berdasarkan ID
        $product = Product::findOrFail($request->product_id);

        // Jika tipe transaksi adalah stok keluar, cek apakah stok mencukupi
        if ($request->type === 'out' && $product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Simpan transaksi stok ke database
        StockTransaction::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'nosur' => $request->nosur,
            'notes' => $request->notes,
            'user_id' => auth()->id(), // Simpan ID user yang login
        ]);
        
        if ($request->type === 'in') {
            $product->increment('stock', $request->quantity);
        } else {
            $product->decrement('stock', $request->quantity);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('stock.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function update(Request $request, $id)
{
    $transaction = StockTransaction::findOrFail($id);

    // ===============================
    // VALIDASI DATA
    // ===============================
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'date'       => 'required|date',
        'type'       => 'required|in:in,out',
        'quantity'   => 'required|integer|min:1',
        'nosur'      => 'nullable|string|max:255',
        'notes'      => 'nullable|string'
    ]);

    // ===============================
    // CEK STOK JIKA TRANSAKSI KELUAR
    // ===============================

    if ($request->type === 'out') {

        // Hitung total stok produk saat ini TANPA transaksi lama
        $totalMasuk = StockTransaction::where('product_id', $request->product_id)
            ->where('type', 'in')
            ->where('id', '!=', $transaction->id)
            ->sum('quantity');

        $totalKeluar = StockTransaction::where('product_id', $request->product_id)
            ->where('type', 'out')
            ->where('id', '!=', $transaction->id)
            ->sum('quantity');

        $stokSaatIni = $totalMasuk - $totalKeluar;

        if ($request->quantity > $stokSaatIni) {
            return back()->withErrors([
                'quantity' => 'Stok tidak mencukupi untuk transaksi keluar ini.'
            ])->withInput();
        }
    }

    // ===============================
    // UPDATE DATA TRANSAKSI & PRODUK
    // ===============================
    
    // Kembalikan stok lama sebelum menghitung ulang
    $product = Product::findOrFail($transaction->product_id);
    if ($transaction->type === 'in') {
        $product->decrement('stock', $transaction->quantity);
    } else {
        $product->increment('stock', $transaction->quantity);
    }
    
    // Jika produknya diubah ke produk lain (jarang terjadi, tapi jika iya)
    if ($request->product_id != $transaction->product_id) {
        $product = Product::findOrFail($request->product_id); // Ganti fokus ke produk baru
    }

    $transaction->update([
        'product_id' => $request->product_id,
        'date'       => $request->date,
        'type'       => $request->type,
        'quantity'   => $request->quantity,
        'nosur'      => $request->nosur,
        'notes'      => $request->notes,
    ]);
    
    // Terapkan stok baru
    if ($request->type === 'in') {
        $product->increment('stock', $request->quantity);
    } else {
        $product->decrement('stock', $request->quantity);
    }

    return redirect()->route('stock.index')
        ->with('success', 'Transaksi berhasil diperbarui.');
}

    public function edit($id)
    {
        $transaction = StockTransaction::findOrFail($id);
        $products = Product::all();

        return view('stock.edit', compact('transaction', 'products'));
    }

    /**
     * Redirect show requests to edit
     */
    public function show($id)
    {
        return redirect()->route('stock.edit', $id);
    }


    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->ids;
        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        foreach ($ids as $id) {
            $transaction = StockTransaction::find($id);
            if ($transaction) {
                // Update stock before deleting
                $product = Product::find($transaction->product_id);
                if ($product) {
                    if ($transaction->type === 'in') {
                        $product->decrement('stock', $transaction->quantity);
                    } else {
                        $product->increment('stock', $transaction->quantity);
                    }
                }
                $transaction->delete();
            }
        }

        return redirect()->route('stock.index')
            ->with('success', count($ids) . ' transaksi berhasil dihapus dan stok telah disesuaikan.');
    }

    /**
     * Menghapus transaksi stok
     */
    public function destroy(StockTransaction $transaction): RedirectResponse
    {
        $product = Product::findOrFail($transaction->product_id);
        
        // Kembalikan stok seperti sebelum transaksi ini ada
        if ($transaction->type === 'in') {
            $product->decrement('stock', $transaction->quantity);
        } else {
            $product->increment('stock', $transaction->quantity);
        }
        
        $transaction->delete();

        return redirect()->route('stock.index')
            ->with('success', 'Transaksi berhasil dihapus dan stok telah disesuaikan.');
    }
}
