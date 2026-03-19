@extends('layouts.admin')

@section('header', 'Berita Acara Pemeriksaan')

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
    /* HYPER-FIDELITY PREVIEW */
    .pemeriksaan-hifi-preview {
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
      padding: 10mm 15mm;
      box-shadow: 0 10px 50px rgba(0, 0, 0, 0.4);
      position: relative;
      line-height: var(--line-height, 1.4);
      color: black !important;
      font-family: 'Nunito', sans-serif !important;
      box-sizing: border-box;
    }

    .bundle-paper * {
      font-family: 'Nunito', sans-serif !important;
      color: black !important;
    }

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

    .doc-pemeriksaan .bundle-paper p { margin: 5px 0; font-size: 14px; }
    .doc-pemeriksaan .bundle-paper h2 { margin: 5px 0; font-size: 18px; font-weight: bold; }
    .doc-pemeriksaan .bundle-paper table.report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .doc-pemeriksaan .bundle-paper table.report-table th, 
    .doc-pemeriksaan .bundle-paper table.report-table td { border: 1px solid black !important; padding: 2px 10px !important; font-size: 12px; }
    .doc-pemeriksaan .bundle-paper table.report-table th { background-color: #f8fafc !important; }

    @media print {
      /* Teknik Visibility: Sembunyikan Body, Tampilkan Laporan */
      body { 
        visibility: hidden !important; 
        background: white !important;
      }
      
      /* Wrapper Laporan: Pojok kiri atas */
      #pemeriksaan-print-wrapper { 
        visibility: visible !important;
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 210mm !important;
        margin: 0 !important;
        padding: 0 !important;
      }

      /* Paksa SEMUA teks hitam */
      #pemeriksaan-print-wrapper, 
      #pemeriksaan-print-wrapper * { 
        visibility: visible !important; 
        color: black !important;
        opacity: 1 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }

      /* Rapatkan spasi teks */
      #pemeriksaan-print-wrapper p { margin: 2px 0 !important; line-height: 1.2 !important; }

      /* Kertas: Sesuaikan padding layar */
      #pemeriksaan-print-wrapper .bundle-paper {
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
      
      /* Border hanya untuk tabel laporan utama */
      table.report-table { margin-bottom: 20px !important; }
      table.report-table th, 
      table.report-table td { 
        border: 1px solid black !important; 
        padding: 4px 8px !important;
      }
      
      /* Tabel metadata bersih dari border */
      table:not(.report-table) td { 
        border: none !important; 
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        line-height: 1.2 !important;
      }
    }

    .nav-buttons {
      width: 210mm;
      margin-bottom: 24px;
      display: flex;
      gap: 12px;
    }
  </style>

  <div class="pemeriksaan-hifi-preview">
    <div class="nav-buttons print:hidden">
      <a href="{{ route('reports.pemeriksaan.list') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <button type="button" onclick="printReport()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
        <i class="fas fa-print"></i> Cetak Dokumen
      </button>
      @if(session('bap_current_id'))
        <a href="{{ route('reports.paket.show', session('bap_current_id')) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all active:scale-95">
          <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
      @endif
    </div>

    <div id="printable-report" class="doc-pemeriksaan">
      <div class="bundle-paper-container">
        <div class="bundle-paper" id="periksa-paper">
          @include('reports.partials.docs.pemeriksaan', ['data' => $data, 'opd' => $opd])
        </div>
        {{-- JS-generated page dividers (only as many as needed) --}}
        <div id="periksa-page-dividers"></div>
      </div>
    </div>
    <script>
      document.fonts.ready.then(function () {
        var paper = document.getElementById('periksa-paper');
        if (!paper) return;
        var container = paper.parentElement;
        var pageH = paper.offsetWidth * (297 / 210);
        // Set toleransi ke 0 agar benar-benar mepet batas kertas baru pindah
        var padV = 0; 
        var pushedEls = [];

        function topFromPaper(el) {
          var t = 0, n = el;
          while (n && n !== paper) { t += n.offsetTop; n = n.offsetParent; }
          return t;
        }

        function fixBreaks() {
          var moved = false;
          var els = paper.querySelectorAll('.signature-section');
          for (var pg = 1; pg <= 20; pg++) {
            var bnd = pageH * pg;
            for (var j = 0; j < els.length; j++) {
              var el = els[j];
              var top = topFromPaper(el);
              var bot = top + el.offsetHeight;
              // Hanya pindah jika bagian bawah elemen benar-benar melewati batas bawah halaman
              if (bot > bnd && top < bnd) {
                var push = (bnd + 20) - top; // Beri sedikit jarak dari atas halaman baru
                el.style.marginTop = (parseFloat(el.style.marginTop || 0) + push) + 'px';
                if (pushedEls.indexOf(el) === -1) pushedEls.push(el);
                moved = true;
              }
            }
          }
          return moved;
        }

        for (var pass = 0; pass < 5; pass++) { if (!fixBreaks()) break; }

        pushedEls.forEach(function(el) {
          var br = document.createElement('div');
          br.className = 'js-page-break';
          br.style.cssText = 'display:none;';
          el.parentNode.insertBefore(br, el);
        });

        var totalH = paper.scrollHeight;
        var pages = Math.ceil(totalH / pageH);
        for (var i = 1; i < pages; i++) {
          var d = document.createElement('div');
          d.className = 'print:hidden';
          d.style.cssText = 'position:absolute;top:' + (pageH * i) + 'px;transform:translateY(-50%);left:25mm;width:calc(100% - 50mm);height:10px;background:#0f172a;border-top:2px dashed #475569;border-bottom:2px dashed #475569;z-index:20;display:flex;align-items:center;justify-content:center;border-radius:10px;';
          d.innerHTML = '<span style="background:#1e293b;color:#94a3b8;padding:2px 14px;border-radius:20px;font-size:9px;font-weight:700;border:1px solid #475569;letter-spacing:0.08em;">&#8212; HALAMAN ' + i + ' / ' + (i + 1) + ' &#8212;</span>';
          container.appendChild(d);
        }
      });
    </script>
    <script>
      function printReport() {
        var paper = document.getElementById('periksa-paper');
        if (!paper) return;

        // Buat wrapper sementara
        var wrapper = document.createElement('div');
        wrapper.id = 'pemeriksaan-print-wrapper';
        
        // PENTING: Tambahkan class doc-pemeriksaan agar CSS laporan tetap berfungsi!
        wrapper.className = 'doc-pemeriksaan'; 
        
        var placeholder = document.createElement('span');
        placeholder.id = 'periksa-print-placeholder';
        paper.parentNode.insertBefore(placeholder, paper);
        wrapper.appendChild(paper);
        document.body.appendChild(wrapper);
        document.body.classList.add('is-printing');

        setTimeout(function() {
          window.print();
          
          var ph = document.getElementById('periksa-print-placeholder');
          if (ph) {
            ph.parentNode.insertBefore(paper, ph);
            ph.remove();
          }
          if (wrapper) wrapper.remove();
          document.body.classList.remove('is-printing');
        }, 300);
      }
    </script>
  </div>
@endsection
