@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
  {{-- Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Nota Pesanan</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('reports.nota.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
        <i class="fas fa-arrow-left text-xs"></i>
      </a>
      @if(session('nota_current_id') && auth()->user()->hasPermission('pemeriksaan') && auth()->user()->hasPermission('penerimaan') && auth()->user()->hasPermission('berkas_lainnya'))
        <a href="{{ route('reports.paket.show', session('nota_current_id')) }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 active:scale-90 transition-transform">
          <i class="fas fa-layer-group text-xs"></i>
        </a>
      @endif
      <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
        <i class="fas fa-print text-xs"></i>
      </button>
    </div>
  </div>

  {{-- Document Preview Card --}}
  <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden">
    <div id="paper-container" class="w-full no-scrollbar flex justify-center items-start" style="padding-bottom:8px;">
      <div id="paper-scale" class="flex-shrink-0" style="transform-origin: top center; width: 850px; margin: 0 auto;">
        <style>
          .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
          .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .preview-paper-mobile h2 { margin: 5px 0; }
          .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
          .text-center { text-align: center; }
          .text-right { text-align: right; }
          .font-bold { font-weight: bold; }
          .underline { text-decoration: underline; }
          .uppercase { text-transform: uppercase; }
          .signature-block { break-inside: avoid; page-break-inside: avoid; }
          .signature-block * { break-inside: avoid; page-break-inside: avoid; }
        </style>
        <div id="document-preview" class="preview-paper-mobile">
          @include('reports.partials.docs.nota_pesanan', ['data' => $data, 'opd' => $opd])
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function openPrintPreview() {
    const printArea = document.getElementById('document-preview');
    if (!printArea) return;
    
    const content = printArea.innerHTML;
    
    const win = window.open('', '_blank', 'width=900,height=1200');
    if (!win) {
      alert('Silakan izinkan popup untuk mencetak laporan.');
      return;
    }
    
    win.document.open();
    win.document.write(`
      <!doctype html>
      <html>
      <head>
        <title>Cetak Nota Pesanan</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
          body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
          .preview-paper-mobile { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; color: black; }
          .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .preview-paper-mobile h2 { margin: 5px 0; }
          .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
          .text-center { text-align: center; }
          .text-right { text-align: right; }
          .font-bold { font-weight: bold; }
          .underline { text-decoration: underline; }
          .uppercase { text-transform: uppercase; }
          .signature-block { break-inside: avoid; page-break-inside: avoid; }
          .signature-block * { break-inside: avoid; page-break-inside: avoid; }
          @media print { 
            body { padding: 0; }
            @page { size: 210mm 330mm; margin: 10mm; }
          }
        </style>
      </head>
      <body>
        <div class="preview-paper-mobile">
          ${content}
        </div>
        <script>
          window.onload = function() {
            window.print();
            window.onafterprint = function() { window.close(); };
          };
        <\/script>
      </body>
      </html>
    `);
    win.document.close();
  }
  (function() {
    const paperScale = document.getElementById('paper-scale');
    const doc = document.getElementById('document-preview');
    const container = document.getElementById('paper-container');
    function fit() {
      if (!paperScale || !doc || !container) return;
      const baseW = 850;
      const baseH = doc.scrollHeight || 1200;
      const rect = container.getBoundingClientRect();
      const availW = container.clientWidth;
      const availH = Math.max(320, window.innerHeight - rect.top - 12);
      const scale = Math.min(availW / baseW, availH / baseH);
      const clamped = Math.max(0.22, Math.min(scale, 1));
      paperScale.style.transform = `scale(${clamped})`;
      paperScale.style.marginLeft = 'auto';
      paperScale.style.marginRight = 'auto';
    }
    window.addEventListener('resize', fit);
    document.addEventListener('DOMContentLoaded', fit);
    setTimeout(fit, 0);
  })();
</script>
@endsection
