@extends('layouts.report_print')

@section('title', 'Cetak Berita Acara Stock Opname')
@section('back_url', route('reports.opname.list'))

@section('styles')
<style>
    .report-paper { max-width: 210mm !important; }
    @media print {
        @page { size: 210mm 330mm; margin: 10mm 15mm; }
    }
</style>
@endsection

@section('report_content')
<div id="print-area">
  <div class="mb-4">
    @include('partials.kop', ['opd' => $opd])
  </div>

  <div class="text-center mb-4">
    <h2 class="font-bold text-lg">BERITA ACARA</h2>
    <h2 class="font-bold text-lg underline uppercase">HASIL STOCK OPNAME PERSEDIAAN BARANG HABIS PAKAI</h2>
    <p class="text-sm">NO: {{ $data['nomor'] ?? '' }}</p>
  </div>

  <p class="mb-3 text-sm">
    {{ $data['pembuka'] ?? 'Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l d F Y') . ', bertempat di ' . \Illuminate\Support\Str::title(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-')) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:' }}
  </p>

  <div class="mb-4">
    <table class="w-full text-sm">
        <tr>
          <td class="w-24 align-top">Nama</td>
          <td class="w-2 align-top">:</td>
          <td class="align-top"><span class="font-bold">{{ $data['pihak_kedua']['nama'] }}</span></td>
        </tr>
    </table>
    <p class="mt-2 text-sm">Telah melaksanakan Stock Opname Persediaan per {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }}</p>
  </div>

  <div class="mb-6">
    <table class="w-full text-[10px] border-collapse border border-black">
      <thead>
        <tr class="text-center font-bold">
          <th class="border border-black p-1">No</th>
          <th class="border border-black p-1">Barang</th>
          <th class="border border-black p-1">Jml</th>
          <th class="border border-black p-1">Harga</th>
          <th class="border border-black p-1">Total</th>
        </tr>
      </thead>
      <tbody>
        @php $total = 0; @endphp
        @foreach ($data['items'] as $i => $item)
          @php $total += (int)($item['jumlah'] ?? 0); @endphp
          <tr>
            <td class="border border-black p-1 text-center">{{ $i + 1 }}</td>
            <td class="border border-black p-1">{{ $item['nama'] }}</td>
            <td class="border border-black p-1 text-center">{{ $item['kuantitas'] }} {{ $item['satuan'] ?? '-' }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
          </tr>
        @endforeach
        <tr class="font-bold">
          <td colspan="4" class="border border-black p-1 text-right">Jumlah</td>
          <td class="border border-black p-1 text-right">{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="grid grid-cols-2 gap-5 mt-2" style="page-break-inside: avoid;">
    <div class="text-center">
      <p class="mb-1 uppercase font-bold text-[10px]">Pengurus Barang</p>
      <div class="h-16"></div>
      <p class="font-bold underline text-xs">{{ $opd->pengurus_nama ?? '' }}</p>
    </div>
    <div class="text-center">
      <p class="mb-1 uppercase font-bold text-[10px]">Kepala SKPD</p>
      <div class="h-16"></div>
      <p class="font-bold underline text-xs">{{ $opd->kepala_nama ?? '' }}</p>
    </div>
  </div>
</div>
@endsection
