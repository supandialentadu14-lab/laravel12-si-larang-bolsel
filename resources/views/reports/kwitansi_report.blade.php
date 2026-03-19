@extends('layouts.admin')

@section('header', 'Kwitansi')

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth >= 1024) {
          window.dispatchEvent(new CustomEvent('close-sidebar'));
        }
    });
</script>
@endsection

@section('content')
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* HYPER-FIDELITY PREVIEW (ONLY FOR THIS REPORT) */
    .kwitansi-hifi-preview {
      width: 100%;
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
      overflow-y: auto;
      background-color: #0f172a;
      border-radius: 1rem;
    }

    .bundle-paper-container {
      position: relative;
      width: 210mm;
      margin: 0 auto;
    }

    .bundle-paper {
      width: 210mm;
      min-height: 297mm;
      background-color: white;
      padding: 15mm 20mm;
      box-shadow: 0 10px 50px rgba(0, 0, 0, 0.4);
      position: relative;
      line-height: var(--line-height, 1.4);
      color: black !important;
      font-family: 'Nunito', sans-serif !important;
      box-sizing: border-box;
    }

    /* Page Break Overlay to simulate separate papers in preview */
    .paper-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      pointer-events: none;
      background-image: repeating-linear-gradient(
        to bottom,
        transparent 0,
        transparent 296.8mm,
        #0f172a 296.8mm,
        #0f172a 297.2mm
      );
      z-index: 10;
    }

    @media print {
      body * { visibility: hidden !important; }
      #printable-report, #printable-report * { visibility: visible !important; }
      #printable-report { position: absolute !important; left: 0 !important; top: 0 !important; width: 100% !important; }
      .bundle-paper { box-shadow: none !important; width: 100% !important; min-height: auto !important; margin: 0 !important; padding: 0 !important; }
      .kwitansi-hifi-preview { background: transparent !important; padding: 0 !important; }
      .paper-overlay { display: none; }
      @page { size: auto; margin: 15mm 20mm; }
      tr, .signature-block { break-inside: avoid; }
    }

    .nav-buttons {
      width: 210mm;
      margin-bottom: 24px;
      display: flex;
      gap: 12px;
    }
  </style>

  <div class="kwitansi-hifi-preview">
    <div class="nav-buttons print:hidden">
      <a href="{{ route('reports.kwitansi.list') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button type="button" onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-print"></i> Cetak Dokumen
      </button>
      @if(session('kwitansi_current_id'))
        <a href="{{ route('reports.paket.show', session('kwitansi_current_id')) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
          <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
      @endif
    </div>

    <div id="printable-report" class="doc-kwitansi">
      <div class="bundle-paper-container">
        <div class="bundle-paper">
          @include('reports.partials.docs.kwitansi', ['data' => $data, 'opd' => $opd])
        </div>
        <div class="paper-overlay print:hidden"></div>
      </div>
    </div>
  </div>
@endsection
