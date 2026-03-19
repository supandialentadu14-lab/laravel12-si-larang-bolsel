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
          <td class="w-28 align-top">Nama</td>
          <td class="w-4 align-top">:</td>
          <td class="align-top"><span class="font-bold">{{ $data['pihak_kedua']['nama'] }}</span></td>
        </tr>
        <tr>
          <td class="align-top">NIP</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_kedua']['nip'] ?? '-' }}</td>
        </tr>
        <tr>
          <td class="align-top">Jabatan</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_kedua']['jabatan'] }}</td>
        </tr>
    </table>
    <p class="mt-2 text-sm">Sebagai pengurus barang pengguna berdasarkan Surat Keputusan Bupati Bolaang
      Mongondow Selatan Nomor: {{ $opd->pengurus_sk ?? '27 Tahun 2026' }} telah melaksanakan Stock Opname
      Persediaan Barang Habis Pakai per 
      {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y') }},
      dengan hasil sebagai berikut</p>
  </div>

  <div class="mb-6">
    <table class="w-full text-[10px] border-collapse" style="table-layout: fixed; border: 1px solid black;">
      <colgroup>
        <col style="width: 30px;">
        <col>
        <col style="width: 55px;">
        <col style="width: 50px;">
        <col style="width: 90px;">
        <col style="width: 100px;">
        <col style="width: 20px;">
        <col style="width: 20px;">
        <col style="width: 20px;">
      </colgroup>
      <thead>
        <tr class="text-center font-bold">
          <th class="border border-black p-1" rowspan="2">No</th>
          <th class="border border-black p-1" rowspan="2">Nama Jenis Persediaan Barang</th>
          <th class="border border-black p-1" rowspan="2">Kwantitas</th>
          <th class="border border-black p-1" rowspan="2">Satuan</th>
          <th class="border border-black p-1" rowspan="2">Harga Satuan (Rp)</th>
          <th class="border border-black p-1" rowspan="2">Jumlah Harga (Rp)</th>
          <th class="border border-black p-1" colspan="3">Kondisi</th>
        </tr>
        <tr class="text-center font-bold text-[8px]">
          <th class="border border-black p-1">B</th>
          <th class="border border-black p-1">RR</th>
          <th class="border border-black p-1">RB</th>
        </tr>
      </thead>
      <tbody>
        @php $total = 0; @endphp
        @foreach ($data['items'] as $i => $item)
          @php $total += (int)($item['jumlah'] ?? 0); @endphp
          <tr>
            <td class="border border-black p-1 text-center">{{ $i + 1 }}</td>
            <td class="border border-black p-1">{{ $item['nama'] }}</td>
            <td class="border border-black p-1 text-center">{{ $item['kuantitas'] }}</td>
            <td class="border border-black p-1 text-center">{{ $item['satuan'] ?? '-' }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
            <td class="border border-black p-1 text-right">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
            <td class="border border-black p-1 text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'B' ? 'V' : '' }}</td>
            <td class="border border-black p-1 text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'RR' ? 'V' : '' }}</td>
            <td class="border border-black p-1 text-center">{{ isset($item['kondisi']) && $item['kondisi'] === 'RB' ? 'V' : '' }}</td>
          </tr>
        @endforeach
        <tr class="font-bold">
          <td colspan="5" class="border border-black p-1 text-right">Jumlah</td>
          <td class="border border-black p-1 text-right">{{ number_format($total, 0, ',', '.') }}</td>
          <td colspan="3" class="border border-black p-1"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <p class="mb-2 text-sm">Demikian Berita Acara Stock Opname Persediaan Barang Habis Pakai ini dibuat untuk diperlukan sebagaimana mestinya.</p>
  
  <div class="grid grid-cols-2 gap-5 mt-2" style="page-break-inside: avoid;">
    <div class="text-center">
      <p class="mb-1">&nbsp;</p>
      <p class="mb-1 uppercase font-bold text-xs">Pengurus Barang Pengguna</p>
      <div class="h-20"></div>
      <p class="font-bold underline">{{ $opd->pengurus_nama ?? ($data['pihak_kedua']['nama'] ?? '') }}</p>
      <p class="text-xs">NIP. {{ $opd->pengurus_nip ?? ($data['pihak_kedua']['nip'] ?? '-') }}</p>
    </div>
    <div class="text-center">
      <p class="mb-1 uppercase font-bold text-xs">Mengetahui</p>
      <p class="mb-1 uppercase font-bold text-xs">{{ $opd->kepala_jabatan ?? ('Kepala ' . \Illuminate\Support\Str::title($opd->nama_opd ?? 'Dinas Komunikasi dan Informatika')) }}</p>
      <div class="h-20"></div>
      <p class="font-bold underline">{{ $opd->kepala_nama ?? ($data['pihak_pertama']['nama'] ?? '') }}</p>
      <p class="text-xs">NIP. {{ $opd->kepala_nip ?? ($data['pihak_pertama']['nip'] ?? '-') }}</p>
    </div>
  </div>
</div>
@endsection
