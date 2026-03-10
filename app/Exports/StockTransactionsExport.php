<?php

namespace App\Exports;

use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class StockTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return StockTransaction::with('product.category')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Tipe Transaksi',
            'Masuk',
            'Keluar',
            'Satuan',
            'Keterangan',
        ];
    }

    public function map($transaction): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            Carbon::parse($transaction->date)->format('d/m/Y'),
            $transaction->product->sku ?? '-',
            $transaction->product->name ?? '-',
            $transaction->product->category->name ?? '-',
            $transaction->type == 'in' ? 'Masuk' : 'Keluar',
            $transaction->type == 'in' ? $transaction->quantity : 0,
            $transaction->type == 'out' ? $transaction->quantity : 0,
            $transaction->product->unit ?? '-',
            $transaction->notes ?? '-',
        ];
    }
}
