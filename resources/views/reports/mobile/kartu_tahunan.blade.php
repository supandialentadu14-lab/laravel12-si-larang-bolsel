@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Kartu Persediaan Tahunan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Filter Form (Simplified for Mobile) --}}
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
        <form method="GET" action="{{ route('reports.kartu.tahunan') }}" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-2xl border-slate-100 text-xs bg-slate-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-2xl border-slate-100 text-xs bg-slate-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- Document Preview Card --}}
    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden flex flex-col items-center">
        <div class="w-full flex justify-center no-scrollbar overflow-x-auto">
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
                    .preview-paper-mobile-landscape h2 { font-size: 20px; font-weight: 800; text-transform: uppercase; margin: 0; text-align: center; }
                    .preview-paper-mobile-landscape .subtitle { font-size: 18px; font-weight: 800; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape .date-info { font-size: 14px; font-weight: 700; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 6px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .info-table-mobile { border: none !important; margin-bottom: 15px; width: auto !important; }
                    .info-table-mobile td { border: none !important; padding: 2px 5px; font-size: 14px; }
                </style>
                <div id="document-preview" class="preview-paper-mobile-landscape">
                    <div class="text-center mb-8">
                        <h2>KARTU PERSEDIAAN BARANG</h2>
                        <div class="subtitle">DI LINGKUNGAN PEMERINTAH KABUPATEN BOLAANG MONGONDOW SELATAN</div>
                        <div class="date-info">Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</div>
                    </div>

                    <table class="info-table-mobile">
                        <tr>
                            <td width="150"><strong>SKPD</strong></td>
                            <td width="15">:</td>
                            <td>{{ ($master['opd']['nama'] ?? null) ?: ($opd->nama_opd ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kabupaten</strong></td>
                            <td>:</td>
                            <td>Bolaang Mongondow Selatan</td>
                        </tr>
                    </table>

                    @foreach ($grouped ?? [] as $data)
                        @php
                            $product = $data['product'];
                            $rows = $data['rows'];
                            $saldo = 0;
                            $harga = 0;
                        @endphp
                        <table class="info-table-mobile" style="margin-top: 20px;">
                            <tr>
                                <td width="150"><strong>Nama barang</strong></td>
                                <td width="15">:</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Satuan</strong></td>
                                <td>:</td>
                                <td>{{ $product->unit }}</td>
                            </tr>
                        </table>

                        <table>
                            <colgroup>
                                <col style="width:2%">
                                <col style="width:9%">
                                <col style="width:18%">
                                <col style="width:16%">
                                <col style="width:4%">
                                <col style="width:4%">
                                <col style="width:4%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:7%">
                                <col style="width:10%">
                            </colgroup>
                            <thead>
                                <tr style="background-color: #f8fafc;">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Nomor Surat Dasar</th>
                                    <th rowspan="2">Uraian</th>
                                    <th colspan="3">Barang-Barang</th>
                                    <th rowspan="2">Harga Satuan</th>
                                    <th colspan="3">Jumlah Harga (Rp)</th>
                                    <th rowspan="2">Keterangan</th>
                                </tr>
                                <tr style="background-color: #f8fafc;">
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Sisa</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($rows as $row)
                                    @php
                                        $saldo += $row['masuk'] - $row['keluar'];
                                        $harga = $row['harga'] ?? 0;
                                    @endphp
                                    <tr class="text-center">
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $row['nosur'] }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $row['masuk'] ?: '' }}</td>
                                        <td>{{ $row['keluar'] ?: '' }}</td>
                                        <td class="font-bold">{{ $saldo }}</td>
                                        <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                                        <td class="text-right">{{ $row['masuk'] ? number_format($row['masuk'] * $harga, 0, ',', '.') : '' }}</td>
                                        <td class="text-right">{{ $row['keluar'] ? number_format($row['keluar'] * $harga, 0, ',', '.') : '' }}</td>
                                        <td class="text-right font-bold">{{ number_format($saldo * $harga, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #f8fafc;">
                                    <td colspan="6" class="text-center font-bold">Saldo Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</td>
                                    <td class="text-center font-bold">{{ $saldo == 0 ? 'Nihil' : $saldo }}</td>
                                    <td colspan="3"></td>
                                    <td class="text-right font-bold">{{ $saldo == 0 ? 'Nihil' : number_format($saldo * $harga, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-top: 60px; text-align: center;">
                        <div>
                            <p>Dibuat Oleh</p>
                            <p>Pengurus Barang</p>
                            <div style="height: 100px;"></div>
                            <p class="font-bold underline uppercase">{{ $opd->pengurus_nama ?? '' }}</p>
                            <p>NIP. {{ $opd->pengurus_nip ?? '' }}</p>
                        </div>
                        <div>
                            <p>Mengetahui</p>
                            <p>Kepala Dinas</p>
                            <div style="height: 100px;"></div>
                            <p class="font-bold underline uppercase">{{ $opd->kepala_nama ?? '' }}</p>
                            <p>NIP. {{ $opd->kepala_nip ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-center gap-2 text-slate-400">
            <i class="fas fa-arrows-left-right text-xs animate-pulse"></i>
            <p class="text-[10px] font-bold uppercase tracking-widest">Geser untuk melihat detail dokumen</p>
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
                <title>Cetak Kartu Persediaan Tahunan</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile-landscape { width: 330mm; min-height: 210mm; margin: 0 auto; background: #fff; padding: 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile-landscape h2 { font-size: 20px; font-weight: 800; text-transform: uppercase; margin: 0; text-align: center; }
                    .preview-paper-mobile-landscape .subtitle { font-size: 18px; font-weight: 800; text-transform: uppercase; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape .date-info { font-size: 14px; font-weight: 700; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 6px; font-size: 12px; }
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
