@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Laporan Persediaan Barang</p>
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

    {{-- Filter Form --}}
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
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
            <div class="flex-shrink-0 origin-top transform scale-[0.22] min-[400px]:scale-[0.28] sm:scale-100 mb-[-160%] min-[400px]:mb-[-140%] sm:mb-0" style="width: 330mm;">
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
                    .preview-paper-mobile-landscape h5 { font-size: 14px; font-weight: 700; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 6px; font-size: 11px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-left { text-align: left; }
                    .font-bold { font-weight: bold; }
                    .info-table-mobile { border: none !important; margin-bottom: 15px; width: auto !important; }
                    .info-table-mobile td { border: none !important; padding: 2px 5px; font-size: 14px; }
                </style>
                <div id="document-preview" class="preview-paper-mobile-landscape">
                    <div class="border-b-2 border-black pb-6 mb-6">
                        <div class="text-center mb-6">
                            <h1>LAPORAN PERSEDIAAN BARANG HABIS PAKAI</h1>
                            <h5>Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</h5>
                        </div>

                        <table class="info-table-mobile">
                            <tr>
                                <td width="150"><strong>SKPD</strong></td>
                                <td width="15">:</td>
                                <td>{{ $master['opd']['nama'] ?? null ?: $opd->nama_opd ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kabupaten</strong></td>
                                <td>:</td>
                                <td>Bolaang Mongondow Selatan</td>
                            </tr>
                        </table>
                    </div>

                    <table>
                        <thead>
                            <tr style="background-color: #f8fafc;" class="font-bold">
                                <th rowspan="2" style="width: 30px;">No</th>
                                <th rowspan="2">Nama Barang</th>
                                <th colspan="3">SALDO AWAL</th>
                                <th colspan="3">MUTASI MASUK</th>
                                <th colspan="3">MUTASI KELUAR</th>
                                <th colspan="3">SALDO AKHIR</th>
                            </tr>
                            <tr style="background-color: #f8fafc;" class="font-bold">
                                @for ($i = 0; $i < 4; $i++)
                                    <th>Jml</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $saldo = [];
                                $lastSaldoPerProduct = [];
                                $lastDate = null;
                            @endphp

                            @forelse($reportData ?? [] as $item)
                                @php
                                    $currentDate = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                                @endphp

                                @if ($lastDate != $currentDate)
                                    <tr style="background-color: #f1f5f9;" class="font-bold text-left">
                                        <td colspan="14" style="padding: 8px;">
                                            Tanggal : {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') }}
                                        </td>
                                    </tr>
                                    @php $lastDate = $currentDate; @endphp
                                @endif

                                @php
                                    $productId = $item['product_id'];
                                    $harga = $item['harga'];
                                    $satuan = $item['satuan'] ?? '';
                                    if (!isset($saldo[$productId])) $saldo[$productId] = 0;
                                    $saldoAwal = $saldo[$productId];
                                    $masuk = $item['masuk'];
                                    $keluar = $item['keluar'];
                                    $saldoAkhir = $saldoAwal + $masuk - $keluar;
                                    $saldo[$productId] = $saldoAkhir;
                                    $lastSaldoPerProduct[$productId] = ['saldo' => $saldoAkhir, 'harga' => $harga];
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-left">{{ $item['name'] }}</td>
                                    <td class="text-center">{{ $saldoAwal }}</td>
                                    <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($saldoAwal * $harga, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold" style="color: #16a34a;">{{ $masuk }}</td>
                                    <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($masuk * $harga, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold" style="color: #dc2626;">{{ $keluar }}</td>
                                    <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($keluar * $harga, 0, ',', '.') }}</td>
                                    <td class="text-center font-bold">{{ $saldoAkhir }}</td>
                                    <td class="text-right">{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td class="text-right font-bold">{{ number_format($saldoAkhir * $harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center" style="padding: 40px; color: #94a3b8;">Tidak ada data</td>
                                </tr>
                            @endforelse

                            @php
                                $grandTotal = 0;
                                foreach ($lastSaldoPerProduct as $data) { $grandTotal += $data['saldo'] * $data['harga']; }
                            @endphp

                            <tr style="background-color: #f1f5f9;" class="font-bold">
                                <td colspan="13" class="text-right" style="padding: 12px;">TOTAL NILAI PERSEDIAAN</td>
                                <td class="text-center" style="font-size: 14px;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-top: 60px; text-align: center;">
                        <div>
                            <p class="font-bold">Dibuat Oleh</p>
                            <p>Pengurus Barang</p>
                            <div style="height: 100px;"></div>
                            <p class="font-bold underline uppercase">{{ $opd->pengurus_nama ?? '' }}</p>
                            <p>NIP. {{ $opd->pengurus_nip ?? '' }}</p>
                        </div>
                        <div>
                            <p class="font-bold">Mengetahui</p>
                            <p>Kepala Dinas</p>
                            <div style="height: 100px;"></div>
                            <p class="font-bold underline uppercase">{{ $opd->kepala_nama ?? '' }}</p>
                            <p>NIP. {{ $opd->kepala_nip ?? '' }}</p>
                        </div>
                    </div>
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
                <title>Cetak Laporan Persediaan</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile-landscape { width: 330mm; min-height: 210mm; margin: 0 auto; background: #fff; padding: 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile-landscape h1 { font-size: 20px; font-weight: 800; text-transform: uppercase; margin: 0; text-align: center; }
                    .preview-paper-mobile-landscape h5 { font-size: 14px; font-weight: 700; margin: 5px 0; text-align: center; }
                    .preview-paper-mobile-landscape table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    .preview-paper-mobile-landscape th, .preview-paper-mobile-landscape td { border: 1px solid black; padding: 6px; font-size: 11px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-left { text-align: left; }
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
