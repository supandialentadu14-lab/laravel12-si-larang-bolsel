<?php

// Menentukan namespace lokasi controller ini
namespace App\Http\Controllers;

// Mengimpor Model Category untuk berinteraksi dengan tabel categories
use App\Models\Category;

// Digunakan untuk tipe return berupa RedirectResponse
use Illuminate\Http\RedirectResponse;

// Digunakan untuk menangkap data request dari form
use Illuminate\Http\Request;

// Digunakan untuk membuat slug otomatis dari string
use Illuminate\Support\Str;

// Digunakan untuk tipe return berupa View (tampilan)
use Illuminate\View\View;
use Illuminate\Validation\Rule;

// Controller untuk mengelola data kategori (CRUD)
class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori
     */
    public function index(Request $request): View
    {
        // Mengambil data kategori
        // withCount('products') → menghitung jumlah produk dalam setiap kategori
        // latest() → urut berdasarkan data terbaru
        // paginate(10) → menampilkan 10 data per halaman
        $query = Category::withCount('products')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->paginate(10)->withQueryString();

        // Mengirim data ke view categories.index
        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan halaman form tambah kategori
     */
    public function create(): View
    {
        // Menampilkan view untuk membuat kategori baru
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $validated = $request->validate([
            // name wajib diisi, berupa string, maksimal 255 karakter, harus unik (abaikan yang sudah di-soft-delete)
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->id())->whereNull('deleted_at');
                })
            ],

            // description boleh kosong (nullable), jika diisi harus string
            'description' => 'nullable|string',
        ]);

        // Cari apakah ada data lama MILIK SENDIRI dengan nama ATAU slug yang sama di sampah
        $newSlug = Str::slug($validated['name']);
        
        $existing = Category::query()->withTrashed()
            ->where(function($q) use ($validated, $newSlug) {
                $q->where('name', $validated['name'])
                  ->orWhere('slug', $newSlug);
            })
            ->first();

        if ($existing && $existing->trashed()) {
            // Jika data yang sama ada di sampah, hapus permanen agar tidak bentrok
            $existing->forceDelete();
        }

        // Membuat slug dari name (contoh: "Makanan Ringan" → "makanan-ringan")
        $validated['slug'] = $newSlug;

        // Menyimpan data kategori ke database
        Category::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    /**
     * Menampilkan halaman edit kategori
     */
    public function edit(Category $category): View
    {
        // Route Model Binding:
        // Laravel otomatis mencari data category berdasarkan ID di URL
        return view('categories.edit', compact('category'));
    }

    /**
     * Menampilkan detail kategori
     */
    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Mengupdate data kategori di database
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id)->where(function ($query) {
                    return $query->where('user_id', auth()->id())->whereNull('deleted_at');
                })
            ],
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama jenis belanja ini sudah Anda gunakan.',
        ]);

        // Cari record lain MILIK SENDIRI (termasuk yang di sampah) yang memiliki nama yang sama
        $newSlug = Str::slug($validated['name']);
        $existing = Category::query()->withTrashed()
            ->where(function($q) use ($validated, $newSlug) {
                $q->where('name', $validated['name'])
                  ->orWhere('slug', $newSlug);
            })
            ->where('id', '!=', $category->id)
            ->first();

        if ($existing && $existing->trashed()) {
            // Jika data yang sama ada di sampah, hapus permanen agar tidak bentrok
            $existing->forceDelete();
        }

        // Jika nama kategori berubah, slug juga diupdate
        if ($category->name !== $validated['name']) {
            $validated['slug'] = $newSlug;
        }

        // Update data kategori
        $category->update($validated);

        // Redirect kembali ke halaman index (daftar belanja)
        return redirect()->route('categories.index')
                         ->with('success', 'Data jenis belanja berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Menghapus data kategori
        $category->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
    
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ], [
            'ids.required' => 'Tidak ada jenis belanja yang dipilih.',
        ]);

        Category::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Jenis belanja terpilih dihapus.');
    }
}
