<?php

namespace App\Exports;

use App\Models\StockTransaction;
use App\Models\OpdSetting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanPersediaanExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    protected string $startDate;
    protected string $endDate;
    protected array  $reportData;
    protected $opd;

    // Track important row numbers for AfterSheet
    protected int $headerRow1  = 7;   // Group header: SALDO AWAL, MUTASI MASUK, …
    protected int $headerRow2  = 8;   // Sub-header:  Jmlh Barang, Harga Satuan, …
    protected int $dataStart   = 9;   // First data / date-separator row
    protected int $grandTotalRow = 0; // filled after build
    protected int $ttdRow        = 0; // filled after build

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate  = $startDate;
        $this->endDate    = $endDate;
        $this->opd        = OpdSetting::where('user_id', Auth::id())->first();
        $this->reportData = $this->buildReportData();
    }

    /* ------------------------------------------------------------------ */
    /* Same grouping logic as Blade view                                   */
    /* ------------------------------------------------------------------ */
    protected function buildReportData(): array
    {
        $transactions = StockTransaction::with('product')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = $transactions->groupBy(fn($item) => $item->date . '-' . $item->product_id);
        $rows    = [];

        foreach ($grouped as $items) {
            $first  = $items->first();
            $rows[] = [
                'date'       => $first->date,
                'product_id' => $first->product_id,
                'name'       => $first->product->name ?? '-',
                'harga'      => $first->product->price ?? 0,
                'satuan'     => $first->product->unit ?? '',
                'masuk'      => $items->where('type', 'in')->sum('quantity'),
                'keluar'     => $items->where('type', 'out')->sum('quantity'),
            ];
        }

        return $rows;
    }

    /* ------------------------------------------------------------------ */
    /* Build the full 2-D array (no AfterSheet row insertion needed)       */
    /* ------------------------------------------------------------------ */
    public function array(): array
    {
        $rows     = [];
        $opdNama  = $this->opd->nama_opd ?? '';
        $endLabel = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');
        $C        = 14; // total columns

        $blank = array_fill(0, $C, '');

        // ── Row 1 : Title ──────────────────────────────────────────────
        $r1 = $blank; $r1[0] = 'LAPORAN PERSEDIAAN BARANG HABIS PAKAI';
        $rows[] = $r1;                                               // Row 1

        // ── Row 2 : Sub-title ─────────────────────────────────────────
        $r2 = $blank; $r2[0] = "Per {$endLabel}";
        $rows[] = $r2;                                               // Row 2

        // ── Row 3 : Empty ─────────────────────────────────────────────
        $rows[] = $blank;                                            // Row 3

        // ── Row 4 : SKPD ──────────────────────────────────────────────
        $r4 = $blank; $r4[0] = 'SKPD'; $r4[1] = ':'; $r4[2] = $opdNama;
        $rows[] = $r4;                                               // Row 4

        // ── Row 5 : Kabupaten ─────────────────────────────────────────
        $r5 = $blank; $r5[0] = 'Kabupaten'; $r5[1] = ':'; $r5[2] = 'Bolaang Mongondow Selatan';
        $rows[] = $r5;                                               // Row 5

        // ── Row 6 : Empty ─────────────────────────────────────────────
        $rows[] = $blank;                                            // Row 6

        // ── Row 7 : Group header (SALDO AWAL … SALDO AKHIR) ──────────
        $rows[] = [
            '',              // A – merged with row 8 → No
            '',              // B – merged with row 8 → Nama Barang
            'SALDO AWAL',    // C – merged C7:E7
            '', '',
            'MUTASI MASUK',  // F – merged F7:H7
            '', '',
            'MUTASI KELUAR', // I – merged I7:K7
            '', '',
            'SALDO AKHIR',   // L – merged L7:N7
            '', '',
        ];                                                           // Row 7

        // ── Row 8 : Sub-header ────────────────────────────────────────
        $rows[] = [
            'No', 'Nama Barang',
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
            'Jmlh Barang', 'Harga Satuan (Rp)', 'Jumlah (Rp)',
        ];                                                           // Row 8

        // ── Data rows (row 9 …) ───────────────────────────────────────
        $saldo     = [];
        $lastSaldo = [];
        $lastDate  = null;
        $no        = 1;

        foreach ($this->reportData as $item) {
            $currentDate = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');

            // Date separator
            if ($lastDate !== $currentDate) {
                $dr = $blank;
                $dr[0] = '__DATE__';
                $dr[1] = 'Tanggal : ' . \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y');
                $rows[] = $dr;
                $lastDate = $currentDate;
            }

            $pid    = $item['product_id'];
            $harga  = $item['harga'];
            $satuan = $item['satuan'];
            $masuk  = $item['masuk'];
            $keluar = $item['keluar'];

            if (!isset($saldo[$pid])) $saldo[$pid] = 0;

            $saldoAwal  = $saldo[$pid];
            $saldoAkhir = $saldoAwal + $masuk - $keluar;
            $saldo[$pid] = $saldoAkhir;
            $lastSaldo[$pid] = ['saldo' => $saldoAkhir, 'harga' => $harga];

            $rows[] = [
                $no++,
                $item['name'],
                // Saldo Awal
                ($saldoAwal ?: '0') . ' ' . $satuan,
                $harga,
                $saldoAwal * $harga,
                // Masuk
                ($masuk ?: '0') . ' ' . $satuan,
                $harga,
                $masuk * $harga,
                // Keluar
                ($keluar ?: '0') . ' ' . $satuan,
                $harga,
                $keluar * $harga,
                // Saldo Akhir
                $saldoAkhir . ' ' . $satuan,
                $harga,
                $saldoAkhir * $harga,
            ];
        }

        // ── Grand Total ───────────────────────────────────────────────
        $grandTotal = array_sum(array_map(fn($d) => $d['saldo'] * $d['harga'], $lastSaldo));
        $gt = $blank;
        $gt[0]  = '__GRAND__';
        $gt[13] = $grandTotal;
        $rows[] = $gt;

        $this->grandTotalRow = count($rows);   // 1-indexed sheet row

        // ── Empty ── ────────────────────────────────────────────────
        $rows[] = $blank;
        $rows[] = $blank;

        // ── Tanda Tangan ──────────────────────────────────────────────
        $this->ttdRow = count($rows) + 1;

        $ttdLabel = $blank;
        $ttdLabel[1]  = 'Dibuat Oleh';
        $ttdLabel[12] = 'Mengetahui';
        $rows[] = $ttdLabel;

        $ttdJabatan = $blank;
        $ttdJabatan[1]  = 'Pengurus Barang';
        $ttdJabatan[12] = 'Kepala Dinas';
        $rows[] = $ttdJabatan;

        // Blank rows for signature space
        $rows[] = $blank;
        $rows[] = $blank;
        $rows[] = $blank;
        $rows[] = $blank;

        $ttdName = $blank;
        $ttdName[1]  = $this->opd->pengurus_nama ?? '';
        $ttdName[12] = $this->opd->kepala_nama ?? '';
        $rows[] = $ttdName;

        $ttdNip = $blank;
        $ttdNip[1]  = 'NIP. ' . ($this->opd->pengurus_nip ?? '');
        $ttdNip[12] = 'NIP. ' . ($this->opd->kepala_nip ?? '');
        $rows[] = $ttdNip;

        return $rows;
    }

    public function title(): string { return 'Laporan Persediaan'; }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  'B' => 22, 'C' => 11, 'D' => 16, 'E' => 14,
            'F' => 11, 'G' => 16, 'H' => 14,
            'I' => 11, 'J' => 16, 'K' => 14,
            'L' => 11, 'M' => 16, 'N' => 14,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // ── Title (rows 1 – 2) ─────────────────────────────────
                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── SKPD / Kabupaten (rows 4 – 5) ─────────────────────
                $sheet->getStyle('A4:A5')->getFont()->setBold(true);

                // ── Group header row (row 7) – merge + style ──────────
                $h1 = $this->headerRow1;
                $h2 = $this->headerRow2;

                // No and Nama Barang span both header rows
                $sheet->mergeCells("A{$h1}:A{$h2}");
                $sheet->setCellValue("A{$h1}", 'No');
                $sheet->mergeCells("B{$h1}:B{$h2}");
                $sheet->setCellValue("B{$h1}", 'Nama Barang');

                // Group spans
                $sheet->mergeCells("C{$h1}:E{$h1}");
                $sheet->mergeCells("F{$h1}:H{$h1}");
                $sheet->mergeCells("I{$h1}:K{$h1}");
                $sheet->mergeCells("L{$h1}:N{$h1}");

                $hdrStyle = [
                    'font'      => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ];
                $sheet->getStyle("A{$h1}:N{$h2}")->applyFromArray($hdrStyle);
                $sheet->getRowDimension($h1)->setRowHeight(20);
                $sheet->getRowDimension($h2)->setRowHeight(28);

                // ── Data / separator / grand-total rows ───────────────
                for ($r = $this->dataStart; $r < $this->ttdRow; $r++) {
                    $valA = (string)($sheet->getCell("A{$r}")->getValue() ?? '');
                    $valB = (string)($sheet->getCell("B{$r}")->getValue() ?? '');

                    // ── Date separator ─────────────────────────────────
                    if ($valA === '__DATE__') {
                        $sheet->setCellValue("A{$r}", '');
                        $sheet->mergeCells("A{$r}:N{$r}");
                        $sheet->setCellValue("A{$r}", $valB);
                        $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                            'font'      => ['bold' => true, 'size' => 9],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
                            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);
                        continue;
                    }

                    // ── Grand total ────────────────────────────────────
                    if ($valA === '__GRAND__') {
                        $grandVal = $sheet->getCell("N{$r}")->getValue();
                        // Clear cols A–M then merge
                        for ($c = ord('A'); $c <= ord('M'); $c++) {
                            $sheet->setCellValue(chr($c) . $r, '');
                        }
                        $sheet->mergeCells("A{$r}:M{$r}");
                        $sheet->setCellValue("A{$r}", 'TOTAL NILAI PERSEDIAAN');
                        $sheet->setCellValue("N{$r}", $grandVal);
                        $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                            'font'      => ['bold' => true, 'size' => 9],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
                            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);
                        $sheet->getStyle("N{$r}")->getNumberFormat()->setFormatCode('#,##0');
                        continue;
                    }

                    // ── Regular data row ───────────────────────────────
                    if (!is_numeric($valA) || (int)$valA <= 0) continue;

                    $sheet->getStyle("A{$r}:N{$r}")->applyFromArray([
                        'font'    => ['size' => 9],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    ]);

                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    foreach (['C', 'F', 'I', 'L'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                    foreach (['D', 'E', 'G', 'H', 'J', 'K', 'M', 'N'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                    }
                }


                // ── Tanda Tangan styling ───────────────────────────────────
                for ($r = $this->ttdRow; $r <= $lastRow; $r++) {
                    $valB  = (string)($sheet->getCell("B{$r}")->getValue() ?? '');
                    $valM  = (string)($sheet->getCell("M{$r}")->getValue() ?? '');

                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("M{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Bold labels
                    if (in_array($valB, ['Dibuat Oleh', 'Pengurus Barang'])) {
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                    }
                    if (in_array($valM, ['Mengetahui', 'Kepala Dinas'])) {
                        $sheet->getStyle("M{$r}")->getFont()->setBold(true);
                    }

                    // Underline name rows
                    $pengNama = $this->opd->pengurus_nama ?? '___';
                    $kepNama  = $this->opd->kepala_nama ?? '___';
                    if ($valB === $pengNama || $valM === $kepNama) {
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true)->setUnderline(Font::UNDERLINE_SINGLE);
                        $sheet->getStyle("M{$r}")->getFont()->setBold(true)->setUnderline(Font::UNDERLINE_SINGLE);
                    }
                }

                // Freeze panes below header
                $sheet->freezePane('A9');

                // Draw border around the whole table (optional, clean look)
                // already done per-cell above
            },
        ];
    }
}
