@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Berita Acara Stock Opname</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.opname.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Document Preview Card --}}
    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden">
        <div id="paper-container" class="w-full no-scrollbar flex justify-center items-start" style="padding-bottom:8px;">
            <div id="paper-scale" class="flex-shrink-0" style="transform-origin: top center; width: 850px; margin: 0 auto;">
                <style>
                    .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                    .signature-block { break-inside: avoid; page-break-inside: avoid; }
                    .signature-block * { break-inside: avoid; page-break-inside: avoid; }
                </style>
                <div id="document-preview" class="preview-paper-mobile">
                    @include('partials.kop', ['opd' => $opd])
                    
                    <div class="text-center mb-6" style="margin-top: 30px;">
                        <h2 class="font-bold" style="font-size: 18px; margin-bottom: 0;">BERITA ACARA</h2>
                        <h2 class="font-bold underline uppercase" style="font-size: 18px; margin-top: 0;">HASIL STOCK OPNAME PERSEDIAAN BARANG HABIS PAKAI</h2>
                        <p style="font-size: 14px;">NO: {{ $data['nomor'] ?? '' }}</p>
                    </div>

                    <p>{{ $data['pembuka'] ?? '' }}</p>

                    <table style="border: none; width: 100%; margin: 15px 0;">
                        <tr style="border: none;">
                            <td style="border: none; width: 120px;">Nama</td>
                            <td style="border: none; width: 15px;">:</td>
                            <td style="border: none;"><span class="font-bold">{{ $data['pihak_kedua']['nama'] }}</span></td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;">NIP</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">{{ $data['pihak_kedua']['nip'] ?? '-' }}</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;">Jabatan</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">{{ $data['pihak_kedua']['jabatan'] }}</td>
                        </tr>
                    </table>

                    <p>Sebagai pengurus barang pengguna berdasarkan Surat Keputusan Bupati Bolaang Mongondow Selatan Nomor: 27 Tahun 2025 Tanggal 6 Januari 2025 telah melaksanakan Stock Opname Persediaan Barang Habis Pakai per {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}, dengan hasil sebagai berikut:</p>

                    <table style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 34px;">
                            <col>
                            <col style="width: 64px;">
                            <col style="width: 60px;">
                            <col style="width: 112px;">
                            <col style="width: 120px;">
                            <col style="width: 20px;">
                            <col style="width: 20px;">
                            <col style="width: 20px;">
                        </colgroup>
                        <thead>
                            <tr class="text-center font-bold" style="background-color: #f8fafc;">
                                <th rowspan="2" style="width: 34px;">No</th>
                                <th rowspan="2">Nama Jenis Persediaan Barang</th>
                                <th rowspan="2" style="white-space: nowrap; font-size: 11px;">Kwantitas</th>
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Harga Satuan (Rp)</th>
                                <th rowspan="2">Jumlah Harga (Rp)</th>
                                <th colspan="3">Kondisi Barang</th>
                            </tr>
                            <tr class="text-center font-bold" style="background-color: #f8fafc;">
                                <th style="width: 20px;">B</th>
                                <th style="width: 20px;">RR</th>
                                <th style="width: 20px;">RB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($data['items'] as $i => $item)
                                @php $total += (int)($item['jumlah'] ?? 0); @endphp
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                    <td class="text-center">{{ $item['kuantitas'] }}</td>
                                    <td class="text-center">{{ $item['satuan'] ?? '-' }}</td>
                                    <td class="text-right">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-right font-bold">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'B' ? 'V' : '' }}</td>
                                    <td class="text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'RR' ? 'V' : '' }}</td>
                                    <td class="text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'RB' ? 'V' : '' }}</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #f8fafc;">
                                <td colspan="5" class="text-right font-bold">Jumlah Total</td>
                                <td class="text-right font-bold" style="font-size: 14px; color: #1e293b;">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 20px;">Demikian Berita Acara Stock Opname Persediaan Barang Habis Pakai ini dibuat untuk diperlukan sebagaimana mestinya.</p>
                    
                    <table class="signature-block" style="border: none; width: 100%; margin-top: 40px;">
                        <tr style="border: none;">
                            <td style="border: none; width: 50%; text-align: center;">
                                <p>&nbsp;</p>
                                <p>Pengurus Barang Pengguna</p>
                                <div style="height: 80px;"></div>
                                <p class="font-bold underline">{{ $opd->pengurus_nama ?? ($data['pihak_kedua']['nama'] ?? '') }}</p>
                                <p>NIP. {{ $opd->pengurus_nip ?? ($data['pihak_kedua']['nip'] ?? '-') }}</p>
                            </td>
                            <td style="border: none; width: 50%; text-align: center;">
                                <p>Mengetahui</p>
                                <p>Kepala Dinas Komunikasi dan Informatika</p>
                                <div style="height: 80px;"></div>
                                <p class="font-bold underline">{{ $opd->kepala_nama ?? ($data['pihak_pertama']['nama'] ?? '') }}</p>
                                <p>NIP. {{ $opd->kepala_nip ?? ($data['pihak_pertama']['nip'] ?? '-') }}</p>
                            </td>
                        </tr>
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
        
        const win = window.open('', '_blank', 'width=900,height=1200');
        if (!win) {
            alert('Silakan izinkan popup untuk mencetak laporan.');
            return;
        }
        
        win.document.open();
        win.document.write(`
            <!doctype html>
            <html>
            <head>
                <title>Cetak Berita Acara Stock Opname</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                    .signature-block { break-inside: avoid; page-break-inside: avoid; }
                    .signature-block * { break-inside: avoid; page-break-inside: avoid; }
                    @media print { 
                        body { padding: 0; }
                        @page { size: 210mm 330mm; margin: 10mm; }
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
            </html>
        `);
        win.document.close();
    }
    (function() {
        const paperScale = document.getElementById('paper-scale');
        const doc = document.getElementById('document-preview');
        const container = document.getElementById('paper-container');
        function fit() {
            if (!paperScale || !doc || !container) return;
            const baseW = 850;
            const baseH = doc.scrollHeight || 1200;
            const rect = container.getBoundingClientRect();
            const availW = container.clientWidth;
            const availH = Math.max(320, window.innerHeight - rect.top - 12);
            const scale = Math.min(availW / baseW, availH / baseH);
            const clamped = Math.max(0.22, Math.min(scale, 1));
            paperScale.style.transform = `scale(${clamped})`;
            paperScale.style.marginLeft = 'auto';
            paperScale.style.marginRight = 'auto';
        }
        window.addEventListener('resize', fit);
        document.addEventListener('DOMContentLoaded', fit);
        setTimeout(fit, 0);
    })();
</script>
@endsection
