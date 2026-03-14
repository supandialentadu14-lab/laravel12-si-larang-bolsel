@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Paket 4 Dokumen</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('reports.nota.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
        <i class="fas fa-arrow-left text-xs"></i>
      </a>
      <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
        <i class="fas fa-print text-xs"></i>
      </button>
    </div>
  </div>

  <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden">
    <div id="paper-container" class="w-full no-scrollbar flex justify-center items-start" style="padding-bottom:8px;">
      <div id="paper-scale" class="flex-shrink-0" style="transform-origin: top center; width: 850px; margin: 0 auto;">
        <style>
          .bundle-sheet { margin-bottom: 18px; }
          .bundle-sheet:last-child { margin-bottom: 0; }
          @media print {
            .bundle-sheet { page-break-after: always; }
            .bundle-sheet:last-child { page-break-after: auto; }
          }

          .text-center { text-align: center; }
          .text-right { text-align: right; }
          .font-bold { font-weight: bold; }
          .underline { text-decoration: underline; }
          .uppercase { text-transform: uppercase; }
          .italic { font-style: italic; }
          .border-kwt { border: 1px solid black; }
          .border-t-kwt { border-top: 1px solid black; }
          .border-r-kwt { border-right: 1px solid black; }
          .signature-block { break-inside: avoid; page-break-inside: avoid; }
          .signature-block * { break-inside: avoid; page-break-inside: avoid; }

          .doc-nota .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
          .doc-nota .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .doc-nota .preview-paper-mobile h2 { margin: 5px 0; }
          .doc-nota .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .doc-nota .preview-paper-mobile th, .doc-nota .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }

          .doc-pemeriksaan .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
          .doc-pemeriksaan .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .doc-pemeriksaan .preview-paper-mobile h2 { margin: 5px 0; }
          .doc-pemeriksaan .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .doc-pemeriksaan .preview-paper-mobile th, .doc-pemeriksaan .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }

          .doc-penerimaan .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
          .doc-penerimaan .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .doc-penerimaan .preview-paper-mobile h2 { margin: 5px 0; }
          .doc-penerimaan .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .doc-penerimaan .preview-paper-mobile th, .doc-penerimaan .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }

          .doc-kwitansi .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
          .doc-kwitansi .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
          .doc-kwitansi .preview-paper-mobile h2 { margin: 5px 0; }
          .doc-kwitansi .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          .doc-kwitansi .preview-paper-mobile th, .doc-kwitansi .preview-paper-mobile td { padding: 8px 12px; font-size: 14px; }
        </style>

        <div id="bundle-pages">
          <div class="bundle-sheet doc-nota">
            <div class="preview-paper-mobile">
              @include('reports.partials.docs.nota_pesanan', ['data' => $nota, 'opd' => $opd])
            </div>
          </div>

          <div class="bundle-sheet doc-pemeriksaan">
            <div class="preview-paper-mobile">
              @if($pemeriksaan)
                @include('reports.partials.docs.pemeriksaan', ['data' => $pemeriksaan, 'opd' => $opd])
              @else
                <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PEMERIKSAAN TIDAK DITEMUKAN</div>
                <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
              @endif
            </div>
          </div>

          <div class="bundle-sheet doc-penerimaan">
            <div class="preview-paper-mobile">
              @if($penerimaan)
                @include('reports.partials.docs.penerimaan', ['data' => $penerimaan, 'opd' => $opd])
              @else
                <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PENERIMAAN TIDAK DITEMUKAN</div>
                <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
              @endif
            </div>
          </div>

          <div class="bundle-sheet doc-kwitansi">
            <div class="preview-paper-mobile">
              @if($kwitansi)
                @include('reports.partials.docs.kwitansi', ['data' => $kwitansi, 'opd' => $opd])
              @else
                <div class="text-center font-bold" style="margin-top: 120px;">KWITANSI TIDAK DITEMUKAN</div>
                <div class="text-center" style="margin-top: 12px;">Nomor Penerimaan: {{ $penerimaan['nomor'] ?? '-' }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function openPrintPreview() {
    const area = document.getElementById('bundle-pages');
    if (!area) return;
    const content = area.innerHTML;
    const win = window.open('', '_blank', 'width=900,height=1200');
    if (!win) {
      alert('Silakan izinkan popup untuk mencetak laporan.');
      return;
    }
    win.document.open();
    win.document.write(`<!doctype html>
      <html>
      <head>
        <title>Cetak Paket Dokumen</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
          body { margin: 0; padding: 0; font-family: 'Nunito', sans-serif; background: #fff; color: #000; }
          .bundle-sheet { page-break-after: always; }
          .bundle-sheet:last-child { page-break-after: auto; }
          .signature-block { break-inside: avoid; page-break-inside: avoid; }
          .signature-block * { break-inside: avoid; page-break-inside: avoid; }
          @media print { @page { size: 210mm 330mm; margin: 10mm 15mm; } }
        </style>
      </head>
      <body>
        ${content}
        <script>
          window.onload = function() {
            window.print();
            window.onafterprint = function() { window.close(); };
          };
        <\/script>
      </body>
      </html>`);
    win.document.close();
  }
  (function() {
    const paperScale = document.getElementById('paper-scale');
    const pages = document.getElementById('bundle-pages');
    const container = document.getElementById('paper-container');
    function fit() {
      if (!paperScale || !pages || !container) return;
      const baseW = 850;
      const availW = container.clientWidth;
      const scale = Math.min(availW / baseW, 1);
      const clamped = Math.max(0.22, scale);
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
