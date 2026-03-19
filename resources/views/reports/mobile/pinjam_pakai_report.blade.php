@extends('layouts.report_print')

@section('title', 'Cetak Berita Acara Pinjam Pakai')
@section('back_url', route('reports.pinjam.list'))

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

    <div class="text-center mb-1">
      <h2 class="font-extrabold text-lg underline ">BERITA ACARA SERAH TERIMA BARANG INVENTARIS</h2>
      <p class="text-xs">NO: {{ $data['nomor'] }}</p>
    </div>

    <p class="mb-2 text-xs">
        {{ $data['pembuka'] ?? ('Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('l d F Y') . ', bertempat di ' . \Illuminate\Support\Str::title(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-')) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:') }}
    </p>

    <div class="mb-2">
      <table class="w-full text-xs">
        <tr>
          <td class="w-24 align-top">Nama</td>
          <td class="w-2 align-top">:</td>
          <td class="align-top font-bold">{{ $data['pihak_pertama']['nama'] }}</td>
        </tr>
      </table>
      <p class="mt-1 text-xs font-bold">PIHAK PERTAMA</p>
    </div>

    <div class="mb-2">
      <table class="w-full text-xs">
        <tr>
          <td class="w-24 align-top">Nama</td>
          <td class="w-2 align-top">:</td>
          <td class="align-top font-bold">{{ $data['pihak_kedua']['nama'] }}</td>
        </tr>
      </table>
      <p class="mt-1 text-xs font-bold">PIHAK KEDUA</p>
    </div>

    <p class="mb-2 text-xs">
      Sepakat serah terima barang inventaris : 
    </p>

    <div class="mb-3">
      <table class="w-full text-[10px] border-collapse border border-black">
        <thead>
          <tr class="text-center font-bold">
            <th class="border border-black p-1">No</th>
            <th class="border border-black p-1">Nama Barang</th>
            <th class="border border-black p-1">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data['items'] as $i => $item)
            <tr>
              <td class="border border-black p-1 text-center">{{ $i + 1 }}</td>
              <td class="border border-black p-1">{{ $item['nama'] }}</td>
              <td class="border border-black p-1 text-center font-bold">{{ $item['jumlah'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <p class="mb-1 text-xs">Demikian Berita Acara ini dibuat.</p>
    
    <div class="text-right text-xs mb-1">
      {{ $data['tempat'] ?? 'Bolaang Mongondow Selatan' }}, {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}
    </div>

    <div class="grid grid-cols-2 gap-4 mt-2" style="page-break-inside: avoid;">
      <div class="text-center">
        <p class="mb-1 font-bold text-[10px]">Pihak Kedua</p>
        <div class="h-12"></div>
        <p class="font-bold underline text-xs">{{ $data['pihak_kedua']['nama'] }}</p>
      </div>
      <div class="text-center">
        <p class="mb-1 font-bold text-[10px]">Pihak Pertama</p>
        <div class="h-12"></div>
        <p class="font-bold underline text-xs">{{ $data['pihak_pertama']['nama'] }}</p>
      </div>
    </div>
</div>
@endsection
