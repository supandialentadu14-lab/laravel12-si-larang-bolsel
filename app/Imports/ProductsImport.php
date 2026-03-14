<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        $lastProduct = Product::withTrashed()->orderBy('id', 'desc')->first();
        $newNumber = 1;
        if ($lastProduct instanceof Product && preg_match('/BRG-(\d+)/', (string) $lastProduct->sku, $matches)) {
            $newNumber = (int) $matches[1] + 1;
        }

        foreach ($rows as $index => $row) {
            $rowArray = is_array($row) ? $row : $row->toArray();
            
            // Skip truly empty rows
            if (!isset($rowArray[0]) && !isset($rowArray[1])) continue;
            
            $rowNumber = $index + 2; // +2 because startRow is 2 and index is 0-based

            $name = isset($rowArray[0]) ? trim((string) $rowArray[0]) : null;
            $categoryName = isset($rowArray[1]) ? trim((string) $rowArray[1]) : null;
            $price = isset($rowArray[2]) ? (float) $rowArray[2] : 0;
            $unit = isset($rowArray[3]) ? trim((string) $rowArray[3]) : 'pcs';

            if (empty($name)) {
                throw new \RuntimeException("Baris {$rowNumber}: Nama barang tidak boleh kosong.");
            }

            if (empty($categoryName)) {
                throw new \RuntimeException("Baris {$rowNumber}: Kategori barang '{$name}' tidak boleh kosong.");
            }

            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'slug' => Str::slug($categoryName) . '-' . time(),
                    'user_id' => Auth::id()
                ]
            );

            $slug = Str::slug($name) . '-' . time() . '-' . rand(100, 999);

            // Ensure SKU is unique by searching for next available number if collision occurs
            $newSku = 'BRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            while (Product::withTrashed()->where('sku', $newSku)->exists()) {
                $newNumber++;
                $newSku = 'BRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }

            Product::create([
                'name' => $name,
                'slug' => $slug,
                'sku' => $newSku,
                'price' => $price,
                'unit' => $unit,
                'category_id' => $category->id,
                'supplier_id' => null,
                'min_stock' => 1,
                'stock' => 0,
                'user_id' => Auth::id(),
            ]);

            $newNumber++; // Increment for the next row
        }
    }
}
