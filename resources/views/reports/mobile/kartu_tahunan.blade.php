@extends('layouts.report_print')

@section('title', 'Cetak Kartu Persediaan Tahunan')
@section('body_class', 'landscape')
@section('back_url', route('stock.index'))

@section('styles')
<style>
    .report-table { border-collapse: collapse; width: 100%; min-width: 800px; }
    .report-table th, .report-table td { border: 1px solid black; padding: 4px; font-size: 11px; color: black; }
    .report-table th { text-align: center; font-weight: bold; background: white; }
    
    .report-table thead { display: table-header-group; }
    .report-table tbody { display: table-row-group; }
    .report-table tr { page-break-inside: avoid; }
    
    .info-table { border-collapse: collapse; margin-bottom: 1rem; font-size: 13px; width: 100%; }
    .info-table td { border: none !important; padding: 2px 4px; color: black; }

    @media print {
        @page { size: landscape; margin: 10mm; }
    }
</style>
@endsection

@section('extra_buttons')
    <form method="GET" action="{{ route('reports.kartu_tahunan') }}" class="flex items-center gap-2">
        <select name="product_id" class="rounded-lg border border-gray-300 px-2 py-1 text-[10px] outline-none w-24">
            @foreach($allProducts as $p)
                <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[10px] font-bold">Apply</button>
    </form>
@endsection

@section('report_content')
    <div class="text-center mb-4 pb-2">
        <h2 class="text-xl font-bold uppercase underline">KARTU PERSEDIAAN TAHUNAN</h2>
        <h5 class="text-xs font-semibold mt-1">Per Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</h5>
    </div>

    <table class="info-table mb-4">
        <tr>
            <td width="140"><strong>SKPD</strong></td>
            <td width="10">:</td>
            <td><strong>{{ $opd->nama_opd ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Barang</strong></td>
            <td>:</td>
            <td><strong>{{ $product->name }}</strong></td>
        </tr>
    </table>

    <table class="report-table text-center w-full">
        <thead>
            <tr class="text-center">
                <th rowspan="2">No</th><th rowspan="2">Tanggal</th><th rowspan="2">Surat Dasar</th><th rowspan="2">Uraian</th>
                <th colspan="3">Barang</th><th rowspan="2">Harga</th><th colspan="3">Jumlah (Rp)</th>
            </tr>
            <tr class="text-center uppercase text-[9px]">
                <th>Msk</th><th>Klr</th><th>Sisa</th><th>Msk</th><th>Klr</th><th>Sisa</th>
            </tr>
        </thead>
        <tbody>
            @php $rowNo = 1; $saldo = 0; @endphp
            @foreach($rows as $row)
                @php $saldo += ($row['masuk'] - $row['keluar']); @endphp
                <tr align="center" style="font-size: 10px;">
                    <td>{{ $rowNo++ }}</td>
                    <td>{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->translatedFormat('d F Y') : '-' }}</td>
                    <td align="left">{{ $row['nosur'] ?? '-' }}</td>
                    <td align="left" class="uppercase font-semibold">{{ $product->name }}</td>
                    <td>{{ $row['masuk'] ?: '-' }}</td>
                    <td>{{ $row['keluar'] ?: '-' }}</td>
                    <td class="font-bold">{{ $saldo }}</td>
                    <td align="right">{{ number_format($row['harga'] ?? 0, 0, ',', '.') }}</td>
                    <td align="right">{{ $row['masuk'] ? number_format($row['masuk'] * ($row['harga'] ?? 0), 0, ',', '.') : '-' }}</td>
                    <td align="right">{{ $row['keluar'] ? number_format($row['keluar'] * ($row['harga'] ?? 0), 0, ',', '.') : '-' }}</td>
                    <td align="right" class="font-bold">{{ number_format($saldo * ($row['harga'] ?? 0), 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-8 pb-4" style="page-break-inside: avoid;">
        <table class="w-full text-center" style="font-size: 13px; line-height: 1.2;">
            <tr>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Pengurus Barang</div>
                    <strong><u>{{ $opd->pengurus_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->pengurus_nip ?? '' }}</div>
                </td>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Kepala SKPD</div>
                    <strong><u>{{ $opd->kepala_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->kepala_nip ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection
