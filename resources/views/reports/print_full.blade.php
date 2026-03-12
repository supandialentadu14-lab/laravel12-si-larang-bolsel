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
            #print-area { position: static !important; width: auto !important; overflow: visible !important; }
            @page { size: auto; margin: 10mm; }
            .doc { 
                display: block !important; 
                page-break-after: always !important; 
                break-after: page !important; 
                background-color: #ffffff !important; 
                color: #000000 !important;
            }
            .doc:last-of-type { page-break-after: auto !important; break-after: auto !important; }
            body { background-color: #ffffff !important; }
        }
        @media screen {
            #print-area { width: 210mm; margin: 0 auto; transition: all 0.3s ease; }
            .theme-dark html, .theme-dark body { background-color: #020617 !important; }
            .doc { 
                width: 210mm; 
                min-height: 330mm; 
                margin: 16px auto; 
                background-color: #ffffff !important; 
                color: #1e293b !important;
                box-shadow: 0 10px 25px rgba(0,0,0,.08); 
                padding: 10mm; 
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            .theme-dark .doc {
                background-color: #1e293b !important;
                color: #f1f5f9 !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
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
