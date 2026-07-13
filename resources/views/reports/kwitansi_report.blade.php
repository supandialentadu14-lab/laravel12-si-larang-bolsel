@extends('layouts.report_print')
@section('default_orientation', 'portrait')
@section('report_class', 'portrait')


@section('title', 'Kwitansi')
@section('back_url', route('reports.kwitansi.list'))

@section('extra_buttons')
    @if(session('kwitansi_current_id'))
        <a href="{{ route('reports.paket.show', session('kwitansi_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    @endif
@endsection

@section('styles')
<style>
    .doc-kwitansi p { margin: 5px 0; font-size: 14px; color: black; }
    .doc-kwitansi h2 { margin: 5px 0; font-size: 18px; font-weight: bold; color: black; }
    
    @media print {
        @page { size: portrait; margin: 15mm; }
        .doc-kwitansi p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .signature-block { break-inside: avoid !important; }
    }
</style>
@endsection

@section('report_content')
<div class="doc-kwitansi">
    @include('reports.partials.docs.kwitansi', ['data' => $data, 'opd' => $opd])
</div>
@endsection
