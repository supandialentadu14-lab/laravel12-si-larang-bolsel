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
        $lastProduct = Product::latest()->first();
        $newNumber = $lastProduct ? (int) substr($lastProduct->sku, 4) + 1 : 1;

        foreach ($rows as $row) {
            $rowArray = is_array($row) ? $row : $row->toArray();
            if (!isset($rowArray[0]) || empty($rowArray[0])) continue;

            $name = $rowArray[0];
            $categoryName = $rowArray[1] ?? null;
            $price = (int)($rowArray[2] ?? 0);

            $category = $categoryName ? Category::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName) . '-' . time()]
            ) : null;

            $newSku = 'BRG-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            $slug = Str::slug($name) . '-' . time() . '-' . rand(100, 999);

            $product = Product::create([
                'name' => $name,
                'slug' => $slug,
                'sku' => $newSku,
                'price' => $price,
                'unit' => 'pcs',
                'category_id' => $category?->id,
                'supplier_id' => null,
                'min_stock' => 10, // Default minimum stock
                'stock' => 0,
            ]);

            $newNumber++;
        }
    }
}
