@extends('layouts.report_print')
@section('default_orientation', 'portrait')
@section('report_class', 'portrait')


@section('title', 'Nota Pesanan')
@section('back_url', route('reports.nota.list'))

@section('extra_buttons')
    @if(session('nota_current_id'))
        <a href="{{ route('reports.paket.show', session('nota_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    @endif
@endsection

@section('extra_styles')
<style>
    /* Screen preview: match print margins */
    #nota-paper {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .report-paper {
        padding-top: 8mm !important;
        padding-bottom: 8mm !important;
    }

    @media print {
        @page { size: 215mm 330mm portrait; margin: 8mm 15mm; }
        .doc-nota p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .doc-nota table td, .doc-nota table th { padding: 3px 6px !important; }
        .signature-section {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            break-before: avoid !important;
            page-break-before: avoid !important;
        }
        .signature-block { break-inside: avoid !important; page-break-inside: avoid !important; }
        #paper-scale { transform: none !important; }
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
    // Signatures are handled purely via CSS break-inside: avoid
    // No JS margin manipulation needed — it caused signatures to be pushed off-screen
</script>
@endsection


