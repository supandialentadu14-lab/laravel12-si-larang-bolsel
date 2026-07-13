<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laporan') - SIMPATI</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Nunito', sans-serif; margin: 0; padding: 0; background-color: #f1f5f9; overflow: hidden; }
        
        /* THE DIGITAL DESK WORKSPACE */
        .preview-workspace {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        /* CENTER AREA: THE PAPERS */
        .document-area {
            flex: 1;
            height: 100%;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 0;
            scroll-behavior: smooth;
        }

        /* THE F4 PAPER STYLES */
        .report-paper {
            background: white !important;
            width: 215mm !important; /* F4 Portrait */
            min-height: 330mm;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            border: 1px solid rgba(0,0,0,0.05);
            margin-bottom: 40px;
            box-sizing: border-box;
            display: block;
            position: relative;
            transform-origin: top center;
            /* REMOVED ZOOM TO PREVENT LAYOUT GLITCHES ON PAGE 2 */
            padding: 15mm !important; /* INNER MARGINS */
        }

        .report-paper.landscape {
            width: 330mm !important;
            min-height: 215mm !important;
        }

        /* TABLE BORDER STYLES - SELECTIVE */
        .report-table, .table-bordered {
            border-collapse: collapse !important;
            width: 100%;
            margin-bottom: 1rem;
        }
        .report-table th, .report-table td,
        .table-bordered th, .table-bordered td {
            border: 1px solid black !important;
            padding: 4px 8px;
        }

        /* HIDE BORDERS FOR LAYOUT TABLES (Headers, Signatures, Info) */
        .info-table, .info-table td, 
        .signature-table, .signature-table td,
        .no-border, .no-border td {
            border: none !important;
        }
        .info-table { width: 100%; margin-bottom: 1rem; }

        /* Page break classes */
        .page-break, .page-break-after {
            page-break-after: always !important;
            break-after: page !important;
        }
        .page-break-before {
            page-break-before: always !important;
            break-before: page !important;
        }

        /* RIGHT SIDEBAR */
        .sidebar {
            width: 320px;
            background: #f8fafc;
            border-l: 1px solid #e2e8f0;
            height: 100%;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            z-index: 100;
        }

        @media print {
            .no-print { display: none !important; }
            .sidebar { display: none !important; }

            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

            @page {
                size: @yield('report_size', '215mm 330mm portrait');
                margin: 0;
            }

            html, body {
                background: white !important;
                overflow: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                width: @yield('report_width', '215mm') !important;
            }

            .preview-workspace {
                display: block !important;
                height: auto !important;
                width: @yield('report_width', '215mm') !important;
            }

            .document-area {
                padding: 0 !important;
                overflow: visible !important;
                display: block !important;
                width: @yield('report_width', '215mm') !important;
            }

            #paper-stack {
                display: block !important;
                width: @yield('report_width', '215mm') !important;
            }

            /* Show ALL pages when printing */
            .report-paper,
            .page-sheet {
                display: block !important;
                width: @yield('report_width', '215mm') !important;
                height: auto !important;
                min-height: 0 !important;
                padding: 15mm !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                box-sizing: border-box !important;
                page-break-after: always;
            }

            .page-sheet:last-child {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .report-paper.landscape,
            .page-sheet.landscape {
                width: 330mm !important;
                height: auto !important;
                min-height: 0 !important;
            }
        }
    </style>
    @stack('styles')
    @yield('extra_styles')
</head>
<body class="@yield('report_class')">
    <div class="preview-workspace">
        {{-- Main Document Area --}}
        <div class="document-area" id="doc-scroller">
            <div id="paper-stack">
                <div class="report-paper @yield('report_class')" id="initial-page">
                    @yield('report_content')
                </div>
            </div>
        </div>

        {{-- Sidebar Controls --}}
        <div class="sidebar no-print">
            <div class="flex flex-col gap-1">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status Pratinjau</span>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-600 uppercase">Dokumen Terverifikasi</span>
                </div>
            </div>

            <div class="h-[1px] bg-slate-200"></div>

            <div class="flex flex-col gap-4">
                <button type="button" onclick="window.print()" 
                        class="w-full h-16 flex items-center justify-center gap-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white transition-all font-black shadow-lg shadow-indigo-600/20 active:scale-95 text-base uppercase">
                    <i class="fas fa-print text-xl"></i> 
                    Cetak Sekarang
                </button>

                {{-- Horizontal Filter Support (Used in Kartu Tahunan) --}}
                <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-sm">
                    @yield('extra_buttons')
                </div>

                {{-- Pagination Switcher --}}
                <div id="pagination-ctrl" class="hidden flex-col gap-3 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-1 text-center">Navigasi Halaman</span>
                    <div class="flex items-center justify-between gap-2">
                        <button type="button" onclick="changePage(-1)" id="prev-btn"
                                class="flex-1 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all border border-slate-200 flex items-center justify-center">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="px-4 py-2 bg-slate-100 rounded-xl text-xs font-black text-slate-700 min-w-[80px] text-center">
                            <span id="current-page">1</span> / <span id="total-pages">1</span>
                        </div>
                        <button type="button" onclick="changePage(1)" id="next-btn"
                                class="flex-1 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all border border-slate-200 flex items-center justify-center">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <a href="@yield('back_url', route('dashboard'))" 
                   class="group w-full flex items-center justify-center gap-2.5 py-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 transition-all font-bold text-sm shadow-sm mt-4">
                    <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i> 
                    Tutup Pratinjau
                </a>
            </div>
            
            <div class="mt-auto p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                <p class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider mb-2">Instruksi:</p>
                <p class="text-[10px] text-indigo-500/80 leading-relaxed font-medium">
                    Pastikan pengaturan <b>Margin</b> pada jendela cetak diatur ke <b>"Default"</b> atau <b>"None"</b> untuk hasil terbaik.
                </p>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 0;
        let sheetsCount = 1;

        function initA4Splitter() {
            const stack = document.getElementById('paper-stack');
            const initialPaper = document.getElementById('initial-page');
            if (!stack || !initialPaper) return;

            const isLandscape = initialPaper.classList.contains('landscape');

            // Create a temporary dummy sheet to measure exact pixel height of F4 computed by browser
            const dummySheet = createNewSheet(isLandscape, -1);
            dummySheet.style.display = 'block';
            stack.appendChild(dummySheet);
            const totalH_px = dummySheet.clientHeight || (isLandscape ? 812 : 1247);
            stack.removeChild(dummySheet);

            // ONLY RUN IF CONTENT IS LONGER THAN ONE A4 OR HAS PAGE BREAKS
            const hasPageBreaks = initialPaper.querySelector('.page-break, .page-break-after, .page-break-before') !== null;
            if (initialPaper.scrollHeight > totalH_px + 20 || hasPageBreaks) {
                let elements = Array.from(initialPaper.children);
                
                // If there's a single wrapper element containing the actual content, unpack its children
                let wrapperSelector = null;
                if (elements.length === 1 && elements[0].children.length > 0) {
                    const wrapper = elements[0];
                    wrapperSelector = {
                        id: wrapper.id,
                        className: wrapper.className,
                        tagName: wrapper.tagName,
                        style: wrapper.getAttribute('style')
                    };
                    elements = Array.from(wrapper.children);
                }
                
                // WIPE THE STACK AND START FRESH
                stack.innerHTML = '';
                
                let currentSheet = createNewSheet(isLandscape, 0);
                
                // If there was a wrapper, create a clone of the wrapper inside the sheet to preserve CSS styles
                let contentContainer = currentSheet;
                if (wrapperSelector) {
                    const wrapDiv = document.createElement(wrapperSelector.tagName);
                    if (wrapperSelector.id) wrapDiv.id = wrapperSelector.id;
                    if (wrapperSelector.className) wrapDiv.className = wrapperSelector.className;
                    if (wrapperSelector.style) wrapDiv.setAttribute('style', wrapperSelector.style);
                    currentSheet.appendChild(wrapDiv);
                    contentContainer = wrapDiv;
                }
                
                currentSheet.style.display = 'block'; // Keep it visible during measurements
                stack.appendChild(currentSheet);

                let count = 1;
                let forceNextNewSheet = false;
                elements.forEach(el => {
                    const hasPageBreakBefore = el.classList.contains('page-break-before') || el.style.pageBreakBefore === 'always';

                    if ((forceNextNewSheet || hasPageBreakBefore) && contentContainer.children.length > 0) {
                        const nextSheet = createNewSheet(isLandscape, count++);
                        
                        let nextContainer = nextSheet;
                        if (wrapperSelector) {
                            const wrapDiv = document.createElement(wrapperSelector.tagName);
                            if (wrapperSelector.id) wrapDiv.id = wrapperSelector.id;
                            if (wrapperSelector.className) wrapDiv.className = wrapperSelector.className;
                            if (wrapperSelector.style) wrapDiv.setAttribute('style', wrapperSelector.style);
                            nextSheet.appendChild(wrapDiv);
                            nextContainer = wrapDiv;
                        }
                        
                        stack.appendChild(nextSheet);
                        currentSheet = nextSheet;
                        contentContainer = nextContainer;
                        forceNextNewSheet = false;
                    }

                    contentContainer.appendChild(el);
                    
                    // Measure exact bottom of element relative to sheet top using getBoundingClientRect()
                    const rect = el.getBoundingClientRect();
                    const sheetRect = currentSheet.getBoundingClientRect();
                    const style = window.getComputedStyle(currentSheet);
                    const paddingBottom = parseFloat(style.paddingBottom) || 0;
                    
                    const elementBottom = rect.bottom - sheetRect.top;
                    const maxAllowedBottom = sheetRect.height - paddingBottom;

                    const hasPageBreakAfter = el.classList.contains('page-break') || el.classList.contains('page-break-after') || el.style.pageBreakAfter === 'always';
                    if (hasPageBreakAfter) {
                        forceNextNewSheet = true;
                    }
                    
                    // If overflow, move to NEW sheet
                    if (elementBottom > maxAllowedBottom && contentContainer.children.length > 1) {
                        const nextSheet = createNewSheet(isLandscape, count++);
                        
                        let nextContainer = nextSheet;
                        if (wrapperSelector) {
                            const wrapDiv = document.createElement(wrapperSelector.tagName);
                            if (wrapperSelector.id) wrapDiv.id = wrapperSelector.id;
                            if (wrapperSelector.className) wrapDiv.className = wrapperSelector.className;
                            if (wrapperSelector.style) wrapDiv.setAttribute('style', wrapperSelector.style);
                            nextSheet.appendChild(wrapDiv);
                            nextContainer = wrapDiv;
                        }
                        
                        stack.appendChild(nextSheet);
                        nextContainer.appendChild(el);
                        currentSheet = nextSheet;
                        contentContainer = nextContainer;
                        forceNextNewSheet = false; // Reset since we already moved
                    }
                });

                sheetsCount = count;
                document.getElementById('total-pages').textContent = sheetsCount;
                if (sheetsCount > 1) {
                    document.getElementById('pagination-ctrl').classList.remove('hidden');
                }
                updateVisiblePage();
            }
        }

        function createNewSheet(landscape, index) {
            const sheet = document.createElement('div');
            sheet.className = 'report-paper page-sheet ' + (landscape ? 'landscape' : '');
            sheet.style.height = landscape ? '215mm' : '330mm';
            sheet.id = 'page-' + index;
            // No display: none during split calculation, so dimensions/getBoundingClientRect are computed correctly
            return sheet;
        }

        function changePage(dir) {
            currentPage = Math.max(0, Math.min(currentPage + dir, sheetsCount - 1));
            updateVisiblePage();
        }

        function updateVisiblePage() {
            const sheets = document.querySelectorAll('.page-sheet');
            sheets.forEach((s, i) => {
                s.style.display = (i === currentPage) ? 'block' : 'none';
            });
            document.getElementById('current-page').textContent = currentPage + 1;
            document.getElementById('prev-btn').disabled = (currentPage === 0);
            document.getElementById('next-btn').disabled = (currentPage === sheetsCount - 1);
        }

        window.addEventListener('load', initA4Splitter);
        setTimeout(initA4Splitter, 500);

        window.onbeforeprint = () => {
            document.querySelectorAll('.page-sheet').forEach(s => s.style.display = 'block');
        };
        window.onafterprint = updateVisiblePage;
    </script>
    @yield('scripts')
</body>
</html>
