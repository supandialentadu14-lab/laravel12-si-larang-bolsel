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
            // name wajib diisi, berupa string, maksimal 255 karakter, harus unik
            'name' => 'required|string|max:255|unique:categories,name',

            // description boleh kosong (nullable), jika diisi harus string
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama jenis belanja ini sudah ada, silakan gunakan nama lain.',
        ]);

        // Membuat slug dari name (contoh: "Makanan Ringan" → "makanan-ringan")
        $validated['slug'] = Str::slug($validated['name']);

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
        // Validasi input - abaikan data yang sedang diupdate
        // dan abaikan data yang sudah dihapus (soft deleted) agar nama bisa dipakai kembali
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama jenis belanja ini sudah digunakan oleh data aktif lain.',
        ]);

        // Cari record lain (termasuk yang di sampah) yang memiliki nama yang sama
        $newSlug = Str::slug($validated['name']);
        $existing = Category::withTrashed()
            ->where(function($q) use ($validated, $newSlug) {
                $q->where('name', $validated['name'])
                  ->orWhere('slug', $newSlug);
            })
            ->where('id', '!=', $category->id)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Jika data yang sama ada di sampah, hapus permanen agar tidak bentrok
                $existing->forceDelete();
            } else {
                // Jika data yang sama masih aktif, kembalikan error
                return back()->withErrors(['name' => 'Nama atau slug jenis belanja ini sudah digunakan.'])->withInput();
            }
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
