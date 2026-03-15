@extends('layouts.admin')

@section('header', 'Kwitansi')
@section('subheader', 'Ringkasan pembayaran berdasarkan BAP Penerimaan')

@section('actions')
  <div class="flex items-center gap-3 w-full sm:w-auto">
    <a href="{{ route('reports.kwitansi.list') }}" class="no-print btn btn-outline font-bold flex-1 sm:flex-none justify-center py-4 sm:py-2 rounded-2xl sm:rounded-lg shadow-sm active:scale-95 transition-all">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <button onclick="window.print()" class="no-print btn btn-neutral font-bold flex-1 sm:flex-none justify-center py-4 sm:py-2 rounded-2xl sm:rounded-lg shadow-sm active:scale-95 transition-all">
      <i class="fas fa-print"></i> Cetak
    </button>
    @if (!empty($data['penerimaan_nomor']))
      <a href="{{ route('reports.kwitansi.print_all', ['penerimaan_nomor' => $data['penerimaan_nomor']]) }}" class="no-print btn btn-neutral font-bold flex-1 sm:flex-none justify-center py-4 sm:py-2 rounded-2xl sm:rounded-lg shadow-sm active:scale-95 transition-all">
        <i class="fas fa-file-pdf"></i> Full
      </a>
    @endif
  </div>
  <form method="POST" action="{{ route('reports.kwitansi.save') }}" class="no-print inline-block ml-2 hidden sm:block">
    @csrf
    <input type="hidden" name="id" value="{{ session('kwitansi_current_id') ?? ($saved_id ?? '') }}">
    <!-- <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button> -->
  </form>
@endsection

@section('content')
  <style>
    .preview-paper { 
      width: 210mm; 
      min-height: 330mm; 
      margin: 0 auto; 
      background: #fff; 
      padding: 5mm 15mm;
      line-height: 1.4;
      font-family: 'Times New Roman', serif;
    }
    @media print {
      body { 
        margin: 0 !important; 
        padding: 0 !important;
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      body * { visibility: hidden; }
      #print-area, #print-area * { visibility: visible; }
      
      @page { 
        size: 210mm 330mm; 
        margin: 5mm 15mm; 
      }
      
      #print-area {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
      }
      
      .preview-paper { 
        width: 100% !important; 
        min-height: auto !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        border: none !important;
        background: transparent !important;
        line-height: 1.4;
      }

      .signature-block {
        break-inside: avoid !important;
      }

      table {
        border-collapse: collapse !important;
      }
    }
    @media screen {
      html, body { background: #f3f4f6; }
      #print-area { width: 210mm; margin: 0 auto; }
      .preview-paper { width: 210mm; min-height: 330mm; margin: 16px auto; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 5mm 15mm; }
    }
  </style>
  <div id="print-area" class="preview-paper bg-white rounded-lg shadow p-6">
    <div class="text-center font-bold text-xl mb-4 uppercase italic">KWITANSI</div>
    <div class="border border-black">
      <table class="w-full text-sm">
        <tr>
          <td class="px-2 py-1 w-1/3 italic">TAHUN ANGGARAN</td>
          <td class="px-2 py-1 w-4 text-center">:</td>
          <td class="px-2 py-1">{{ $data['tahun'] ?? '' }}</td>
        </tr>
        <tr>
          <td class="px-2 py-1 italic">KODE REKENING</td>
          <td class="px-2 py-1 w-4 text-center">:</td>
          <td class="px-2 py-1">{{ $data['rekening'] ?? '' }}</td>
        </tr>
        <tr>
          <td class="px-2 py-1 italic">NO. KWT</td>
          <td class="px-2 py-1 w-4 text-center">:</td>
          <td class="px-2 py-1">{{ $data['nomor_kwt'] ?? '' }}</td>
        </tr>
      </table>
      
      <table class="w-full text-sm border-t border-black mt-1">
        <tr>
          <td class="px-2 py-1 w-1/3 align-top italic">Sudah Terima Dari</td>
          <td class="px-2 py-1 align-top w-4 text-center">:</td>
          <td class="px-2 py-1 align-top italic">Bendahara Pengeluaran {{ $data['opd_nama'] ?? ($opd->nama_opd ?? '') }} Kabupaten Bolaang Mongondow Selatan</td>
        </tr>
        <tr>
          <td class="px-2 py-1 align-top italic">Banyaknya Uang</td>
          <td class="px-2 py-1 align-top w-4 text-center">:</td>
          <td class="px-2 py-1 align-top italic">{{ $data['terbilang'] ?? '' }}</td>
        </tr>
        <tr>
          <td class="px-2 py-1 align-top italic">Untuk Pembayaran</td>
          <td class="px-2 py-1 align-top w-4 text-center">:</td>
          <td class="px-2 py-1 align-top italic">{{ $data['pembayaran_uraian'] ?? '' }}</td>
        </tr>
      </table>

      <div class="border-t border-black flex text-sm font-bold italic">
        <div class="px-2 py-1 border-r border-black w-32">Rp</div>
        <div class="px-2 py-1 flex-1">{{ number_format($data['jumlah'] ?? 0, 0, ',', '.') }}</div>
      </div>

      <div class="p-4 text-sm signature-block">
        <div class="flex justify-end mb-8">
          <div class="text-right">{{ $data['lokasi_tanggal'] ?? '' }}</div>
        </div>
        
        <table class="w-full text-center text-sm mb-8" style="table-layout: fixed;">
          <tr>
            <td class="align-top italic font-bold">PPTK</td>
            <td class="align-top italic font-bold">Bendahara Pengeluaran,</td>
            <td class="align-top italic font-bold">Yang Menerima,<br>Pihak Ketiga</td>
          </tr>
          <tr>
            <td colspan="3" class="h-16"></td>
          </tr>
          <tr>
            <td class="font-bold underline uppercase">{{ $data['pejabat']['pptk'] ?? '' }}</td>
            <td class="font-bold underline uppercase">{{ $data['pejabat']['bendahara'] ?? '' }}</td>
            <td class="font-bold underline uppercase">{{ $data['pejabat']['pihak_ketiga'] ?? '' }}</td>
          </tr>
          <tr>
            <td class="text-xs">NIP. {{ $data['pptk_nip'] ?? '-' }}</td>
            <td class="text-xs">NIP. {{ $data['bendahara_nip'] ?? '-' }}</td>
            <td class="text-xs">&nbsp;</td>
          </tr>
        </table>

        <div class="text-center">
          <div class="italic font-bold">Mengetahui,</div>
          <div class="italic font-bold">Pengguna Anggaran</div>
          <div class="h-20"></div>
          <div class="font-bold underline uppercase">{{ $data['pejabat']['pengguna'] ?? '' }}</div>
          <div>NIP. {{ $data['ppk_nip'] ?? '-' }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection
