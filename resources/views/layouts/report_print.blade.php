@extends('layouts.admin')

@push('styles')
<style>
    /* Hide admin UI during print */
    @media print {
        #sidebar-main, 
        header, 
        footer,
        .no-print,
        #page-header,
        #page-actions { 
            display: none !important; 
        }

        /* Fix for multiple pages printing */
        html, body {
            height: auto !important;
            overflow: visible !important;
            background: white !important;
        }

        /* Reset all layout containers for printing */
        .max-w-none,
        .flex-row,
        #desktop-content-wrapper,
        #main-content-wrapper,
        #app-content,
        .flex.flex-col.gap-6 {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
        }

        #desktop-content-wrapper {
            background: white !important;
        }

        .report-paper {
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            max-width: none !important;
            margin: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
            display: block !important;
        }

        /* Removal of duplicate border logic since it's now global */
        
        @page {
            margin: 10mm 15mm;
            size: auto;
        }

        @page {
            margin: 10mm 15mm;
            size: auto;
        }
    /* Shared Document Styling (Screen & Print) */
    .report-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 1rem;
        margin-bottom: 1rem;
        background-color: white !important;
        display: table !important; /* Force table layout */
        table-layout: auto !important; /* Allow browser to adjust widths based on content */
    }

    .report-table th, 
    .report-table td {
        border: 1px solid black !important;
        padding: 4px 6px !important; /* Reduced padding */
        font-size: 13px;
        color: black !important;
        background-color: white !important;
        vertical-align: middle;
    }

    /* Fixed width/nowrap for "No" column to prevent multi-line numbers */
    .report-table th:first-child,
    .report-table td:first-child {
        width: 45px !important;
        min-width: 45px !important;
        text-align: center !important;
        white-space: nowrap !important;
    }

    .report-table thead th {
        background-color: #f1f5f9 !important;
        text-align: center;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap !important; /* Prevent header wrapping */
    }

    /* Embedded Preview Styling */
    #main-content-wrapper {
        padding: 2rem !important;
        background-color: #e2e8f0 !important; /* Contrast background for paper */
    }

    .report-paper {
        background: white !important;
        padding: 20mm !important;
        width: 100%;
        max-width: 900px;
        margin: 0 auto !important;
        color: black !important;
        position: relative;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15) !important;
        min-height: 297mm;
        border-radius: 4px;
    }
    
    /* Final Border Logic: Nuclear reset then whitelist */
    /* Target all except report-table structure to keep main table lines visible */
    html body #print-area,
    html body #print-area *:not(table.report-table, table.report-table *, .kop-divider, .kop-divider::after) {
        border-width: 0 !important;
        border-color: transparent !important;
        border-style: none !important;
    }
    
    html body #print-area {
        color: black !important;
        opacity: 1 !important;
    }

    /* Whitelist: Main Report Tables - Only on structure elements */
    html body #print-area table.report-table,
    html body #print-area table.report-table th,
    html body #print-area table.report-table td {
        border-width: 1px !important;
        border-color: black !important;
        border-style: solid !important;
        border-collapse: collapse !important;
    }

    /* Whitelist: Kop Divider lines */
    html body #print-area .kop-divider {
        border-bottom-width: 3px !important;
        border-bottom-color: black !important;
        border-bottom-style: solid !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    html body #print-area .kop-divider::after {
        border-bottom-width: 1px !important;
        border-bottom-color: black !important;
        border-bottom-style: solid !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    /* Whitelist: Manual overrides */
    html body #print-area .with-print-border {
        border-width: 1px !important;
        border-color: black !important;
        border-style: solid !important;
    }

    /* Reduce spacing for metadata tables (Nomor, Nama, etc.) */
    html body #print-area table:not(.report-table) {
        line-height: 1.2 !important;
        margin-top: 4px !important;
        margin-bottom: 4px !important;
    }
    
    html body #print-area table:not(.report-table) td {
        padding-top: 1px !important;
        padding-bottom: 1px !important;
    }

    /* Fix for smushed columns: Ensure div flex containers inside cells are handled */
    .report-table td div {
        min-width: 80px;
    }

    /* Hide admin containers shadows/bg in print */
    @media print {
        #main-content-wrapper {
            padding: 0 !important;
            background: white !important;
        }

        .report-paper {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            min-height: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        .report-table {
            display: table !important;
            table-layout: auto !important;
        }
        
        .report-table tr { display: table-row !important; }
        .report-table td, .report-table th { display: table-cell !important; }
    }

    /* Landscape Adjustment */
    .landscape .report-paper {
        max-width: 1300px;
    }

    @media (max-width: 768px) {
        .report-paper {
            padding: 1rem;
            margin: 0;
            border-radius: 0;
        }
    }
</style>
@yield('extra_styles')
@endpush

@section('content')
<div class="flex flex-col gap-6 -mt-6">
    {{-- Top Controls Bar --}}
    <div class="no-print flex items-center justify-between gap-4 p-4 bg-white/80 backdrop-blur-md shadow-sm rounded-2xl sticky top-0 z-30 transition-colors">
        <div class="flex gap-2">
            <a href="@yield('back_url', route('dashboard'))" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 transition font-bold text-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @yield('extra_buttons')
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-black text-white hover:bg-slate-900 transition font-bold shadow-lg shadow-slate-200 text-sm">
                <i class="fas fa-print"></i> Mode Cetak (Instan)
            </button>
        </div>
    </div>

    {{-- The Report Paper --}}
    <div class="report-paper @yield('report_class')" id="print-area">
        @yield('report_content')
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('load', function() {
        // Auto print logic if needed, but the user requested a side-by-side view, 
        // they might prefer clicking the button manually or keeping the auto-print.
        // I'll keep it but slightly delayed.
        setTimeout(function() {
            // window.print(); // Uncomment if auto-print is still desired
        }, 800);
    });
</script>
@yield('extra_scripts')
@endsection
