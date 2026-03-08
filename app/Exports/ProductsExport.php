<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Nama Barang',
            'Kategori',
            'Supplier',
            'Harga',
            'Stok Saat Ini',
            'Satuan',
            'Keterangan',
        ];
    }

    public function map($product): array
    {
        return [
            $product->sku,
            $product->name,
            $product->category->name ?? '-',
            $product->supplier->name ?? '-',
            $product->price,
            $product->stock,
            $product->unit,
            $product->description,
        ];
    }
}
