@extends('layouts.admin')

@section('header', 'Paket Dokumen')
@section('content')
  <style>
    /* Base Styles Scoping */
    .doc-nota, .doc-pemeriksaan, .doc-penerimaan, .doc-kwitansi {
      --line-height: 1.25;
      color: black;
    }

    .bundle-paper {
      width: 210mm;
      min-height: 297mm;
      margin: 16px auto;
      background: #ffffff;
      padding: 10mm 15mm;
      line-height: var(--line-height);
      font-family: 'Nunito', sans-serif;
      box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
      position: relative;
      box-sizing: border-box;
      overflow: hidden;
    }

    .bundle-sheet {
      page-break-after: always;
      position: relative;
    }

    .bundle-sheet:last-child {
      page-break-after: auto;
    }

    /* Utilitas Teks */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    .underline { text-decoration: underline; }
    .uppercase { text-transform: uppercase; }
    .italic { font-style: italic; }

    /* Gaya Tabel Umum di Preview */
    .bundle-paper table.report-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .bundle-paper table.report-table th, 
    .bundle-paper table.report-table td {
      border: 1px solid black;
      padding: 4px 8px;
      font-size: 12px;
    }

    @media print {
      body { 
        visibility: hidden !important; 
        background: white !important;
      }
      
      #paket-print-wrapper { 
        visibility: visible !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 210mm !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      #paket-print-wrapper * { 
        visibility: visible !important; 
        color: black !important;
        opacity: 1 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      #paket-print-wrapper p { margin: 2px 0 !important; line-height: 1.2 !important; }

      #paket-print-wrapper .bundle-paper {
        width: 210mm !important;
        min-height: 297mm !important;
        padding: 10mm 15mm !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
        background: white !important;
        box-sizing: border-box !important;
      }

      .js-page-break { 
        display: block !important; 
        break-before: page !important; 
        page-break-before: always !important; 
        height: 0 !important; 
      }
      
      .print\:hidden { display: none !important; }

      @page { size: A4; margin: 0 !important; }
      
      tr, .signature-section { break-inside: avoid !important; }
      table { width: 100% !important; border-collapse: collapse !important; border: none !important; }
      
      table.report-table { margin-bottom: 20px !important; }
      table.report-table th, 
      table.report-table td { 
        border: 1px solid black !important; 
        padding: 4px 8px !important;
      }
      
      table:not(.report-table) td { 
        border: none !important; 
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        line-height: 1.2 !important;
      }
    }
  </style>

  <div class="bg-white rounded-lg shadow p-6 mb-6 print:hidden flex items-center justify-end gap-2">
      <a href="{{ route('reports.nota.list') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button type="button" onclick="printPackage()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-print"></i> Cetak Paket Dokumen
      </button>
  </div>

  <div id="bundle-printable-area">
    <div class="bundle-sheet doc-nota">
      <div class="bundle-paper" id="paper-nota">
        @include('reports.partials.docs.nota_pesanan', ['data' => $nota, 'opd' => $opd])
      </div>
    </div>

    <div class="bundle-sheet doc-pemeriksaan">
      <div class="bundle-paper" id="paper-pemeriksaan">
        @if($pemeriksaan)
          @include('reports.partials.docs.pemeriksaan', ['data' => $pemeriksaan, 'opd' => $opd])
        @else
          <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PEMERIKSAAN TIDAK DITEMUKAN</div>
          <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
        @endif
      </div>
    </div>

    <div class="bundle-sheet doc-penerimaan">
      <div class="bundle-paper" id="paper-penerimaan">
        @if($penerimaan)
          @include('reports.partials.docs.penerimaan', ['data' => $penerimaan, 'opd' => $opd])
        @else
          <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PENERIMAAN TIDAK DITEMUKAN</div>
          <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
        @endif
      </div>
    </div>

    <div class="bundle-sheet doc-kwitansi">
      <div class="bundle-paper" id="paper-kwitansi">
        @if($kwitansi)
          @include('reports.partials.docs.kwitansi', ['data' => $kwitansi, 'opd' => $opd])
        @else
          <div class="text-center font-bold" style="margin-top: 120px;">KWITANSI TIDAK DITEMUKAN</div>
          <div class="text-center" style="margin-top: 12px;">Nomor Penerimaan: {{ $penerimaan['nomor'] ?? '-' }}</div>
        @endif
      </div>
    </div>
  </div>

  <script>
    function printPackage() {
      var area = document.getElementById('bundle-printable-area');
      if (!area) return;

      var wrapper = document.createElement('div');
      wrapper.id = 'paket-print-wrapper';
      
      var placeholder = document.createElement('span');
      placeholder.id = 'paket-print-placeholder';
      area.parentNode.insertBefore(placeholder, area);
      wrapper.appendChild(area);
      document.body.appendChild(wrapper);
      document.body.classList.add('is-printing');

      setTimeout(function() {
        window.print();
        var ph = document.getElementById('paket-print-placeholder');
        if (ph) {
          ph.parentNode.insertBefore(area, ph);
          ph.remove();
        }
        if (wrapper) wrapper.remove();
        document.body.classList.remove('is-printing');
      }, 500);
    }

    document.fonts.ready.then(function () {
      var papers = document.querySelectorAll('.bundle-paper');
      
      papers.forEach(function(paper) {
        var pageH = paper.offsetWidth * (297 / 210);
        var pushedEls = [];

        function topFromPaper(el) {
          var t = 0, n = el;
          while (n && n !== paper) { t += n.offsetTop; n = n.offsetParent; }
          return t;
        }

        function fixBreaks() {
          var moved = false;
          var els = paper.querySelectorAll('.signature-section');
          for (var pg = 1; pg <= 10; pg++) {
            var bnd = pageH * pg;
            for (var j = 0; j < els.length; j++) {
              var el = els[j];
              var top = topFromPaper(el);
              var bot = top + el.offsetHeight;
              if (bot > bnd && top < bnd) {
                var push = (bnd + 20) - top;
                el.style.marginTop = (parseFloat(el.style.marginTop || 0) + push) + 'px';
                if (pushedEls.indexOf(el) === -1) pushedEls.push(el);
                moved = true;
              }
            }
          }
          return moved;
        }

        for (var pass = 0; pass < 3; pass++) { if (!fixBreaks()) break; }

        pushedEls.forEach(function(el) {
          var br = document.createElement('div');
          br.className = 'js-page-break';
          br.style.cssText = 'display:none;';
          el.parentNode.insertBefore(br, el);
        });
      });
    });
  </script>
@endsection
