<?php

namespace App\Exports;

use App\Models\OpdSetting;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;

class BelanjaModalExport implements FromArray, WithTitle, WithColumnWidths, WithEvents
{
    protected array  $data;   // ['tahun'=>..., 'items'=>[...]]
    protected $opd;
    protected $master;

    public function __construct(array $data, $opd, $master)
    {
        $this->data   = $data;
        $this->opd    = $opd;
        $this->master = $master;
    }

    public function array(): array
    {
        $rows   = [];
        $tahun  = $this->data['tahun'] ?? date('Y');
        $items  = $this->data['items'] ?? [];
        $opdNama = $this->master['opd']['nama'] ?? ($this->opd->nama_opd ?? '');

        // ── Kop ────────────────────────────────────────────────────────
        $rows[] = ['DAFTAR KONTRAK BELANJA MODAL'];
        $rows[] = ["{$opdNama} KABUPATEN BOLAANG MONGONDOW SELATAN"];
        $rows[] = ["TAHUN {$tahun}"];
        $rows[] = [];

        // ── Header (2 rows) ────────────────────────────────────────────
        // Row 1
        $rows[] = ['No', 'Nama Kegiatan', 'Pekerjaan', 'Nilai Kontrak (Rp)', 'Tanggal Mulai', 'Tanggal Akhir Pekerjaan',
                   'SP2D Pembayaran', '', '', '', '',
                   'Total Pembayaran (Rp)', 'Status Pekerjaan'];
        // Row 2 (SP2D sub-headers)
        $rows[] = ['', '', '', '', '', '', 'Uang Muka (Rp)', 'Termin I (Rp)', 'Termin II (Rp)', 'Termin III (Rp)', 'Termin IV (Rp)', '', ''];

        // ── Data ───────────────────────────────────────────────────────
        $no = 1;
        foreach ($items as $row) {
            $rows[] = [
                $no++,
                $row['nm'] ?? '',
                $row['pk'] ?? '',
                (int)($row['nk'] ?? 0),
                $row['tm'] ? \Carbon\Carbon::parse($row['tm'])->translatedFormat('d F Y') : '-',
                $row['ta'] ? \Carbon\Carbon::parse($row['ta'])->translatedFormat('d F Y') : '-',
                $row['um'] ? (int)$row['um'] : '-',
                $row['t1'] ? (int)$row['t1'] : '-',
                $row['t2'] ? (int)$row['t2'] : '-',
                $row['t3'] ? (int)$row['t3'] : '-',
                $row['t4'] ? (int)$row['t4'] : '-',
                (int)($row['ttl'] ?? 0),
                $row['st'] ?? '-',
            ];
        }

        // ── Tanda Tangan ───────────────────────────────────────────────
        $rows[] = [];
        $rows[] = [];
        $rows[] = ['', '', '', '', '', '', 'Dibuat Oleh', '', '', '', '', 'Mengetahui'];
        $rows[] = ['', '', '', '', '', '', 'Pengurus Barang', '', '', '', '', 'Kepala Dinas'];
        $rows[] = [];
        $rows[] = [];
        $rows[] = [];
        $rows[] = [];
        $rows[] = ['', '', '', '', '', '', $this->opd->pengurus_nama ?? '', '', '', '', '', $this->opd->kepala_nama ?? ''];
        $rows[] = ['', '', '', '', '', '', 'NIP. ' . ($this->opd->pengurus_nip ?? ''), '', '', '', '', 'NIP. ' . ($this->opd->kepala_nip ?? '')];

        return $rows;
    }

    public function title(): string { return 'Belanja Modal'; }

    public function columnWidths(): array
    {
        return ['A'=>5,'B'=>25,'C'=>25,'D'=>18,'E'=>16,'F'=>18,'G'=>16,'H'=>14,'I'=>14,'J'=>14,'K'=>14,'L'=>18,'M'=>16];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ── Kop center & bold ─────────────────────────────────
                foreach (['A1:M1','A2:M2','A3:M3'] as $range) {
                    $sheet->mergeCells($range);
                    $sheet->getStyle($range)->applyFromArray([
                        'font'      => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                }
                $sheet->getStyle('A1')->getFont()->setSize(14);

                // ── Header row 5: merge SP2D cols ─────────────────────
                $headerRow1 = 5;
                $headerRow2 = 6;

                // Merge SP2D group header
                $sheet->mergeCells("G{$headerRow1}:K{$headerRow1}");
                $sheet->setCellValue("G{$headerRow1}", 'SP2D Pembayaran');

                // Merge single-col headers across 2 rows
                foreach (['A','B','C','D','E','F','L','M'] as $col) {
                    $sheet->mergeCells("{$col}{$headerRow1}:{$col}{$headerRow2}");
                }

                $headerStyle = [
                    'font'      => ['bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1D5DB']],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ];
                $sheet->getStyle("A{$headerRow1}:M{$headerRow2}")->applyFromArray($headerStyle);
                $sheet->getRowDimension($headerRow1)->setRowHeight(24);
                $sheet->getRowDimension($headerRow2)->setRowHeight(24);

                // ── Data rows ─────────────────────────────────────────
                $lastRow   = $sheet->getHighestRow();
                $dataStart = $headerRow2 + 1;
                $dataEnd   = $lastRow - 12; // leave room for signatures

                for ($r = $dataStart; $r <= $dataEnd; $r++) {
                    $valA = $sheet->getCell("A{$r}")->getValue();
                    if ($valA === '' || $valA === null) break; // reached TTD section

                    $sheet->getStyle("A{$r}:M{$r}")->applyFromArray([
                        'font'    => ['size' => 9],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setWrapText(true);
                    $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setWrapText(true);
                    $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("M{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    foreach (['D','G','H','I','J','K','L'] as $col) {
                        $val = $sheet->getCell("{$col}{$r}")->getValue();
                        if (is_numeric($val)) {
                            $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $sheet->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                        } else {
                            $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        }
                    }
                }

                // ── TTD styling ───────────────────────────────────────
                $ttdStart = $dataEnd + 3;
                for ($r = $ttdStart; $r <= $lastRow; $r++) {
                    foreach (['G','L'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // Underline name rows
                for ($r = $lastRow - 1; $r <= $lastRow; $r++) {
                    $sheet->getStyle("G{$r}")->getFont()->setUnderline(true);
                    $sheet->getStyle("L{$r}")->getFont()->setUnderline(true);
                }
            },
        ];
    }
}
