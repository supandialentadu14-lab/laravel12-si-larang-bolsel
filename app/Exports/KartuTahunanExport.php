<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Events\AfterSheet;

class KartuTahunanExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    protected array  $grouped;
    protected string $startDate;
    protected string $endDate;
    protected $opd;
    protected $master;

    // Track row positions for AfterSheet styling
    // Each element: ['h1' => int, 'h2' => int, 'saldo' => int, 'data_start' => int, 'data_end' => int]
    protected array $productBlocks = [];
    protected int   $ttdRow        = 0;

    public function __construct(array $grouped, string $startDate, string $endDate, $opd, $master)
    {
        $this->grouped   = $grouped;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->opd       = $opd;
        $this->master    = $master;
    }

    /* ------------------------------------------------------------------ */
    /* Total columns = 12                                                  */
    /* A  B        C                     D       E     F      G    H       */
    /* No Tanggal  Nomor Surat           Uraian  Masuk Keluar Sisa Harga   */
    /* I         J          K          L                                   */
    /* Jml Masuk Jml Keluar Jml Sisa   Keterangan                         */
    /* ------------------------------------------------------------------ */

    public function array(): array
    {
        $rows     = [];
        $opdNama  = $this->master['opd']['nama'] ?? ($this->opd->nama_opd ?? '');
        $endLabel = \Carbon\Carbon::parse($this->endDate)->translatedFormat('d F Y');
        $C        = 12;
        $blank    = array_fill(0, $C, '');

        // ── Rows 1-3: Title ────────────────────────────────────────────
        $t1 = $blank; $t1[0] = 'KARTU PERSEDIAAN BARANG';
        $rows[] = $t1;                                                       // 1

        $t2 = $blank; $t2[0] = 'DI LINGKUNGAN PEMERINTAH KABUPATEN BOLAANG MONGONDOW SELATAN';
        $rows[] = $t2;                                                       // 2

        $t3 = $blank; $t3[0] = "Per {$endLabel}";
        $rows[] = $t3;                                                       // 3

        // ── Row 4: empty ──────────────────────────────────────────────
        $rows[] = $blank;                                                    // 4

        // ── Rows 5-6: SKPD / Kabupaten ────────────────────────────────
        $r5 = $blank; $r5[0] = 'SKPD';      $r5[1] = ':'; $r5[2] = $opdNama;
        $rows[] = $r5;                                                       // 5

        $r6 = $blank; $r6[0] = 'Kabupaten'; $r6[1] = ':'; $r6[2] = 'Bolaang Mongondow Selatan';
        $rows[] = $r6;                                                       // 6

        // ── Row 7: empty ──────────────────────────────────────────────
        $rows[] = $blank;                                                    // 7

        // ── Per-product blocks ─────────────────────────────────────────
        foreach ($this->grouped as $data) {
            $product = $data['product'];
            $rowsP   = $data['rows'];
            $saldo   = 0;
            $harga   = 0;
            $block   = [];

            // Product info rows
            $pi1 = $blank; $pi1[0] = 'Nama Barang'; $pi1[1] = ':'; $pi1[2] = $product->name;
            $rows[] = $pi1;

            $pi2 = $blank; $pi2[0] = 'Satuan'; $pi2[1] = ':'; $pi2[2] = $product->unit ?? '';
            $rows[] = $pi2;

            // Header row 1 (group header)
            $block['h1'] = count($rows) + 1; // +1 because 1-indexed
            $rows[] = [
                '__H1__',                                       // A – marker
                'Tanggal',                                      // B
                'Nomor Surat Dasar Penerimaan/Pengeluaran',    // C
                'Uraian',                                       // D
                'Barang-Barang',                                // E – merged E:G
                '', '',                                         // F, G
                'Harga Satuan (Rp)',                            // H
                'Jumlah Harga (Rp)',                            // I – merged I:K
                '', '',                                         // J, K
                'Keterangan',                                   // L
            ];

            // Header row 2 (sub-header: Masuk/Keluar/Sisa)
            $block['h2'] = count($rows) + 1;
            $rows[] = [
                '__H2__', '', '', '',                            // A-D
                'Masuk', 'Keluar', 'Sisa',                      // E-G
                '',                                              // H
                'Masuk', 'Keluar', 'Sisa',                      // I-K
                '',                                              // L
            ];

            // Data rows
            $no = 1;
            $block['data_start'] = count($rows) + 1;
            $lastHarga = 0;
            $lastSisa  = 0;
            foreach ($rowsP as $row) {
                $sisa     = $row['sisa'] ?? 0;
                $masuk    = $row['masuk'] ?? 0;
                $keluar   = $row['keluar'] ?? 0;
                $harga    = $row['harga'] ?? 0;
                $lastHarga = $harga;
                $lastSisa  = $sisa;

                $rows[] = [
                    $no++,
                    $row['date'] ? \Carbon\Carbon::parse($row['date'])->translatedFormat('d F Y') : '-',
                    $row['nosur'] ?? '-',
                    $product->name,
                    $masuk ?: '',
                    $keluar ?: '',
                    $sisa,
                    $harga,
                    $masuk ? $masuk * $harga : '',
                    $keluar ? $keluar * $harga : '',
                    $sisa * $harga,
                    $row['keterangan'] ?? '',
                ];
            }
            $block['data_end'] = count($rows);

            // Saldo row
            $block['saldo'] = count($rows) + 1;
            $saldoLabel  = "Saldo Per {$endLabel}";
            $saldoQty    = $lastSisa == 0 ? 'Nihil' : $lastSisa;
            $saldoAmount = $lastSisa == 0 ? 'Nihil' : $lastSisa * $lastHarga;
            $rows[] = [
                '__SALDO__',         // A – marker
                $saldoLabel,         // B – will be merged A:F
                '', '', '', '',
                $saldoQty,           // G – sisa qty
                '',                  // H
                '', '',              // I, J
                $saldoAmount,        // K – sisa amount
                '',                  // L
            ];

            // Empty separator row between products
            $rows[] = $blank;

            $this->productBlocks[] = $block;
        }

        // ── Tanda Tangan ──────────────────────────────────────────────
        $rows[] = $blank;
        $this->ttdRow = count($rows) + 1;

        $ttdLbl = $blank;
        $ttdLbl[1]  = 'Dibuat Oleh';
        $ttdLbl[10] = 'Mengetahui';
        $rows[] = $ttdLbl;

        $ttdJab = $blank;
        $ttdJab[1]  = 'Pengurus Barang';
        $ttdJab[10] = 'Kepala Dinas';
        $rows[] = $ttdJab;

        $rows[] = $blank;
        $rows[] = $blank;
        $rows[] = $blank;
        $rows[] = $blank;

        $ttdNm = $blank;
        $ttdNm[1]  = $this->opd->pengurus_nama ?? '';
        $ttdNm[10] = $this->opd->kepala_nama ?? '';
        $rows[] = $ttdNm;

        $ttdNip = $blank;
        $ttdNip[1]  = 'NIP. ' . ($this->opd->pengurus_nip ?? '');
        $ttdNip[10] = 'NIP. ' . ($this->opd->kepala_nip ?? '');
        $rows[] = $ttdNip;

        return $rows;
    }

    public function title(): string { return 'Kartu Persediaan Tahunan'; }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  'B' => 18, 'C' => 30, 'D' => 22,
            'E' => 8,  'F' => 8,  'G' => 8,
            'H' => 16, 'I' => 16, 'J' => 16, 'K' => 14,
            'L' => 16,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // ── Title rows 1-3 ─────────────────────────────────────
                foreach (['A1:L1', 'A2:L2', 'A3:L3'] as $rng) {
                    $sheet->mergeCells($rng);
                    $sheet->getStyle($rng)->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }
                $sheet->getStyle('A1')->getFont()->setSize(14);

                // ── SKPD / Kabupaten rows 5-6 ──────────────────────────
                $sheet->getStyle('A5:A6')->getFont()->setBold(true);

                // ── Per-product blocks ─────────────────────────────────
                $hdrStyle = [
                    'font'      => ['bold' => true, 'size' => 9],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ];

                foreach ($this->productBlocks as $block) {
                    $h1   = $block['h1'];
                    $h2   = $block['h2'];
                    $ds   = $block['data_start'];
                    $de   = $block['data_end'];
                    $sRow = $block['saldo'];

                    // ── Product info rows (h1-2 before them) ──────────
                    $prodRow1 = $h1 - 2;
                    $prodRow2 = $h1 - 1;
                    $sheet->getStyle("A{$prodRow1}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$prodRow2}")->getFont()->setBold(true);
                    $sheet->mergeCells("C{$prodRow1}:L{$prodRow1}");
                    $sheet->mergeCells("C{$prodRow2}:L{$prodRow2}");

                    // ── Header row 1: clear marker, set 'No', merge groups ─
                    $sheet->setCellValue("A{$h1}", 'No');
                    $sheet->mergeCells("A{$h1}:A{$h2}");  // No spans 2 rows
                    $sheet->mergeCells("B{$h1}:B{$h2}");  // Tanggal spans 2 rows
                    $sheet->mergeCells("C{$h1}:C{$h2}");  // Nomor Surat spans 2 rows
                    $sheet->mergeCells("D{$h1}:D{$h2}");  // Uraian spans 2 rows
                    $sheet->mergeCells("E{$h1}:G{$h1}");  // Barang-Barang
                    $sheet->mergeCells("H{$h1}:H{$h2}");  // Harga Satuan spans 2 rows
                    $sheet->mergeCells("I{$h1}:K{$h1}");  // Jumlah Harga
                    $sheet->mergeCells("L{$h1}:L{$h2}");  // Keterangan spans 2 rows

                    $sheet->getStyle("A{$h1}:L{$h2}")->applyFromArray($hdrStyle);
                    $sheet->getRowDimension($h1)->setRowHeight(20);
                    $sheet->getRowDimension($h2)->setRowHeight(18);

                    // Fix header row 2: clear marker
                    $sheet->setCellValue("A{$h2}", '');

                    // ── Data rows ──────────────────────────────────────
                    for ($r = $ds; $r <= $de; $r++) {
                        $valA = $sheet->getCell("A{$r}")->getValue();
                        if (!is_numeric($valA) || (int)$valA <= 0) continue;

                        $sheet->getStyle("A{$r}:L{$r}")->applyFromArray([
                            'font'    => ['size' => 9],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        foreach (['E', 'F', 'G'] as $col) {
                            $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        }
                        foreach (['H', 'I', 'J', 'K'] as $col) {
                            $val = $sheet->getCell("{$col}{$r}")->getValue();
                            if ($val !== '' && $val !== null) {
                                $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $sheet->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                            }
                        }
                    }

                    // ── Saldo row ──────────────────────────────────────
                    $saldoLabel = $sheet->getCell("B{$sRow}")->getValue();
                    // Clear marker, merge A:F for label
                    $sheet->setCellValue("A{$sRow}", '');
                    for ($c = ord('A'); $c <= ord('F'); $c++) {
                        $sheet->setCellValue(chr($c) . $sRow, '');
                    }
                    $sheet->mergeCells("A{$sRow}:F{$sRow}");
                    $sheet->setCellValue("A{$sRow}", $saldoLabel);

                    $sheet->getStyle("A{$sRow}:L{$sRow}")->applyFromArray([
                        'font'    => ['bold' => true, 'size' => 9],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    ]);
                    $sheet->getStyle("A{$sRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle("G{$sRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Format Jumlah Sisa (K)
                    $kVal = $sheet->getCell("K{$sRow}")->getValue();
                    if (is_numeric($kVal)) {
                        $sheet->getStyle("K{$sRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $sheet->getStyle("K{$sRow}")->getNumberFormat()->setFormatCode('#,##0');
                    } else {
                        $sheet->getStyle("K{$sRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // ── Tanda Tangan ───────────────────────────────────────
                for ($r = $this->ttdRow; $r <= $lastRow; $r++) {
                    $valB  = (string)($sheet->getCell("B{$r}")->getValue() ?? '');
                    $valK  = (string)($sheet->getCell("K{$r}")->getValue() ?? '');

                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("K{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    if (in_array($valB, ['Dibuat Oleh', 'Pengurus Barang'])) {
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true);
                    }
                    if (in_array($valK, ['Mengetahui', 'Kepala Dinas'])) {
                        $sheet->getStyle("K{$r}")->getFont()->setBold(true);
                    }
                }

                // Underline + bold nama
                $pengNama = $this->opd->pengurus_nama ?? '';
                $kepNama  = $this->opd->kepala_nama ?? '';
                for ($r = $this->ttdRow; $r <= $lastRow; $r++) {
                    $valB = (string)($sheet->getCell("B{$r}")->getValue() ?? '');
                    $valK = (string)($sheet->getCell("K{$r}")->getValue() ?? '');
                    if ($valB === $pengNama) {
                        $sheet->getStyle("B{$r}")->getFont()->setBold(true)->setUnderline(Font::UNDERLINE_SINGLE);
                    }
                    if ($valK === $kepNama) {
                        $sheet->getStyle("K{$r}")->getFont()->setBold(true)->setUnderline(Font::UNDERLINE_SINGLE);
                    }
                }
            },
        ];
    }
}
