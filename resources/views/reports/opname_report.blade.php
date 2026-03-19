@extends('layouts.admin')

@section('title', 'Cetak Berita Acara Stock Opname')
@section('header', 'Berita Acara Stock Opname Persediaan Barang Habis Pakai')
@section('subheader', 'Pratinjau & cetak')

@section('actions')
  <div class="flex items-center gap-3 w-full sm:w-auto">
    <a href="{{ route('reports.opname.list') }}" class="no-print btn btn-outline font-bold flex-1 sm:flex-none justify-center py-4 sm:py-2 rounded-2xl sm:rounded-lg shadow-sm active:scale-95 transition-all">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <button type="button" onclick="openPrintPreview()" class="no-print btn btn-neutral font-bold flex-1 sm:flex-none justify-center py-4 sm:py-2 rounded-2xl sm:rounded-lg shadow-sm active:scale-95 transition-all">
      <i class="fas fa-print"></i> Cetak
    </button>
  </div>
  <form method="POST" action="{{ route('reports.opname.save') }}" class="no-print inline-block ml-2 hidden sm:block">
    @csrf
    <input type="hidden" name="id" value="{{ session('opname_current_id') ?? ($saved_id ?? '') }}">
    <!-- <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button> -->
  </form>
@endsection

@section('content')
  <script>
    function openPrintPreview() {
      const printArea = document.getElementById('print-area');
      if (!printArea) return;
      const styles = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'))
        .map((el) => el.outerHTML)
        .join('');
      const win = window.open('', '_blank', 'width=900,height=1200');
      if (!win) return;
      win.document.open();
      win.document.write(`<!doctype html><html><head><title>Print</title>${styles}</head><body>${printArea.outerHTML}</body></html>`);
      win.document.close();
      win.focus();
      win.onload = () => {
        win.print();
        win.onafterprint = () => win.close();
      };
    }
  </script>
    <style>
      .preview-paper {
        width: 210mm;
        min-height: 330mm;
        margin: 0 auto;
        background: #fff;
        padding: 10mm 15mm;
        line-height: 1.4;
        font-family: 'Nunito', sans-serif;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
      }
      @media print { 
        @page { size: 210mm 330mm; margin: 5mm 15mm; } 
        body { margin: 0; background: #fff !important; }
        .preview-paper { 
          width: 100% !important; 
          min-height: auto !important; 
          padding: 0 !important; 
          margin: 0 !important; 
          box-shadow: none !important; 
        }
      }
    </style>
    <div id="print-area" class="preview-paper bg-white text-black shadow-sm mx-auto">
      <div class="mb-4">
        @include('partials.kop', ['opd' => $opd])
      </div>
    
      @if (isset($error))
        <div class="no-print mb-4 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded">
          {{ $error }}
        </div>
      @endif
      <div class="text-center mb-4">
        <h2 class="font-bold text-lg">BERITA ACARA</h2>
        <h2 class="font-bold text-lg underline uppercase">HASIL STOCK OPNAME PERSEDIAAN BARANG HABIS PAKAI</h2>
        <p class="text-sm">NO: {{ $data['nomor'] ?? '' }}</p>
      </div>

      <p class="mb-3 text-sm">
        {{ $data['pembuka'] ?? 'Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l d F Y') . ', bertempat di ' . \Illuminate\Support\Str::title(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-')) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:' }}
      </p>

      <div class="mb-4">
        <div>
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
      </div>

      <div class="overflow-x-auto mb-6">
        <table class="w-full text-xs border border-black print:text-[10px]" style="table-layout: fixed;">
          <colgroup>
            <col style="width: 34px;">
            <col>
            <col style="width: 64px;">
            <col style="width: 60px;">
            <col style="width: 112px;">
            <col style="width: 120px;">
            <col style="width: 20px;">
            <col style="width: 20px;">
            <col style="width: 20px;">
          </colgroup>
          <thead>
            <tr class="text-center font-bold">
              <th class="border border-black px-1 py-1" style="width: 34px;" rowspan="2">No</th>
              <th class="border border-black px-2 py-1" rowspan="2">Nama Jenis Persediaan Barang</th>
              <th class="border border-black px-2 py-1" style="white-space: nowrap; font-size: 11px;" rowspan="2">Kwantitas</th>
              <th class="border border-black px-2 py-1" rowspan="2">Satuan</th>
              <th class="border border-black px-2 py-1" rowspan="2">Harga Satuan (Rp)</th>
              <th class="border border-black px-2 py-1" rowspan="2">Jumlah Harga (Rp)</th>
              <th class="border border-black px-2 py-1" colspan="3">Kondisi Barang</th>
            </tr>
            <tr class="text-center font-bold">
              <th class="border border-black px-0 py-1" style="width: 20px;">B</th>
              <th class="border border-black px-0 py-1" style="width: 20px;">RR</th>
              <th class="border border-black px-0 py-1" style="width: 20px;">RB</th>
            </tr>
          </thead>
          <tbody>
            @php $total = 0; @endphp
            @foreach ($data['items'] as $i => $item)
              @php $total += (int)($item['jumlah'] ?? 0); @endphp
              <tr>
                <td class="border border-black px-1 py-1 text-center" style="width: 34px;">{{ $i + 1 }}</td>
                <td class="border border-black px-2 py-1">{{ $item['nama'] }}</td>
                <td class="border border-black px-2 py-1 text-center">{{ $item['kuantitas'] }}</td>
                <td class="border border-black px-2 py-1 text-center">{{ $item['satuan'] ?? '-' }}</td>
                <td class="border border-black px-2 py-1 text-right">
                  {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                <td class="border border-black px-2 py-1 text-right">
                  {{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                <td class="border border-black px-0 py-1 text-center" style="width: 20px;">
                  {{ isset($item['kondisi']) && $item['kondisi'] === 'B' ? 'V' : '' }}</td>
                <td class="border border-black px-0 py-1 text-center" style="width: 20px;">
                  {{ isset($item['kondisi']) && $item['kondisi'] === 'RR' ? 'V' : '' }}</td>
                <td class="border border-black px-0 py-1 text-center" style="width: 20px;">
                  {{ isset($item['kondisi']) && $item['kondisi'] === 'RB' ? 'V' : '' }}</td>
              </tr>
            @endforeach
            <tr>
              <td colspan="5" class="border border-black px-2 py-1 text-right font-bold">Jumlah</td>
              <td class="border border-black px-2 py-1 text-right font-bold">
                {{ number_format($total, 0, ',', '.') }}</td>
              <td class="border border-black px-0 py-1" style="width: 20px;"></td>
              <td class="border border-black px-0 py-1" style="width: 20px;"></td>
              <td class="border border-black px-0 py-1" style="width: 20px;"></td>
            </tr>
          </tbody>
        </table>
      </div>

      <p class="mb-4 text-sm">Demikian Berita Acara Stock Opname Persediaan Barang Habis Pakai ini dibuat untuk
        diperlukan sebagaimana mestinya.</p>
      
      <div class="grid grid-cols-2 gap-5 mt-2 signature-block">
        <div class="text-center">
          <p class="mb-1">&nbsp;</p>
          <p class="mb-1">Pengurus Barang Pengguna</p>
          <div class="h-24"></div>
          <p class="font-bold underline">{{ $opd->pengurus_nama ?? ($data['pihak_kedua']['nama'] ?? '') }}</p>
          <p class="text-sm">NIP. {{ $opd->pengurus_nip ?? ($data['pihak_kedua']['nip'] ?? '-') }}</p>
        </div>
        <div class="text-center">
          <p class="mb-1">Mengetahui</p>
          <p class="mb-1">{{ $opd->kepala_jabatan ?? ('Kepala ' . \Illuminate\Support\Str::title($opd->nama_opd ?? 'Dinas Komunikasi dan Informatika')) }}</p>
          <div class="h-24"></div>
          <p class="font-bold underline">{{ $opd->kepala_nama ?? ($data['pihak_pertama']['nama'] ?? '') }}</p>
          <p class="text-sm">NIP. {{ $opd->kepala_nip ?? ($data['pihak_pertama']['nip'] ?? '-') }}</p>
        </div>
      </div>
    
  @endsection
