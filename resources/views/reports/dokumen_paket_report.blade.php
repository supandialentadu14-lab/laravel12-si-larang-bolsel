@extends('layouts.report_print')

@section('title', 'Paket 4 Dokumen')
@section('back_url', route('reports.nota.list'))

@section('styles')
<style>
    /* Styling khusus paket dokumen */
    .bundle-sheet { 
        break-after: page; 
        page-break-after: always; 
        margin-bottom: 30px;
        position: relative;
    }
    .bundle-sheet:last-child { 
        break-after: auto; 
        page-break-after: auto; 
        margin-bottom: 0;
    }

    /* Override layout paper width to match bundle sheets */
    .report-paper { 
        max-width: none !important; 
        width: 100% !important; 
        background: transparent !important; 
        box-shadow: none !important; 
        padding: 0 !important;
    }

    .bundle-content {
        background: white;
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto 30px auto;
        padding: 10mm 15mm;
        box-sizing: border-box;
    }

    @media print {
        .bundle-content { 
            margin: 0 !important; 
            padding: 10mm 15mm !important; 
            box-shadow: none !important;
            width: 100% !important;
        }
        @page { size: portrait; margin: 0; }
        .no-print { display: none !important; }
    }
</style>
@endsection

@section('report_content')
<div id="bundle-container">
    {{-- 1. Nota Pesanan --}}
    <div class="bundle-sheet doc-nota">
        <div class="bundle-content">
            @include('reports.partials.docs.nota_pesanan', ['data' => $nota, 'opd' => $opd, 'is_first' => true, 'is_last' => true])
        </div>
    </div>

    {{-- 2. BA Pemeriksaan --}}
    <div class="bundle-sheet doc-pemeriksaan">
        <div class="bundle-content">
            @if($pemeriksaan)
                @include('reports.partials.docs.pemeriksaan', ['data' => $pemeriksaan, 'opd' => $opd, 'is_first' => true, 'is_last' => true])
            @else
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-file-invoice text-4xl mb-4"></i>
                    <p class="font-bold">BAP Pemeriksaan Belum Dibuat</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 3. BA Penerimaan --}}
    <div class="bundle-sheet doc-penerimaan">
        <div class="bundle-content">
            @if($penerimaan)
                @include('reports.partials.docs.penerimaan', ['data' => $penerimaan, 'opd' => $opd, 'is_first' => true, 'is_last' => true])
            @else
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-file-import text-4xl mb-4"></i>
                    <p class="font-bold">BAP Penerimaan Belum Dibuat</p>
                </div>
            @endif
        </div>
    </div>

    {{-- 4. Kwitansi --}}
    <div class="bundle-sheet doc-kwitansi">
        <div class="bundle-content">
            @if($kwitansi)
                @include('reports.partials.docs.kwitansi', ['data' => $kwitansi, 'opd' => $opd, 'is_first' => true, 'is_last' => true])
            @else
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-receipt text-4xl mb-4"></i>
                    <p class="font-bold">Kwitansi Belum Dibuat</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Logic for signature breaking if needed in multiple sheets
    document.fonts.ready.then(function () {
        var bundle = document.getElementById('bundle-container');
        if (!bundle) return;
        var pageH = 1122; 
        var sigs = bundle.querySelectorAll('.signature-section');
        sigs.forEach(function(el) {
            // Logic is a bit complex for multi-page bundle, 
            // but the layouts usually handle it via break-inside: avoid
        });
    });
</script>
@endsection
