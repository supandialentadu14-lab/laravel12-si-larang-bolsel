<?php

namespace App\Exports;

use App\Models\StockTransaction;
use App\Models\OpdSetting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class StockTransactionsExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected array $reportData = [];
    protected $opd;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate    = $endDate;
        $this->opd        = OpdSetting::where('user_id', Auth::id())->first();
        $this->reportData = $this->buildReportData();
    }

    /* ------------------------------------------------------------------ */
    /* Build the same grouped data as the Blade report view                 */
    /* ------------------------------------------------------------------ */
    protected function buildReportData(): array
    {
        $transactions = StockTransaction::with('product')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = $transactions->groupBy(fn($item) => $item->date . '-' . $item->product_id);

        $rows = [];
        foreach ($grouped as $items) {
            $first  = $items->first();
            $masuk  = $items->where('type', 'in')->sum('quantity');
            $keluar = $items->where('type', 'out')->sum('quantity');

            $rows[] = [
                'date'       => $first->date,
                'product_id' => $first->product_id,
                'name'       => $first->product->name ?? '-',
                'harga'      => $first->product->price ?? 0,
                'satuan'     => $first->product->unit ?? '',
                'masuk'      => $masuk,
                'keluar'     => $keluar,
            ];
        }

        return $rows;
    }

    /* ------------------------------------------------------------------ */
    /* FromArray – build the raw 2-D array delivered to the sheet          */
    /* ------------------------------------------------------------------ */
    public function array(): array
    {
        $rows = [];

        // ── Metadata ─────────────────────────────────────────────────────
        $opdNama  = $this->opd->nama_opd ?? '';
        $endLabel = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');

        $rows[] = ['LAPORAN PERSEDIAAN BARANG HABIS PAKAI'];
        $rows[] = ["Per {$endLabel}"];
        $rows[] = [];
        $rows[] = ['SKPD', ':', $opdNama];
        $rows[] = ['Kabupaten', ':', 'Bolaang Mongondow Selatan'];
        $rows[] = [];

        // ── Double header row ─────────────────────────────────────────────
        // Row 1
        $rows[] = [
            'No', 'Nama Barang',
            // SALDO AWAL
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            // MUTASI MASUK
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            // MUTASI KELUAR
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            // SALDO AKHIR
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
        ];

        // ── Data rows ─────────────────────────────────────────────────────
        $saldo       = [];
        $lastDate    = null;
        $no          = 1;
        $grandTotal  = 0;
        $lastSaldo   = [];

        foreach ($this->reportData as $item) {
            $currentDate = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');

            // Date group separator
            if ($lastDate !== $currentDate) {
                $rows[]   = ['Tanggal : ' . \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y')];
                $lastDate = $currentDate;
            }

            $pid       = $item['product_id'];
            $harga     = $item['harga'];
            $satuan    = $item['satuan'];
            $masuk     = $item['masuk'];
            $keluar    = $item['keluar'];

            if (!isset($saldo[$pid])) $saldo[$pid] = 0;

            $saldoAwal  = $saldo[$pid];
            $saldoAkhir = $saldoAwal + $masuk - $keluar;
            $saldo[$pid] = $saldoAkhir;
            $lastSaldo[$pid] = ['saldo' => $saldoAkhir, 'harga' => $harga];

            $rows[] = [
                $no++,
                $item['name'],
                // Saldo Awal
                "{$saldoAwal} {$satuan}",
                $harga,
                $saldoAwal * $harga,
                // Masuk
                "{$masuk} {$satuan}",
                $harga,
                $masuk * $harga,
                // Keluar
                "{$keluar} {$satuan}",
                $harga,
                $keluar * $harga,
                // Saldo Akhir
                "{$saldoAkhir} {$satuan}",
                $harga,
                $saldoAkhir * $harga,
            ];
        }

        // Grand total
        foreach ($lastSaldo as $d) {
            $grandTotal += $d['saldo'] * $d['harga'];
        }

        $rows[] = array_merge(
            array_fill(0, 13, ''),
            [$grandTotal]
        );
        // Label for grand total is written via AfterSheet event

        return $rows;
    }

    public function title(): string
    {
        return 'Laporan Persediaan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 28,
            'C' => 14,
            'D' => 18,
            'E' => 18,
            'F' => 14,
            'G' => 18,
            'H' => 18,
            'I' => 14,
            'J' => 18,
            'K' => 18,
            'L' => 14,
            'M' => 18,
            'N' => 18,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalRows = count($this->array());

                // ── Title area ──────────────────────────────────────────
                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => false, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Group header merges (row 7 = header row in sheet)
                $headerRow = 7;
                // Merge group labels in row 7 across cols C-E, F-H, I-K, L-N
                foreach (['C7:E7' => 'SALDO AWAL', 'F7:H7' => 'MUTASI MASUK', 'I7:K7' => 'MUTASI KELUAR', 'L7:N7' => 'SALDO AKHIR'] as $range => $label) {
                    // We need to insert a real group-header row above our current header row
                }

                // Insert 1 row at row 7 to add the group labels row
                $sheet->insertNewRowBefore($headerRow, 1);

                // Group label row (now row 7), column header stays at row 8
                $sheet->setCellValue('A7', 'No');
                $sheet->setCellValue('B7', 'Nama Barang');
                $sheet->mergeCells('A7:A8'); // No spans 2 rows
                $sheet->mergeCells('B7:B8'); // Nama spans 2 rows
                $sheet->setCellValue('C7', 'SALDO AWAL');
                $sheet->mergeCells('C7:E7');
                $sheet->setCellValue('F7', 'MUTASI MASUK');
                $sheet->mergeCells('F7:H7');
                $sheet->setCellValue('I7', 'MUTASI KELUAR');
                $sheet->mergeCells('I7:K7');
                $sheet->setCellValue('L7', 'SALDO AKHIR');
                $sheet->mergeCells('L7:N7');

                // Style group header row 7
                $sheet->getStyle('A7:N7')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1D5DB']],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                // Style column sub-header row 8
                $sheet->getStyle('A8:N8')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                $sheet->getRowDimension(7)->setRowHeight(22);
                $sheet->getRowDimension(8)->setRowHeight(30);

                // ── Data area borders & number format ─────────────────────
                $lastRow = $sheet->getHighestRow();
                $dataStart = 9; // data rows start here

                for ($r = $dataStart; $r <= $lastRow; $r++) {
                    $cellA = $sheet->getCell("A{$r}")->getValue();

                    // Date separator row
                    if ($cellA && str_starts_with((string)$cellA, 'Tanggal :')) {
                        $sheet->mergeCells("A{$r}:N{$r}");
                        $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                            'font'      => ['bold' => true, 'size' => 9],
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
                            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                        ]);
                        continue;
                    }

                    // Grand total row (col A is empty, col N has value)
                    if ($cellA === '' || $cellA === null) {
                        $sheet->mergeCells("A{$r}:M{$r}");
                        $sheet->setCellValue("A{$r}", 'TOTAL NILAI PERSEDIAAN');
                        $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                            'font'      => ['bold' => true, 'size' => 9],
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1D5DB']],
                            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                        ]);
                        // Number format for grand total
                        $sheet->getStyle("N{$r}")->getNumberFormat()->setFormatCode('#,##0');
                        continue;
                    }

                    // Normal data row
                    $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                        'font'    => ['size' => 9],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    // Right-align & number format for currency columns: D,E,G,H,J,K,M,N
                    foreach (['D', 'E', 'G', 'H', 'J', 'K', 'M', 'N'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                    }
                    // Center Jmlh columns: C, F, I, L
                    foreach (['C', 'F', 'I', 'L'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // ── Metadata style ────────────────────────────────────────
                $sheet->getStyle('A4:A5')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(13)->setBold(true);

                // Freeze header rows
                $sheet->freezePane('A9');
            },
        ];
    }
}
