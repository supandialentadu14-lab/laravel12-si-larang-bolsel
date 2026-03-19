@extends('layouts.report_print')

@section('title', 'Berita Acara Pemeriksaan')
@section('back_url', route('reports.pemeriksaan.list'))

@section('extra_buttons')
    @if(session('bap_current_id'))
        <a href="{{ route('reports.paket.show', session('bap_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    @endif
@endsection

@section('styles')
<style>
    .doc-pemeriksaan p { margin: 5px 0; font-size: 14px; color: black; }
    .doc-pemeriksaan h2 { margin: 5px 0; font-size: 18px; font-weight: bold; color: black; }
    .doc-pemeriksaan table.report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .doc-pemeriksaan table.report-table th, 
    .doc-pemeriksaan table.report-table td { border: 1px solid black !important; padding: 4px 10px !important; font-size: 12px; color: black; }
    
    @media print {
        @page { size: portrait; margin: 15mm; }
        .doc-pemeriksaan p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .signature-section { break-inside: avoid !important; }
    }
</style>
@endsection

@section('report_content')
<div class="doc-pemeriksaan" id="periksa-paper">
    @include('reports.partials.docs.pemeriksaan', ['data' => $data, 'opd' => $opd])
</div>
@endsection

@section('scripts')
<script>
    document.fonts.ready.then(function () {
        var paper = document.getElementById('periksa-paper');
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
