@extends('layouts.report_print')

@section('title', 'Cetak Kartu Persediaan Tahunan')
@section('report_class', 'landscape')
@section('report_size', '330mm 215mm landscape')
@section('report_width', '330mm')
@section('report_height', '215mm')
@section('back_url', route('dashboard'))

@section('extra_styles')
<style>
    .report-table th, .report-table td { 
        border: 1px solid black; padding: 1px 2px !important; font-size: 8.5px !important; color: black; 
        line-height: 1.1;
    }

</style>
@endsection

@section('extra_buttons')
    <form method="GET" action="{{ route('reports.kartu.tahunan') }}" class="flex flex-col gap-3 w-full">
        <div class="flex flex-col gap-1 w-full text-left">
            <label class="text-[10px] uppercase font-bold text-slate-400">Pilih Barang</label>
            <select name="product_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
                <option value="">-- Semua Barang --</option>
                @foreach($allProducts as $p)
                    <option value="{{ $p->id }}" {{ ($product && $product->id == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        
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
            <td><strong>Kabupaten</strong></td>
            <td>:</td>
            <td><strong>Bolaang Mongondow Selatan</strong></td>
        </tr>
    </table>

    @forelse($grouped as $productId => $data)
        <div class="product-section mb-4 {{ !$loop->last ? 'border-b border-dashed border-gray-300 pb-3' : '' }}">
            <table class="info-table mb-2">
                <tr>
                    <td width="140"><strong>Barang</strong></td>
                    <td width="10">:</td>
                    <td><strong>{{ $data['product']->name }}</strong></td>
                </tr>
            </table>

            <table class="report-table text-center w-full">
                <thead>
                    <tr class="text-center font-bold">
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">Uraian</th>
                        <th colspan="3">Barang</th>
                        <th colspan="3">Harga (Rp)</th>
                    </tr>
                    <tr class="text-center uppercase text-[8px]">
                        <th>In</th><th>Out</th><th>Sisa</th>
                        <th>In</th><th>Out</th><th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowNo = 1; @endphp
                    @foreach($data['rows'] as $row)
                        <tr align="center" style="font-size: 10px;">
                            <td>{{ $rowNo++ }}</td>
                            <td>{{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->translatedFormat('d/m/y') : '-' }}</td>
                            <td align="left" style="font-size: 9px;">{{ $data['product']->name }}</td>
                            <td>{{ $row['masuk'] ? number_format($row['masuk'], 0, ',', '.') : '-' }}</td>
                            <td>{{ $row['keluar'] ? number_format($row['keluar'], 0, ',', '.') : '-' }}</td>
                            <td class="font-bold">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            <td align="right">{{ $row['masuk'] ? number_format($row['masuk'] * ($row['harga'] ?? 0), 0, ',', '.') : '-' }}</td>
                            <td align="right">{{ $row['keluar'] ? number_format($row['keluar'] * ($row['harga'] ?? 0), 0, ',', '.') : '-' }}</td>
                            <td align="right" class="font-bold">{{ number_format($row['saldo'] * ($row['harga'] ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="text-[10px] font-bold">
                        <td colspan="5">Saldo Akhir</td>
                        <td>{{ number_format($data['saldo_akhir'], 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                        <td align="right">{{ number_format($data['saldo_akhir'] * (count($data['rows'])>0 ? ($data['rows'][count($data['rows'])-1]['harga'] ?? 0) : 0), 0, ',', '.') }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    @empty
        <div class="text-center py-10 text-gray-400 font-bold">Tidak ada data.</div>
    @endforelse

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
