@extends('layouts.report_print')
@section('default_orientation', 'portrait')
@section('report_class', 'portrait')


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
      <p class="text-sm">NO: {{ $data['nomor'] }}</p>
    </div>

    <p class="mb-2 text-sm">
        {{ $data['pembuka'] ?? ('Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('l d F Y') . ', bertempat di ' . \Illuminate\Support\Str::title(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-')) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:') }}
    </p>

    <div class="mb-2">
      <table class="w-full text-sm">
        <tr>
          <td class="w-28 align-top">N a m a</td>
          <td class="w-4 align-top">:</td>
          <td class="align-top font-bold">{{ $data['pihak_pertama']['nama'] }}</td>
        </tr>
        <tr>
          <td class="align-top">N I P</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_pertama']['nip'] ?? '-' }}</td>
        </tr>
        <tr>
          <td class="align-top">Jabatan</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_pertama']['jabatan'] }}</td>
        </tr>
      </table>
      <p class="mt-1 text-sm">Selanjutnya disebut <span class="font-bold">PIHAK PERTAMA</span></p>
    </div>

    <div class="mb-2">
      <table class="w-full text-sm">
        <tr>
          <td class="w-28 align-top">N a m a</td>
          <td class="w-4 align-top">:</td>
          <td class="align-top font-bold">{{ $data['pihak_kedua']['nama'] }}</td>
        </tr>
        <tr>
          <td class="align-top">N I P</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_kedua']['nip'] ?? '-' }}</td>
        </tr>
        <tr>
          <td class="align-top">Jabatan</td>
          <td class="align-top">:</td>
          <td class="align-top">{{ $data['pihak_kedua']['jabatan'] }}</td>
        </tr>
      </table>
      <p class="mt-1 text-sm">Selanjutnya disebut <span class="font-bold">PIHAK KEDUA</span></p>
    </div>

    <p class="mb-2 text-sm text-justify">
      Bahwa kedua belah pihak sepakat mengadakan perjanjian serah terima barang inventaris kantor/kendaraan milik Pemerintah Kabupaten Bolaang Mongondow Selatan : 
    </p>

    <div class="mb-3">
      <table class="w-full text-[10px] border-collapse border border-black">
        <thead>
          <tr class="text-center font-bold bg-gray-50">
            <th class="border border-black p-1" style="width: 30px;">No</th>
            <th class="border border-black p-1">Nama Barang</th>
            <th class="border border-black p-1">Merk</th>
            <th class="border border-black p-1">Type</th>
            <th class="border border-black p-1" style="width: 80px;">No. Polisi</th>
            <th class="border border-black p-1" style="width: 70px;">Tahun</th>
            <th class="border border-black p-1" style="width: 70px;">Kondisi</th>
            <th class="border border-black p-1" style="width: 60px;">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data['items'] as $i => $item)
            <tr>
              <td class="border border-black p-1 text-center">{{ $i + 1 }}</td>
              <td class="border border-black p-1">{{ $item['nama'] }}</td>
              <td class="border border-black p-1 text-center">{{ $item['merk'] ?? '-' }}</td>
              <td class="border border-black p-1 text-center">{{ $item['tipe'] ?? '-' }}</td>
              <td class="border border-black p-1 text-center">{{ $item['identitas'] ?? '-' }}</td>
              <td class="border border-black p-1 text-center">{{ $item['tahun'] ?? '-' }}</td>
              <td class="border border-black p-1 text-center">{{ $item['kondisi'] ?? '-' }}</td>
              <td class="border border-black p-1 text-center font-bold">{{ $item['jumlah'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <p class="mb-1 text-sm font-bold">Ketentuan:</p>
    @php
      $rulesLines = preg_split("/\r\n|\n|\r/", $data['ketentuan'] ?? '');
      $rulesLines = array_values(array_filter($rulesLines, fn($l) => trim($l) !== ''));
      $defaultRules = [
        'PIHAK PERTAMA meminjamkan Barang Milik Daerah tersebut di atas kepada PIHAK KEDUA untuk mendukung kegiatan tugas pada ' . \Illuminate\Support\Str::title($opd->nama_opd ?? 'Instansi Terkait') . '.',
        'PIHAK KEDUA bertanggung jawab dalam hal penggunaan, pemeliharaan dan pengamanan barang tersebut.',
        'PIHAK KEDUA dilarang memindahtangankan barang tersebut kepada pihak lain tanpa seizin PIHAK PERTAMA.',
        'PIHAK KEDUA sanggup mengganti rugi apabila barang hilang.',
        'PIHAK KEDUA wajib mengembalikan Barang Milik Daerah kepada PIHAK PERTAMA apabila pensiun/mutasi.',
        'Berita Acara ini berlaku hingga 31 Desember 2026.',
      ];
      $list = count($rulesLines) ? $rulesLines : $defaultRules;
    @endphp
    <table class="text-sm mb-3">
      @foreach ($list as $i => $line)
        @php
            $content = trim($line);
            if (preg_match('/^\s*[a-zA-Z]\.\s*(.*)$/', $line, $m)) { $content = $m[1]; }
            $letter = chr(97 + $i);
        @endphp
        <tr>
          <td valign="top" style="width: 20px;">{{ $letter }}.</td>
          <td class="text-justify">{!! $content !!}</td>
        </tr>
      @endforeach
    </table>
    
    <p class="mb-1 text-sm">Demikian Berita Acara ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    
    <div class="text-right text-sm mb-1">
      {{ ucwords(strtolower($data['tempat'] ?? 'Bolaang Mongondow Selatan')) }}, {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}
    </div>

    <div class="grid grid-cols-2 gap-6 mt-2" style="page-break-inside: avoid;">
      <div class="text-center">
        <p class="mb-1 font-bold text-xs">Pihak Pertama</p>
        <div class="h-20"></div>
        <p class="font-bold underline">{{ $data['pihak_kedua']['nama'] }}</p>
        <p class="text-xs">NIP. {{ $data['pihak_kedua']['nip'] ?? '-' }}</p>
      </div>
      <div class="text-center">
        <p class="mb-1 font-bold text-xs">Pihak Kedua</p>
        <div class="h-20"></div>
        <p class="font-bold underline">{{ $data['pihak_pertama']['nama'] }}</p>
        <p class="text-xs">NIP. {{ $data['pihak_pertama']['nip'] ?? '-' }}</p>
      </div>
    </div>
</div>
@endsection
