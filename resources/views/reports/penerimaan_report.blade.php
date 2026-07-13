@extends('layouts.report_print')
@section('default_orientation', 'portrait')
@section('report_class', 'portrait')


@section('title', 'Berita Acara Penerimaan')
@section('back_url', route('reports.penerimaan.list'))

@section('extra_buttons')
    @if(session('penerimaan_current_id'))
        <a href="{{ route('reports.paket.show', session('penerimaan_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    @endif
@endsection

@section('styles')
<style>
    .doc-penerimaan p { margin: 5px 0; font-size: 14px; color: black; }
    .doc-penerimaan h2 { margin: 5px 0; font-size: 18px; font-weight: bold; color: black; }
    .doc-penerimaan table.report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .doc-penerimaan table.report-table th, 
    .doc-penerimaan table.report-table td { border: 1px solid black !important; padding: 4px 10px !important; font-size: 12px; color: black; }
    
    @media print {
        @page { size: 215mm 330mm portrait; margin: 15mm; }
        .doc-penerimaan p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .signature-section { break-inside: avoid !important; page-break-inside: avoid !important; }
        .signature-block { break-inside: avoid !important; page-break-inside: avoid !important; }
    }
</style>
@endsection

@section('report_content')
<div class="doc-penerimaan" id="terima-paper">
    @include('reports.partials.docs.penerimaan', ['data' => $data, 'opd' => $opd])
</div>
@endsection

@section('scripts')
<script>
    document.fonts.ready.then(function () {
        var paper = document.getElementById('terima-paper');
        if (!paper) return;
        var pageH = 1247; // F4: 330mm at 96dpi
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
