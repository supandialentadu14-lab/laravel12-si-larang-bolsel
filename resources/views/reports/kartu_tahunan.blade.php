@extends('layouts.report_print')

@section('title', 'Cetak Kartu Persediaan Tahunan')
@section('body_class', 'landscape')
@section('back_url', route('dashboard'))

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
        <label class="text-[10px] uppercase font-bold text-gray-400">Filter:</label>
        <select name="product_id" class="rounded-lg border border-gray-300 px-2 py-1 text-xs outline-none">
            @foreach($allProducts as $p)
                <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
        <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border border-gray-300 px-2 py-1 text-xs outline-none">
        <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border border-gray-300 px-2 py-1 text-xs outline-none">
        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold">Apply</button>
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
            <td><strong>Nama Barang</strong></td>
            <td>:</td>
            <td><strong>{{ $product->name }}</strong></td>
        </tr>
        <tr>
            <td><strong>Satuan</strong></td>
            <td>:</td>
            <td><strong>{{ $product->satuan ?? '-' }}</strong></td>
        </tr>
    </table>

    <table class="report-table text-center w-full">
        <thead>
            <tr class="text-center">
                <th rowspan="2">No</th><th rowspan="2">Tanggal</th><th rowspan="2">Nomor Surat Dasar Penerimaan / Pengeluaran</th><th rowspan="2">Uraian</th>
                <th colspan="3">Barang-Barang</th><th rowspan="2">Harga Satuan (Rp)</th><th colspan="3">Jumlah Harga (Rp)</th><th rowspan="2">Keterangan</th>
            </tr>
            <tr class="text-center uppercase text-[9px]">
                <th>Masuk</th><th>Keluar</th><th>Sisa</th><th>Masuk</th><th>Keluar</th><th>Sisa</th>
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
                    <td></td>
                </tr>
            @endforeach
            <tr class="text-[11px]">
                <td colspan="6" align="center"><strong>Saldo Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong></td>
                <td align="center"><strong>{{ $saldo == 0 ? 'Nihil' : $saldo }}</strong></td>
                <td colspan="3"></td>
                <td align="right"><strong>{{ $saldo == 0 ? 'Nihil' : number_format($saldo * (count($rows)>0 ? ($rows[count($rows)-1]['harga'] ?? 0) : 0), 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="mt-8 pb-4" style="page-break-inside: avoid;">
        <table class="w-full text-center" style="font-size: 13px; line-height: 1.2;">
            <tr>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Mengetahui,<br>Pengurus Barang</div>
                    <strong><u>{{ $opd->pengurus_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->pengurus_nip ?? '' }}</div>
                </td>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Mengesahkan,<br>Kepala SKPD</div>
                    <strong><u>{{ $opd->kepala_nama ?? '' }}</u></strong><br>
                    <div>NIP. {{ $opd->kepala_nip ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>
@endsection
