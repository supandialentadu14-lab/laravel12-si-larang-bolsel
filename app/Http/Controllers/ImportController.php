<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\ProductsImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $file = $request->file('file');
        
        // Manual extension check is much more reliable than HTTP MIME type sniffing for Excel formats
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['csv', 'xls', 'xlsx'])) {
            return back()->with('error', 'Format file tidak didukung. Harap unggah file Excel (.xlsx, .xls) atau CSV.');
        }


        DB::beginTransaction();
        try {
            Excel::import(new ProductsImport, $file);
            DB::commit();
            return redirect()->route('products.index')->with('success', "Berhasil mengimpor data barang.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage());
            
            // Simplify error message for better user experience
            return back()->with('error', 'Format data atau file yang Anda unggah tidak sesuai. Silakan periksa kembali file Anda dan pastikan sudah mengikuti format yang ditentukan.');
        }
    }
}
