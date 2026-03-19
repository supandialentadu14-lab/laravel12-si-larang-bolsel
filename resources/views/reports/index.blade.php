@extends('layouts.report_print')

@section('title', 'Laporan Persediaan Barang Habis Pakai')
@section('body_class', 'landscape')
@section('back_url', route('dashboard'))

@section('styles')
<style>
    .report-table { border-collapse: collapse; width: 100%; min-width: 800px; }
    .report-table th, .report-table td { border: 1px solid black; padding: 4px; font-size: 11px; color: black; }
    .report-table th { text-align: center; font-weight: bold; }
    
    .report-table thead { display: table-header-group; }
    .report-table tbody { display: table-row-group; }
    .report-table tr { page-break-inside: avoid; }
    
    .info-table { border-collapse: collapse; margin-bottom: 1rem; font-size: 13px; width: 100%; }
    .info-table td { border: none !important; padding: 2px 4px; color: black; }

    /* Custom Membelah Kolom Jumlah & Satuan */
    td.split-col { position: relative; padding: 0 !important; --qty-w: 36px; }
    td.split-col::after { 
        content: ''; 
        position: absolute; 
        left: var(--qty-w); 
        top: 0; 
        bottom: 0; 
        border-left: 1px solid black; 
        pointer-events: none; 
    }
    .split-cell { display: flex; width: 100%; height: 100%; min-height: 22px; align-items: stretch; }
    .split-cell .left { flex: 0 0 var(--qty-w); text-align: center; padding: 2px 0; display: flex; align-items: center; justify-content: center; font-size: 10px; color: black; }
    .split-cell .right { flex: 1 1 auto; text-align: center; padding: 2px 4px; font-size: 10px; display: flex; align-items: center; justify-content: center; color: black; }

    @media print {
        @page { size: landscape; margin: 10mm; }
    }
</style>
@endsection

@section('extra_buttons')
    <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
        <label class="text-[10px] uppercase font-bold text-gray-400">Filter:</label>
        <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border border-gray-300 px-2 py-1 text-xs outline-none">
        <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border border-gray-300 px-2 py-1 text-xs outline-none">
        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold">Apply</button>
    </form>
@endsection

@section('report_content')
    <div class="text-center mb-4 pb-2">
        <h2 class="text-xl font-bold uppercase underline">Laporan Persediaan Barang Habis Pakai</h2>
        <h5 class="text-xs font-semibold mt-1">Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</h5>
    </div>

    <table class="info-table mb-4">
        <tr>
            <td width="140"><strong>SKPD</strong></td>
            <td width="10">:</td>
            <td><strong>{{ $master['opd']['nama'] ?? null ?: $opd->nama_opd ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Kabupaten</strong></td>
            <td>:</td>
            <td><strong>Bolaang Mongondow Selatan</strong></td>
        </tr>
    </table>

    <table class="report-table text-center w-full">
        <thead>
            <tr>
                <th rowspan="2" style="width: 35px;">No</th>
                <th rowspan="2" style="width: 14rem;">Nama Barang</th>
                <th colspan="3">SALDO AWAL</th>
                <th colspan="3">MUTASI MASUK</th>
                <th colspan="3">MUTASI KELUAR</th>
                <th colspan="3">SALDO AKHIR</th>
            </tr>
            <tr class="text-[9px] font-bold">
                @for ($i=0; $i<4; $i++)
                    <th>Jmlh Brg</th><th>Harga (Rp)</th><th>Jumlah (Rp)</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $saldo = [];
                $lastSaldoPerProduct = [];
                $lastDateForChunking = null;
                $dateNo = 1;
            @endphp

            @forelse ($reportData as $index => $item)
                @php
                    $currentDate = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                    
                    if ($lastDateForChunking !== $currentDate) {
                        $dateNo = 1;
                        echo '<tr class="font-bold text-left"><td colspan="14" class="px-2 py-1 text-[11px]">Tanggal : ' . \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') . '</td></tr>';
                        $lastDateForChunking = $currentDate;
                    }

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

                <tr style="font-size: 10px;">
                    <td>{{ $dateNo++ }}</td>
                    <td align="left">{{ $item['name'] }}</td>
                    
                    <td class="p-0 split-col"><div class="split-cell"><div class="left font-semibold">{{ $saldoAwal }}</div><div class="right">{{ $satuan }}</div></div></td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($saldoAwal * $harga, 0, ',', '.') }}</td>
                    
                    <td class="p-0 split-col"><div class="split-cell"><div class="left font-bold">{{ $masuk ?: '0' }}</div><div class="right">{{ $satuan }}</div></div></td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($masuk * $harga, 0, ',', '.') }}</td>
                    
                    <td class="p-0 split-col"><div class="split-cell"><div class="left font-bold">{{ $keluar ?: '0' }}</div><div class="right">{{ $satuan }}</div></div></td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($keluar * $harga, 0, ',', '.') }}</td>
                    
                    <td class="p-0 split-col font-bold"><div class="split-cell"><div class="left">{{ $saldoAkhir }}</div><div class="right">{{ $satuan }}</div></div></td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right" class="font-bold">{{ number_format($saldoAkhir * $harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="14" class="py-6 text-gray-400 text-center">Tidak ada data</td></tr>
            @endforelse

            @php
                $grandTotalAll = 0;
                foreach ($lastSaldoPerProduct as $d) {
                    $grandTotalAll += ($d['saldo'] * $d['harga']);
                }
            @endphp
            
            @if(count($reportData) > 0)
            <tr class="font-bold">
                <td colspan="13" align="right" class="px-3 py-1">TOTAL NILAI PERSEDIAAN</td>
                <td align="right" class="px-3 py-1">{{ number_format($grandTotalAll, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-8 pb-4" style="page-break-inside: avoid;">
        <table class="w-full text-center" style="font-size: 13px; line-height: 1.2;">
            <tr>
                <td width="50%" align="center">
                    <div style="margin-bottom: 0;">Dibuat Oleh</div>
                    <div style="margin-bottom: 50px;" class="font-semibold">Pengurus Barang</div>
                    <strong><u>{{ $opd->pengurus_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->pengurus_nip ?? '' }}</div>
                </td>
                <td width="50%" align="center">
                    <div style="margin-bottom: 0;">Mengetahui</div>
                    <div style="margin-bottom: 50px;" class="font-semibold">Kepala Dinas</div>
                    <strong><u>{{ $opd->kepala_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->kepala_nip ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection
