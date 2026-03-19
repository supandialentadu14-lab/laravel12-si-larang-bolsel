@extends('layouts.report_print')

@section('title', 'Nota Pesanan')
@section('back_url', route('reports.nota.list'))

@section('extra_buttons')
    @if(session('nota_current_id'))
        <a href="{{ route('reports.paket.show', session('nota_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold text-xs">
            <i class="fas fa-layer-group"></i> Paket
        </a>
    @endif
@endsection

@section('styles')
<style>
    .doc-nota p { margin: 5px 0; font-size: 14px; color: black; }
    .doc-nota h2 { margin: 5px 0; font-size: 18px; font-weight: bold; color: black; }
    .doc-nota table.report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .doc-nota table.report-table th, 
    .doc-nota table.report-table td { border: 1px solid black !important; padding: 4px 10px !important; font-size: 12px; color: black; }
    
    @media print {
        @page { size: portrait; margin: 15mm; }
        .doc-nota p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .signature-section { break-inside: avoid !important; }
    }
</style>
@endsection

@section('report_content')
<div class="doc-nota" id="nota-paper">
    @include('reports.partials.docs.nota_pesanan', ['data' => $data, 'opd' => $opd])
</div>
@endsection

@section('scripts')
<script>
    document.fonts.ready.then(function () {
        var paper = document.getElementById('nota-paper');
        if (!paper) return;
        var pageH = 1122; 
        var els = paper.querySelectorAll('.signature-section');
        els.forEach(function(el) {
            var relativeTop = el.offsetTop;
            var bot = relativeTop + el.offsetHeight;
            var pg = Math.floor(relativeTop / pageH);
            var nextPgBoundary = (pg + 1) * pageH;
            if (bot > nextPgBoundary && relativeTop < nextPgBoundary) {
                el.style.marginTop = (nextPgBoundary - relativeTop + 20) + "px";
            }
        });
    });
</script>
@endsection
