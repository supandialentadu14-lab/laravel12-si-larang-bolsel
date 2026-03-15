@extends('layouts.admin')

@section('header', 'Cetak Berkas Full')
@section('subheader', 'Nota Pesanan • BAP Pemeriksaan • BAP Penerimaan • Kwitansi')

@section('actions')
  <a href="{{ route('reports.kwitansi.list') }}" class="no-print btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
  <button type="button" onclick="window.print()" class="no-print btn btn-neutral ml-2"><i class="fas fa-print"></i> Cetak</button>
@endsection

@section('content')
  <style>
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
        margin: 0; 
      }
      
      #print-area {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
      }
      
      .doc { 
        display: block !important; 
        width: 210mm !important;
        min-height: 330mm !important;
        padding: 5mm 15mm !important;
        margin: 0 !important;
        page-break-after: always !important; 
        break-after: page !important; 
        box-sizing: border-box !important;
        background: white !important;
        font-family: 'Times New Roman', serif !important;
      }
      .doc:last-child { 
        page-break-after: auto !important; 
        break-after: auto !important; 
      }
      
      /* Reset preview paper internal styles if nested */
      .doc .preview-paper {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
      }
    }
    @media screen {
      html, body { background: #f3f4f6; }
      #print-area { width: 210mm; margin: 0 auto; }
      .doc { 
        width: 210mm; 
        min-height: 330mm; 
        margin: 16px auto; 
        background: #fff; 
        box-shadow: 0 10px 25px rgba(0,0,0,.08); 
        padding: 5mm 15mm; 
        box-sizing: border-box;
      }
    }
  </style>
  <div id="print-area">
    <div class="doc">{!! $notaHtml !!}</div>
    <div class="doc">{!! $pemeriksaanHtml !!}</div>
    <div class="doc">{!! $penerimaanHtml !!}</div>
    <div class="doc">{!! $kwitansiHtml !!}</div>
  </div>
@endsection
