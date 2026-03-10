<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Ambil barang terakhir berdasarkan id
        $lastProduct = Product::latest()->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newSku = 'BRG-'.str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('products.create', compact('categories', 'suppliers', 'newSku'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with(['category', 'transactions'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                      ->orWhere('sku', 'like', '%'.$request->search.'%')
                      ->orWhereHas('category', function($cq) use ($request) {
                          $cq->where('name', 'like', '%'.$request->search.'%');
                      });
                });
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->low_stock, function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->paginate(10);

        return view('products.index', compact('products', 'categories'));
    }
    /**
     * SIMPAN PRODUK
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required',
        'unit' => 'required',
        'category_id' => 'required',
    ]);

    $lastProduct = Product::latest()->first();

    if ($lastProduct) {
        $lastNumber = (int) substr($lastProduct->sku, 4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
$slug = Str::slug($request->name);

    $newSku = 'BRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    Product::create([
    'name' => $request->name,
    'slug' => $slug, // TAMBAHKAN INI
    'sku' => $newSku,
    'price' => $request->price,
    'unit' => $request->unit,
    'category_id' => $request->category_id,
    'supplier_id' => $request->supplier_id,
    'description' => $request->description,
]);


    return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan');
}


    /**
     * UPDATE PRODUK
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'unit' => 'required',
            'category_id' => 'required',
        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit, // ✅ FIX DI SINI
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * REPORT KARTU
     */
    public function kartu(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transactions = StockTransaction::with('product')
            ->when($startDate, fn ($q) => $q->whereDate('date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('date', '<=', $endDate))
            ->orderBy('date', 'asc')
            ->get();

        $reportData = [];

        foreach ($transactions as $trx) {
            $reportData[] = [
                'date' => $trx->date,
                'reference' => $trx->reference_number ?? '-',
                'uraian' => $trx->description ?? $trx->product->name,
                'name' => $trx->product->name,
                'unit' => $trx->product->unit, // ✅ TAMBAH AGAR SATUAN SESUAI DATABASE
                'masuk' => $trx->type === 'in' ? $trx->quantity : 0,
                'keluar' => $trx->type === 'out' ? $trx->quantity : 0,
                'harga' => $trx->price ?? $trx->product->price,
                'keterangan' => $trx->notes ?? '',
            ];
        }

        return view('reports.kartu', compact(
            'reportData',
            'startDate',
            'endDate'
        ));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ], [
            'ids.required' => 'Tidak ada barang yang dipilih.',
        ]);

        Product::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('products.index')->with('success', 'Barang terpilih berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'daftar-barang-'.date('Ymd').'.xlsx');
    }

    public function printBarcode(Product $product)
    {
        return view('products.barcode', compact('product'));
    }
}
