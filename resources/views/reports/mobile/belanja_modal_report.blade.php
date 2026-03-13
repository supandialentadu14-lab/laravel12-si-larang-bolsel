@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
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

    {{-- Document Preview Card --}}
    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden flex flex-col items-center">
        <div class="w-full flex justify-center no-scrollbar overflow-x-auto">
            {{-- Landscape report needs different scale/min-width --}}
            <div class="flex-shrink-0 origin-top transform scale-[0.25] min-[400px]:scale-[0.3] sm:scale-100 mb-[-150%] min-[400px]:mb-[-130%] sm:mb-0" style="width: 330mm;">
                <style>
                    .preview-paper-mobile-landscape { 
                        width: 330mm; 
                        min-height: 210mm; 
                        margin: 0; 
                        background: #fff; 
                        padding: 15mm; 
                        line-height: 1.4; 
                        color: black; 
                        font-family: 'Nunito', sans-serif;
                        box-shadow: 0 0 30px rgba(0,0,0,0.12);
                        border: 1px solid #f1f5f9;
                    }
                    .preview-paper-mobile-landscape h1 { font-size: 20px; font-weight: 800; text-transform: uppercase; margin: 0; text-align: center; }
                    .preview-paper-mobile-landscape h2 { font-size: 16px; font-weight: 700; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape h3 { font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 8px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                </style>
                <div id="document-preview" class="preview-paper-mobile-landscape">
                    <div class="mb-8">
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
                            <tr style="background-color: #f8fafc;">
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Kegiatan</th>
                                <th rowspan="2">Pekerjaan</th>
                                <th rowspan="2">Nilai Kontrak (Rp)</th>
                                <th rowspan="2">Tanggal Mulai</th>
                                <th rowspan="2">Tanggal Akhir</th>
                                <th colspan="5">SP2D Pembayaran</th>
                                <th rowspan="2">Total (Rp)</th>
                                <th rowspan="2">Status</th>
                            </tr>
                            <tr style="background-color: #f8fafc;">
                                <th>Uang Muka</th>
                                <th>T-I</th>
                                <th>T-II</th>
                                <th>T-III</th>
                                <th>T-IV</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data['items'] as $row)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td style="word-wrap: break-word;">{{ $row['nm'] }}</td>
                                    <td style="word-wrap: break-word;">{{ $row['pk'] }}</td>
                                    <td class="text-right font-bold">{{ number_format($row['nk'], 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $row['tm'] ? \Carbon\Carbon::parse($row['tm'])->translatedFormat('d/m/Y') : '-' }}</td>
                                    <td class="text-center">{{ $row['ta'] ? \Carbon\Carbon::parse($row['ta'])->translatedFormat('d/m/Y') : '-' }}</td>
                                    <td class="text-right">{{ $row['um'] ? number_format($row['um'], 0, ',', '.') : '-' }}</td>
                                    <td class="text-right">{{ $row['t1'] ? number_format($row['t1'], 0, ',', '.') : '-' }}</td>
                                    <td class="text-right">{{ $row['t2'] ? number_format($row['t2'], 0, ',', '.') : '-' }}</td>
                                    <td class="text-right">{{ $row['t3'] ? number_format($row['t3'], 0, ',', '.') : '-' }}</td>
                                    <td class="text-right">{{ $row['t4'] ? number_format($row['t4'], 0, ',', '.') : '-' }}</td>
                                    <td class="text-right font-bold">{{ number_format($row['ttl'], 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $row['st'] ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openPrintPreview() {
        const printArea = document.getElementById('document-preview');
        if (!printArea) return;
        
        const content = printArea.innerHTML;
        
        const win = window.open('', '_blank', 'width=1200,height=900');
        if (!win) {
            alert('Silakan izinkan popup untuk mencetak laporan.');
            return;
        }
        
        win.document.open();
        win.document.write(`
            <!doctype html>
            <html>
            <head>
                <title>Cetak Daftar Kontrak Belanja Modal</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile-landscape { width: 330mm; min-height: 210mm; margin: 0 auto; background: #fff; padding: 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile-landscape h1 { font-size: 20px; font-weight: 800; text-transform: uppercase; margin: 0; text-align: center; }
                    .preview-paper-mobile-landscape h2 { font-size: 16px; font-weight: 700; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape h3 { font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 8px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    @media print { 
                        body { padding: 0; }
                        @page { size: 330mm 210mm; margin: 10mm; }
                    }
                </style>
            </head>
            <body>
                <div class="preview-paper-mobile-landscape">
                    ${content}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() { window.close(); };
                    };
                <\/script>
            </body>
            </html>
        `);
        win.document.close();
    }
</script>
@endsection
