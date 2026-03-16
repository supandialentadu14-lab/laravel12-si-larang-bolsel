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
use App\Models\NotaPesanan;
use App\Models\BapPemeriksaan;
use App\Models\BapItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Ambil barang terakhir berdasarkan id, termasuk yang dihapus soft-delete
        $lastProduct = Product::where('user_id', auth()->id())->withTrashed()->orderBy('id', 'desc')->first();

        if ($lastProduct instanceof Product && preg_match('/BRG-(\d+)/', (string) $lastProduct->sku, $matches)) {
            $lastNumber = (int) $matches[1];
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
        $suppliers = Supplier::all();
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

        return view('products.index', compact('products', 'categories', 'suppliers'));
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
        'min_stock' => 'nullable|integer|min:0',
    ]);

    // Ambil barang terakhir milik sendiri berdasarkan id, termasuk yang dihapus soft-delete
    $lastProduct = Product::where('user_id', auth()->id())->withTrashed()->orderBy('id', 'desc')->first();

    if ($lastProduct instanceof Product && preg_match('/BRG-(\d+)/', (string) $lastProduct->sku, $matches)) {
        $lastNumber = (int) $matches[1];
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }
    $slug = Str::slug($request->name);
    
    // Cari apakah ada data lama MILIK SENDIRI dengan slug yang sama di sampah
    $existing = Product::where('user_id', auth()->id())
        ->withTrashed()
        ->where('slug', $slug)
        ->first();

    if ($existing) {
        if ($existing->trashed()) {
            // Bersihkan dari dokumen jika barang ini pernah dipakai
            $this->cleanupProductFromDocs($request->name);
            
            // Jika ada di sampah, hapus permanen beserta transaksinya agar bisa dipakai lagi
            $existing->transactions()->forceDelete();
            $existing->forceDelete();
        } else {
            // Jika ada yang aktif, tambahkan akhiran angka agar unik
            $count = Product::where('user_id', auth()->id())
                ->where('slug', 'like', $slug.'%')
                ->count();
            $slug = $slug . '-' . ($count + 1);
        }
    }

    $newSku = 'BRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    Product::create([
        'name' => $request->name,
        'slug' => $slug,
        'sku' => $newSku,
        'price' => $request->price,
        'unit' => $request->unit,
        'category_id' => $request->category_id,
        'supplier_id' => $request->supplier_id,
        'description' => $request->description,
        'min_stock' => $request->min_stock ?? 10,
    ]);

    return redirect()->route('products.index')->with('success', 'Barang "' . $request->name . '" berhasil ditambahkan');
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
            'min_stock' => 'nullable|integer|min:0',
        ]);

        $productData = [
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'description' => $request->description,
            'min_stock' => $request->min_stock ?? 10,
        ];

        // Jika nama berubah, cek slug baru agar tidak bentrok
        if ($product->name !== $request->name) {
            $slug = Str::slug($request->name);
            
            // Cek record lain (termasuk sampah) milik sendiri yang punya slug tersebut
            $existing = Product::where('user_id', auth()->id())
                ->withTrashed()
                ->where('slug', $slug)
                ->where('id', '!=', $product->id)
                ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    // Bersihkan dari dokumen jika barang ini pernah dipakai
                    $this->cleanupProductFromDocs($request->name);
                    
                    $existing->transactions()->forceDelete();
                    $existing->forceDelete();
                } else {
                    $count = Product::where('user_id', auth()->id())
                        ->where('slug', 'like', $slug.'%')
                        ->where('id', '!=', $product->id)
                        ->count();
                    $slug = $slug . '-' . ($count + 1);
                }
            }
            $productData['slug'] = $slug;
        }

        $product->update($productData);

        return redirect()->route('products.index')
            ->with('success', 'Barang "' . $product->name . '" berhasil diperbarui.');
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
        $productName = $product->name;
        // Bersihkan barang dari seluruh Nota, BA, dan Kwitansi
        $this->cleanupProductFromDocs($product->name);

        // Hapus transaksi terkait (soft delete)
        $product->transactions()->delete();
        
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Barang "' . $productName . '" berhasil dihapus.');
    }
    
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ], [
            'ids.required' => 'Tidak ada barang yang dipilih.',
        ]);

        // Bersihkan barang-barang ini dari seluruh dokumen terkait
        $productsToRemove = Product::whereIn('id', $validated['ids'])->get();
        foreach ($productsToRemove as $p) {
            $this->cleanupProductFromDocs($p->name);
        }

        // Hapus transaksi terkait untuk barang-barang ini
        StockTransaction::whereIn('product_id', $validated['ids'])->delete();

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

    /**
     * Membersihkan data barang dari dokumen Nota Pesanan, BA, dan Kwitansi
     * saat barang tersebut dihapus.
     */
    private function cleanupProductFromDocs(string $productName): void
    {
        $userId = Auth::id();
        $disk = Storage::disk('local');
        $dir = "users/{$userId}/nota-pesanan";

        if (!$disk->exists($dir)) return;

        foreach ($disk->files($dir) as $file) {
            if (!str_ends_with($file, '.json')) continue;
            
            $data = json_decode($disk->get($file), true) ?: [];
            $items = $data['items'] ?? [];
            $originalCount = count($items);
            
            // Filter barang yang namanya sama dengan product yang dihapus
            $items = array_values(array_filter($items, function($item) use ($productName) {
                return (string)($item['name'] ?? '') !== $productName;
            }));

            // Jika ada barang yang terhapus dari nota
            if (count($items) !== $originalCount) {
                // Jika nota jadi kosong, biarkan saja (user yang hapus notanya nanti) 
                // atau tetap update totalnya jadi 0.
                $newTotal = array_sum(array_map(fn($it) => (int)($it['total'] ?? 0), $items));
                $data['items'] = $items;
                $data['total'] = $newTotal;
                
                // Simpan JSON Nota
                $disk->put($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                // Update Database Nota & Items
                if (Schema::hasTable('nota_pesanans')) {
                    $dbNota = NotaPesanan::where('user_id', '=', $userId, 'and')->where('nomor', '=', $data['nomor'] ?? '', 'and')->first();
                    if ($dbNota) {
                        $dbNota->update([
                            'total' => $newTotal,
                            'terbilang' => $this->toWordsIdInternal($newTotal)
                        ]);
                        $dbNota->items()->where('name', $productName)->delete();
                    }
                }

                // Sinkronisasi ke berkas terkait (BAP & Kwitansi)
                $this->syncRelatedDocsForProductCleanup($data);
            }
        }
    }

    private function syncRelatedDocsForProductCleanup(array $notaData): void
    {
        $userId = Auth::id();
        $disk = Storage::disk('local');
        $notaNomor = trim((string)($notaData['nomor'] ?? ''));
        if ($notaNomor === '') return;

        $notaItems = $notaData['items'] ?? [];
        $bapItems = [];
        foreach ($notaItems as $row) {
            $bapItems[] = [
                'nama' => (string)($row['name'] ?? ''),
                'kuantitas' => (int)($row['qty'] ?? 0),
                'satuan' => (string)($row['unit'] ?? ''),
                'harga' => (int)($row['price'] ?? 0),
                'jumlah' => (int)($row['total'] ?? 0),
            ];
        }
        
        $bapTotal = array_sum(array_map(fn($r) => (int)($r['jumlah'] ?? 0), $bapItems));
        $bapTerbilang = ucwords($this->toWordsIdInternal((int)$bapTotal));

        // Update Pemeriksaan
        $pemeriksaanDir = "users/{$userId}/bap-pemeriksaan";
        if ($disk->exists($pemeriksaanDir)) {
            foreach ($disk->files($pemeriksaanDir) as $file) {
                if (!str_ends_with($file, '.json')) continue;
                $doc = json_decode($disk->get($file), true) ?: [];
                if (trim((string)($doc['nota']['nomor'] ?? '')) === $notaNomor) {
                    $doc['nota'] = $notaData;
                    $doc['items'] = $bapItems;
                    $doc['total'] = $bapTotal;
                    $doc['terbilang'] = $bapTerbilang;
                    $disk->put($file, json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                    if (Schema::hasTable('bap_pemeriksaans')) {
                        $bap = BapPemeriksaan::where('user_id', '=', $userId, 'and')->where('nomor', '=', $doc['nomor'] ?? '', 'and')->first();
                        if ($bap) {
                            $bap->update(['total' => $bapTotal, 'terbilang' => $bapTerbilang]);
                            $bap->items()->delete();
                            foreach ($bapItems as $row) {
                                BapItem::create([
                                    'bap_id' => $bap->id,
                                    'nama' => $row['nama'],
                                    'kuantitas' => $row['kuantitas'],
                                    'satuan' => $row['satuan'],
                                    'harga' => $row['harga'],
                                    'jumlah' => $row['jumlah'],
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Update Penerimaan
        $affectedPenerimaan = [];
        $penerimaanDir = "users/{$userId}/bap-penerimaan";
        if ($disk->exists($penerimaanDir)) {
            foreach ($disk->files($penerimaanDir) as $file) {
                if (!str_ends_with($file, '.json')) continue;
                $doc = json_decode($disk->get($file), true) ?: [];
                if (trim((string)($doc['nota']['nomor'] ?? '')) === $notaNomor) {
                    $doc['nota'] = $notaData;
                    $doc['items'] = $bapItems;
                    $doc['total'] = $bapTotal;
                    $doc['terbilang'] = $bapTerbilang;
                    $disk->put($file, json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    if (!empty($doc['nomor'])) $affectedPenerimaan[trim($doc['nomor'])] = $doc;
                }
            }
        }

        // Update Kwitansi
        if (!empty($affectedPenerimaan)) {
            $kwitansiDir = "users/{$userId}/kwitansi";
            if ($disk->exists($kwitansiDir)) {
                foreach ($disk->files($kwitansiDir) as $file) {
                    if (!str_ends_with($file, '.json')) continue;
                    $doc = json_decode($disk->get($file), true) ?: [];
                    $pNomor = trim((string)($doc['penerimaan_nomor'] ?? ''));
                    if ($pNomor !== '' && isset($affectedPenerimaan[$pNomor])) {
                        $doc['jumlah'] = $bapTotal;
                        $doc['terbilang'] = $bapTerbilang . ' Rupiah';
                        $disk->put($file, json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    }
                }
            }
        }
    }

    protected function toWordsIdInternal(int $value): string
    {
        $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        if ($value < 12) return $huruf[$value];
        if ($value < 20) return $this->toWordsIdInternal($value - 10) . ' belas';
        if ($value < 100) return $this->toWordsIdInternal(intval($value / 10)) . ' puluh ' . $this->toWordsIdInternal($value % 10);
        if ($value < 200) return 'seratus ' . $this->toWordsIdInternal($value - 100);
        if ($value < 1000) return $this->toWordsIdInternal(intval($value / 100)) . ' ratus ' . $this->toWordsIdInternal($value % 100);
        if ($value < 2000) return 'seribu ' . $this->toWordsIdInternal($value - 1000);
        if ($value < 1000000) return $this->toWordsIdInternal(intval($value / 1000)) . ' ribu ' . $this->toWordsIdInternal($value % 1000);
        if ($value < 1000000000) return $this->toWordsIdInternal(intval($value / 1000000)) . ' juta ' . $this->toWordsIdInternal($value % 1000000);
        return (string) $value;
    }
}
