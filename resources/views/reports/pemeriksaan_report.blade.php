@extends('layouts.report_print')
@section('default_orientation', 'portrait')
@section('report_class', 'portrait')


@section('title', 'Berita Acara Pemeriksaan')
@section('back_url', route('reports.pemeriksaan.list'))

@section('extra_buttons')
    @if(session('bap_current_id'))
        <a href="{{ route('reports.paket.show', session('bap_current_id')) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    @endif
@endsection

@section('extra_styles')
<style>
    .doc-pemeriksaan p { margin: 3px 0; font-size: 13px; color: black; line-height: 1.3; }
    .doc-pemeriksaan h2 { margin: 4px 0; font-size: 16px; font-weight: bold; color: black; }
    .doc-pemeriksaan table.report-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .doc-pemeriksaan table.report-table th,
    .doc-pemeriksaan table.report-table td { border: 1px solid black !important; padding: 3px 8px !important; font-size: 11px; color: black; }
    .doc-pemeriksaan .signature-section { page-break-inside: avoid !important; break-inside: avoid !important; }
    .doc-pemeriksaan .signature-block { page-break-inside: avoid !important; break-inside: avoid !important; }

    @media print {
        @page { size: 215mm 330mm portrait; margin: 15mm; }
        .doc-pemeriksaan p { margin: 1px 0 !important; line-height: 1.2 !important; }
        .signature-section { break-inside: avoid !important; page-break-inside: avoid !important; }
        .signature-block { break-inside: avoid !important; page-break-inside: avoid !important; }
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
    // Signature section is kept on same page via CSS break-inside: avoid
    // No JS pushing needed
</script>
@endsection
