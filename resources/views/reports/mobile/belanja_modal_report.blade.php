@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Daftar Kontrak Belanja Modal</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.belanja.modal.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden">
        <div id="paper-container" class="w-full no-scrollbar flex justify-center">
            <div id="paper-scale" class="flex-shrink-0 w-full" style="transform-origin: top center;">
                <style>
                    .preview-paper-mobile {
                        width: 1200px; /* basis ukuran kanvas untuk diskalakan */
                        min-height: 760px;
                        margin: 0; 
                        background: #fff; 
                        padding: 24px; 
                        line-height: 1.4; 
                        color: black; 
                        font-family: 'Nunito', sans-serif;
                        box-shadow: 0 0 30px rgba(0,0,0,0.12);
                        border: 1px solid #f1f5f9;
                    }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .preview-paper-mobile .kop { text-align: center; margin-bottom: 12px; }
                    .preview-paper-mobile .kop h1 { font-size: 16px; font-weight: 800; text-transform: uppercase; margin: 0; }
                    .preview-paper-mobile .kop h2 { font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 4px 0 0; }
                    .preview-paper-mobile .kop h3 { font-size: 13px; font-weight: 700; text-transform: uppercase; margin: 4px 0 0; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .uppercase { text-transform: uppercase; }
                </style>
                <div id="document-preview" class="preview-paper-mobile">
                    <div class="kop">
                        <h1>DAFTAR KONTRAK BELANJA MODAL</h1>
                        <h2>{{ $master['opd']['nama'] ?? null ?: $opd->nama_opd ?? '' }} KABUPATEN BOLAANG MONGONDOW SELATAN</h2>
                        <h3>TAHUN {{ $data['tahun'] }}</h3>
                    </div>

                    <table>
                        <colgroup>
                            <col style="width:3%">
                            <col style="width:14%">
                            <col style="width:16%">
                            <col style="width:10%">
                            <col style="width:8%">
                            <col style="width:10%">
                            <col style="width:8%">
                            <col style="width:8%">
                            <col style="width:8%">
                            <col style="width:8%">
                            <col style="width:8%">
                            <col style="width:10%">
                            <col style="width:8%">
                        </colgroup>
                        <thead>
                        <tr class="text-center font-bold" style="background-color: #f8fafc;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Kegiatan</th>
                            <th rowspan="2">Pekerjaan</th>
                            <th rowspan="2">Nilai Kontrak (Rp)</th>
                            <th rowspan="2">Tanggal Mulai</th>
                            <th rowspan="2">Tanggal Akhir Pekerjaan</th>
                            <th colspan="5">SP2D Pembayaran</th>
                            <th rowspan="2">Total Pembayaran (Rp)</th>
                            <th rowspan="2">Status Pekerjaan</th>
                        </tr>
                        <tr class="text-center font-bold" style="background-color: #f8fafc;">
                            <th>Uang Muka (Rp)</th>
                            <th>Termin I (Rp)</th>
                            <th>Termin II (Rp)</th>
                            <th>Termin III (Rp)</th>
                            <th>Termin IV (Rp)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['items'] as $i => $row)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $row['nm'] }}</td>
                                <td>{{ $row['pk'] }}</td>
                                <td class="text-right">{{ number_format($row['nk'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ $row['tm'] ? \Carbon\Carbon::parse($row['tm'])->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $row['ta'] ? \Carbon\Carbon::parse($row['ta'])->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td class="text-right">{{ $row['um'] ? ' ' . number_format($row['um'], 0, ',', '.') : '-' }}</td>
                                <td class="text-right">{{ $row['t1'] ? ' ' . number_format($row['t1'], 0, ',', '.') : '-' }}</td>
                                <td class="text-right">{{ $row['t2'] ? ' ' . number_format($row['t2'], 0, ',', '.') : '-' }}</td>
                                <td class="text-right">{{ $row['t3'] ? ' ' . number_format($row['t3'], 0, ',', '.') : '-' }}</td>
                                <td class="text-right">{{ $row['t4'] ? ' ' . number_format($row['t4'], 0, ',', '.') : '-' }}</td>
                                <td class="text-right">{{ number_format($row['ttl'], 0, ',', '.') }}</td>
                                <td class="text-center">{{ $row['st'] ?: '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-center gap-2 text-slate-400">
            <i class="fas fa-arrows-left-right text-xs animate-pulse"></i>
            <p class="text-[10px] font-bold uppercase tracking-widest">Geser untuk melihat dokumen</p>
        </div>
    </div>
</div>

<script>
    function openPrintPreview() {
        const printArea = document.getElementById('document-preview');
        if (!printArea) return;
        const content = printArea.innerHTML;
        const win = window.open('', '_blank', 'width=1000,height=800');
        if (!win) { alert('Silakan izinkan popup untuk mencetak.'); return; }
        win.document.open();
        win.document.write(`<!doctype html>
            <html>
            <head>
                <title>Cetak Daftar Kontrak Belanja Modal</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile { width: 330mm; min-height: 210mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .uppercase { text-transform: uppercase; }
                    @media print { 
                        body { padding: 0; }
                        @page { size: 330mm 210mm; margin: 10mm 15mm; }
                    }
                </style>
            </head>
            <body>
                <div class="preview-paper-mobile">
                    ${content}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() { window.close(); };
                    };
                <\/script>
            </body>
            </html>`);
        win.document.close();
    }
    (function() {
        const paperScale = document.getElementById('paper-scale');
        const doc = document.getElementById('document-preview');
        const container = document.getElementById('paper-container');
        function fit() {
            if (!paperScale || !doc || !container) return;
            const baseW = doc.scrollWidth || 1200;
            const baseH = doc.scrollHeight || 760;
            const availW = container.clientWidth;
            const headerHeight = 220; // kira-kira tinggi header + padding
            const availH = Math.max(300, window.innerHeight - headerHeight);
            const scale = Math.min(availW / baseW, availH / baseH);
            const clamped = Math.max(0.35, Math.min(scale, 1));
            paperScale.style.transform = `scale(${clamped})`;
        }
        window.addEventListener('resize', fit);
        document.addEventListener('DOMContentLoaded', fit);
        setTimeout(fit, 0);
    })();
</script>
@endsection
