@extends('layouts.report_print')

@section('title', 'Laporan Persediaan Barang Habis Pakai')
@section('report_class', 'landscape')
@section('report_size', '330mm 215mm landscape')
@section('report_width', '330mm')
@section('report_height', '215mm')
@section('back_url', route('dashboard'))

@section('extra_styles')
<style>
    /* Specific tweaks for Laporan Persediaan */
    /* Ensure table fits in landscape without scroll */
    .report-table { border-collapse: collapse; width: 100%; min-width: 950px !important; table-layout: fixed; }
    .report-table th, .report-table td { 
        border: 1px solid black; padding: 0px 4px !important; font-size: 8px !important; color: black; 
        white-space: nowrap !important; line-height: 1.1;
        word-break: keep-all !important; overflow-wrap: normal !important;
    }

    .report-table th { text-align: center; font-weight: bold; }
    
    .report-table thead { display: table-header-group !important; }
    .report-table tbody { display: table-row-group !important; }
    .report-table tr { page-break-inside: avoid !important; }


    
    .info-table { border-collapse: collapse; margin-bottom: 0.5rem; font-size: 11px; width: 100%; }
    .info-table td { border: none !important; padding: 0px 4px; color: black; white-space: nowrap; line-height: 1.0; }


    
    @media print {
        @page { size: landscape; margin: 0; }
        #print-area { margin: 0 !important; padding: 5mm !important; box-shadow: none !important; width: 100% !important; min-height: auto !important; }
        .report-table { width: 100% !important; min-width: 950px !important; table-layout: fixed; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }




</style>
@endsection



@section('extra_buttons')
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col gap-3 w-full">
        <div class="grid grid-cols-2 gap-2 w-full text-left">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase font-bold text-slate-400">Dari</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase font-bold text-slate-400">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
            </div>
        </div>

        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/10 active:scale-[0.98]">
            Filter Laporan
        </button>
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
                <th rowspan="2" style="width: 30px !important;">No</th>
                <th rowspan="2" style="width: 110px !important;">Nama Barang</th>
                <th colspan="4">SALDO AWAL</th>
                <th colspan="4">MUTASI MASUK</th>
                <th colspan="4">MUTASI KELUAR</th>
                <th colspan="4">SALDO AKHIR</th>
            </tr>



            <tr class="text-[7.5px] font-bold">
                @for ($i=0; $i<4; $i++)
                    <th colspan="2" style="width: 35px !important;">Jumlah<br>Barang</th>
                    <th style="width: 70px !important;">Harga<br>(Rp)</th>
                    <th style="width: 80px !important;">Jumlah<br>(Rp)</th>
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
                        echo '<tr class="font-bold text-left"><td colspan="18" class="px-2 py-1 text-[11px]">Tanggal : ' . \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') . '</td></tr>';
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
                    <td class="text-center">{{ $dateNo++ }}</td>
                    <td align="left">{{ $item['name'] }}</td>
                    
                    <td class="text-center">{{ $saldoAwal }}</td>
                    <td class="text-center">{{ $satuan }}</td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($saldoAwal * $harga, 0, ',', '.') }}</td>
                    
                    <td class="text-center font-bold">{{ $masuk ?: '0' }}</td>
                    <td class="text-center">{{ $satuan }}</td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($masuk * $harga, 0, ',', '.') }}</td>
                    
                    <td class="text-center font-bold">{{ $keluar ?: '0' }}</td>
                    <td class="text-center">{{ $satuan }}</td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right">{{ number_format($keluar * $harga, 0, ',', '.') }}</td>
                    
                    <td class="text-center font-bold">{{ $saldoAkhir }}</td>
                    <td class="text-center">{{ $satuan }}</td>
                    <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                    <td align="right" class="font-bold">{{ number_format($saldoAkhir * $harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="18" class="py-6 text-gray-400 text-center">Tidak ada data</td></tr>
            @endforelse

            @php
                $grandTotalAll = 0;
                foreach ($lastSaldoPerProduct as $d) {
                    $grandTotalAll += ($d['saldo'] * $d['harga']);
                }
            @endphp
            
            @if(count($reportData) > 0)
            <tr class="font-bold" style="font-size: 11px;">
                <td colspan="17" align="right" class="px-3 py-2 uppercase">TOTAL NILAI PERSEDIAAN</td>
                <td align="right" class="px-3 py-2">{{ number_format($grandTotalAll, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>

    </table>

    <div class="mt-8 pb-4" style="page-break-inside: avoid;">
        <table class="w-full text-center border-none" style="font-size: 13px; line-height: 1.2; border: none !important;">
            <tr style="border: none !important;">
                <td width="50%" align="center" style="border: none !important;">

                    <div style="margin-bottom: 0;">Dibuat Oleh</div>
                    <div style="margin-bottom: 50px;" class="font-semibold">Pengurus Barang</div>
                    <strong><u>{{ $opd->pengurus_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->pengurus_nip ?? '' }}</div>
                </td>
                <td width="50%" align="center" style="border: none !important;">

                    <div style="margin-bottom: 0;">Mengetahui</div>
                    <div style="margin-bottom: 50px;" class="font-semibold">Kepala Dinas</div>
                    <strong><u>{{ $opd->kepala_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->kepala_nip ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection
