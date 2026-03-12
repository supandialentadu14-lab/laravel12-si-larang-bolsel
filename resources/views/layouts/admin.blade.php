<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Setup -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">

    <title>{{ config('app.name', 'Inventory') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Nunito', 'sans-serif'],
                        },
                        colors: {
                            orange: {
                                50: '#FFF1E6',
                                100: '#FFE3CC',
                                200: '#FFD0A3',
                                300: '#FFB875',
                                400: '#FF9E47',
                                500: '#FF7F1A',
                                600: '#E76A09',
                                700: '#C45508',
                                800: '#9A4407',
                                900: '#7A3606',
                            },
                            indigo: {
                                50: '#EEF2FF',
                                100: '#E0E7FF',
                                200: '#C7D2FE',
                                300: '#A5B4FC',
                                400: '#818CF8',
                                500: '#6366F1',
                                600: '#4F46E5',
                                700: '#4338CA',
                                800: '#3730A3',
                                900: '#312E81',
                            },
                            green: {
                                500: '#22c55e',
                                600: '#16a34a',
                            },
                            blue: {
                                500: '#3b82f6',
                                600: '#2563eb',
                            },
                            rose: {
                                500: '#f43f5e',
                                600: '#e11d48',
                            },
                        }
                    },
                },
            }
        </script>
    @endif
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    {{-- CSS laporan global untuk preview F4, KOP, dan tabel --}}
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">

    <style>
        :root {
            --body-bg: #F3F4F6;
            --body-text: #111827;
            --sidebar-bg: #111827;
            --sidebar-text: #E5E7EB;
            --sidebar-muted: #9CA3AF;
            --sidebar-hover: #1F2937;
            --sidebar-active: #374151;
            --accent: #4F46E5;
        }

        .theme-light {
            --body-bg: #F8FAFC;
            --body-text: #1F2937;
            --sidebar-bg: #F1F5F9;
            --sidebar-text: #1F2937;
            --sidebar-muted: #64748B;
            --sidebar-hover: #E2E8F0;
            --sidebar-active: #CBD5E1;
            --accent: #4F46E5;
            --marquee-start: #60A5FA;
            --marquee-end: #A78BFA;
        }

        .theme-dark {
            --body-bg: #0f172a;
            --body-text: #f1f5f9;
            --sidebar-bg: #020617;
            --sidebar-text: #f1f5f9;
            --sidebar-muted: #94a3b8;
            --sidebar-hover: #1e293b;
            --sidebar-active: #334155;
            --accent: #60a5fa;
            --marquee-start: #93c5fd;
            --marquee-end: #c4b5fd;
            --card-bg: #1e293b;
            --card-border: rgba(255, 255, 255, 0.07);
            --input-bg: #0f172a;
            --input-border: #334155;
            --input-text: #f1f5f9;
        }

        .theme-light {
            --card-bg: #ffffff;
            --card-border: #f1f5f9;
            --input-bg: #ffffff;
            --input-border: #e5e7eb;
            --input-text: #1f2937;
        }

        [x-cloak] {
            display: none !important;
        }

        .sidebar-modern {
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            min-height: 100vh;
            will-change: transform, opacity;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: .9rem 1rem;
            color: var(--sidebar-text);
            transition: all .2s;
            border-left: 3px solid transparent;
            border-radius: .75rem;
            cursor: pointer;
        }

        .nav-link:hover {
            color: var(--sidebar-text);
            background-color: var(--sidebar-hover);
        }

        .nav-link.active {
            color: var(--sidebar-text);
            background-color: var(--sidebar-active);
            box-shadow: none;
            font-weight: 700;
            border-left: 3px solid var(--accent);
        }

        .bg-indigo-800 {
            background-color: var(--sidebar-active);
            box-shadow: none;
        }

        #page-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: .2px;
        }

        #page-header p {
            color: #6b7280;
        }

        .card {
            border: 1px solid #f3f4f6;
            border-radius: .75rem;
            background: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .05), 0 4px 6px -4px rgba(0, 0, 0, .05);
        }

        .table-clean thead {
            background: #f3f4f6;
        }

        .table-clean tbody tr:hover {
            background: #f3f4f6;
        }

        /* ── Global: semua baris tabel hover (sama seperti halaman Barang) ── */
        table tbody tr:hover {
            background-color: rgba(238, 242, 255, 0.40) !important; /* indigo-50/40, light mode */
            transition: background-color 0.2s ease;
        }

        /* Dark mode: warna solid sedikit lebih terang dari background card */
        body.theme-dark table tbody tr:hover {
            background-color: #263044 !important; /* slate-700 — jelas terlihat di dark bg */
        }

        /* ── Responsive: Table global scroll ── */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0.75rem;
        }
        table {
            min-width: 600px;
        }
        /* Prevent text wrapping in tables on small screens to enforce horizontal scroll */
        table th, table td {
            white-space: nowrap;
        }

        /* ══════════════════════════════════════
           RESPONSIVE BREAKPOINTS
           ══════════════════════════════════════ */

        /* ── Auto scroll all tables globally ── */
        /* Wrap any direct table parent that doesn't have overflow */
        .rounded-lg.shadow > table,
        .rounded-xl.shadow > table,
        .card > table,
        .p-6 > table,
        .p-4 > table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* MOBILE: 360px - 767px */
        @media (max-width: 767px) {
            /* Touch targets minimum 36px */
            button { min-height: 34px; }

            /* Sidebar fully hidden when closed on mobile */
            .sidebar-modern { transition: width 0.3s ease, transform 0.3s ease; }

            /* Page header stack on mobile */
            #page-header { flex-direction: column !important; align-items: flex-start !important; }
            #page-header h2 { font-size: 1.15rem !important; }
            #page-header > div:last-child { width: 100%; flex-wrap: wrap; gap: 0.5rem; }

            /* Section card reduced padding on mobile */
            .bg-white.rounded-lg.shadow.p-6,
            .bg-white.rounded-xl.p-6 { padding: 0.875rem !important; }

            /* All main card containers: auto-scroll for tables inside */
            main .bg-white,
            main [class*="rounded-xl"],
            main [class*="rounded-lg"] { overflow-x: auto; }

            /* Form grids: single column on mobile */
            .md\:grid-cols-2,
            .md\:grid-cols-3,
            .md\:grid-cols-4 { grid-template-columns: 1fr !important; }
            .sm\:grid-cols-2 { grid-template-columns: 1fr 1fr !important; }
            
            /* Modal sizing on mobile */
            .fixed.inset-0 .inline-block {
                max-width: calc(100vw - 1.5rem) !important;
                width: 100% !important;
                margin: 0.75rem auto !important;
            }

            /* Topbar tight on mobile */
            header { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }

            /* KPI cards on mobile */
            .grid.grid-cols-2.lg\:grid-cols-4 > * { padding: 0.875rem !important; }
            .grid.grid-cols-2.lg\:grid-cols-4 p.text-3xl,
            .grid.grid-cols-2.lg\:grid-cols-4 p.text-2xl { font-size: 1.375rem !important; }

            /* Footer compact */
            footer .px-6 { padding-left: 1rem !important; padding-right: 1rem !important; }

            /* Action buttons top area - wrap nicely */
            #page-header .flex.items-center { flex-wrap: wrap !important; }
        }

        /* TABLET: 768px - 1023px */
        @media (min-width: 768px) and (max-width: 1023px) {
            .sidebar-modern { transition: width 0.3s ease, transform 0.3s ease; }
            
            #page-header { flex-direction: row; align-items: center; }
            main { padding: 1.25rem !important; }

            /* 2-col forms on tablet */
            .md\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }
        }

        /* DESKTOP: 1024px+ */
        @media (min-width: 1024px) {
            .sidebar-modern { position: relative !important; }
            main { padding: 1.5rem; }
        }

        /* LARGE DESKTOP: 1440px+ */
        @media (min-width: 1440px) {
            .marquee-text { font-size: 20px; }
        }

        /* Touch device interactions */
        @media (hover: none) and (pointer: coarse) {
            .group:hover .group-hover\:opacity-100 { opacity: 1; }
            /* Larger tap targets for touch */
            nav a, nav button { padding-top: 0.65rem !important; padding-bottom: 0.65rem !important; }
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        textarea,
        select {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--input-text);
            border-radius: 0.5rem;
            padding: 0.6rem 0.9rem;
            transition: all .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            border-color: #6366f1;
        }

        button {
            transition: transform .05s ease, box-shadow .2s ease;
        }

        /* Fix Safari/Mobile input date width bug */
        input[type="date"] {
            width: 100% !important;
            min-width: 100% !important;
            box-sizing: border-box !important;
            appearance: none;
            -webkit-appearance: none;
        }

        button:hover {
            transform: translateY(-1px);
        }

        /* Flatpickr Customizations */
        .flatpickr-calendar {
            font-family: inherit !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            padding: 8px !important;
        }
        .flatpickr-day.selected {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
        }

        /* GLOBAL CURSOR RULES */
        a,
        button,
        [role="button"],
        input[type="submit"],
        input[type="button"],
        input[type="reset"],
        select {
            cursor: pointer !important;
        }

        /* Cursor for datalist dropdown arrow */
        input[list]::-webkit-calendar-picker-indicator {
            cursor: pointer !important;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        .submenu-stagger a {
            opacity: 0;
            transform: translateY(4px);
            transition: opacity .18s ease, transform .18s ease;
            will-change: opacity, transform;
        }

        .submenu-open a {
            opacity: 1;
            transform: translateY(0);
        }

        .submenu-open a:nth-child(1) {
            transition-delay: 30ms;
        }

        .submenu-open a:nth-child(2) {
            transition-delay: 60ms;
        }

        .submenu-open a:nth-child(3) {
            transition-delay: 90ms;
        }

        .submenu-open a:nth-child(4) {
            transition-delay: 120ms;
        }

        .submenu-open a:nth-child(5) {
            transition-delay: 150ms;
        }

        .submenu-open a:nth-child(6) {
            transition-delay: 180ms;
        }

        .submenu-open a:nth-child(7) {
            transition-delay: 210ms;
        }

        .submenu-open a:nth-child(8) {
            transition-delay: 240ms;
        }

        .submenu-open a:nth-child(9) {
            transition-delay: 270ms;
        }

        .submenu-open a:nth-child(10) {
            transition-delay: 300ms;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem .75rem;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            font-weight: 600;
            line-height: 1.25rem;
            background: #ffffff;
            color: #374151;
            box-shadow: none;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
            white-space: nowrap !important;
            flex-shrink: 0;
        }

        .btn:hover {
            background: #f9fafb;
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .25);
        }

        .btn i {
            font-size: .9rem;
        }

        .btn-primary {
            background: #4F46E5;
            color: #ffffff;
            border-color: transparent;
        }

        .btn-primary:hover {
            background: #4338CA;
            color: #ffffff;
            border-color: transparent;
        }

        .btn-success {
            background: #16a34a;
            color: #ffffff;
            border-color: transparent;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .btn-warning {
            background: #f97316;
            color: #ffffff;
            border-color: transparent;
        }

        .btn-warning:hover {
            background: #ea580c;
        }

        .btn-neutral {
            background: #111827;
            color: #ffffff;
            border-color: transparent;
        }

        .btn-neutral:hover {
            background: #0f172a;
        }

        .btn-outline {
            background: transparent;
            color: #374151;
            border-color: #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        /* MARQUEE */
        .marquee-container {
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
            min-height: 36px;
            padding: 4px 0;
            border-radius: 0.5rem;
            background: linear-gradient(90deg, rgba(99, 102, 241, .12), rgba(124, 58, 237, .12));
            border: 1px solid rgba(99, 102, 241, .15);
            display: flex;
            align-items: center;
        }

        .marquee-text {
            font-size: 18px;
            display: inline-block;
            padding-left: 100%;
            font-weight: 800;
            background: linear-gradient(90deg, var(--marquee-start), var(--marquee-end));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: .4px;
            animation: marqueeMove 18s linear infinite;
            text-shadow: 0 0 1px rgba(255, 255, 255, .2);
            line-height: normal;
        }

        .marquee-container:hover .marquee-text {
            animation-play-state: paused;
        }

        @keyframes marqueeMove {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @media print {

            aside,
            header,
            #page-header,
            .no-print {
                display: none !important;
            }

            marquee,
            .marquee-container,
            .marquee-text,
            [data-marquee] {
                display: none !important;
            }

            * {
                animation: none !important;
                transition: none !important;
            }

            .h-screen {
                height: auto !important;
            }

            .overflow-hidden,
            .overflow-y-auto,
            .overflow-x-hidden {
                overflow: visible !important;
            }

            main {
                padding: 0 !important;
                background: #ffffff !important;
            }

            body {
                background: #ffffff !important;
            }

            * {
                box-shadow: none !important;
            }
        }

        #print-area,
        #print-area * {
            animation: none !important;
            transition: none !important;
        }

        html,
        body {
            background-color: var(--body-bg);
            color: var(--body-text);
        }

        body {
            overscroll-behavior: none;
        }

        .no-marquee .marquee-container {
            display: none !important;
        }

        .no-marquee .marquee-text {
            animation: none !important;
        }

        main {
            content-visibility: auto;
            contain-intrinsic-size: 800px;
            animation: none !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(2px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        main.fade-out {
            animation: none !important;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(2px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
                animation: none !important;
            }
        }

        .no-anim * {
            animation: none !important;
            transition: none !important;
        }

        .no-anim main {
            animation: none !important;
        }

        .no-anim .sidebar-gradient,
        .no-anim .overflow-y-auto {
            scroll-behavior: auto;
        }
        /* ── Global Dark Theme Overrides for Tailwind Classes ── */
        .theme-dark body {
            background-color: var(--body-bg);
            color: var(--body-text);
        }

        .theme-dark .bg-white,
        .theme-dark .bg-slate-50,
        .theme-dark .bg-gray-50 {
            background-color: var(--card-bg) !important;
        }

        .theme-dark .bg-orange-50 {
            background-color: rgba(251, 146, 60, 0.1) !important;
        }

        .theme-dark .text-slate-800,
        .theme-dark .text-gray-800,
        .theme-dark .text-slate-700,
        .theme-dark .text-gray-700 {
            color: var(--body-text) !important;
        }

        .theme-dark .text-slate-600,
        .theme-dark .text-gray-600,
        .theme-dark .text-slate-500,
        .theme-dark .text-gray-500 {
            color: #94a3b8 !important;
        }

        .theme-dark .border-slate-200,
        .theme-dark .border-gray-200,
        .theme-dark .border-slate-100,
        .theme-dark .border-gray-100,
        .theme-dark .border-indigo-100 {
            border-color: var(--card-border) !important;
        }

        .theme-dark .divide-slate-100 > *,
        .theme-dark .divide-gray-100 > *,
        .theme-dark .divide-slate-200 > * {
            border-color: var(--card-border) !important;
        }

        .theme-dark footer {
            border-color: var(--card-border) !important;
        }

        .theme-dark .bg-indigo-50,
        .theme-dark .bg-slate-100,
        .theme-dark .bg-gray-100 {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
    <style>
        html {
            scrollbar-width: thin;
            scrollbar-color: #6366F1 rgba(99, 102, 241, .12);
        }

        .sidebar-gradient,
        .overflow-y-auto {
            scroll-behavior: smooth;
        }

        .sidebar-gradient::-webkit-scrollbar,
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .sidebar-gradient::-webkit-scrollbar-track,
        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, .08);
            border-radius: 999px;
        }

        .sidebar-gradient::-webkit-scrollbar-thumb,
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-image: linear-gradient(180deg, #6366F1 0%, #7C3AED 100%);
            border-radius: 999px;
            border: 2px solid rgba(255, 255, 255, .25);
        }

        .sidebar-gradient::-webkit-scrollbar-thumb:hover,
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-image: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('#print-area')) {
                document.body.classList.add('no-marquee');
                document.body.classList.add('no-anim');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var collapse = {!! json_encode(session('collapse_submenus') ? true : false) !!};
            if (collapse) {
                try {
                    localStorage.setItem('sidebarOpenGroups', JSON.stringify({
                        master: false,
                        pengadaan: false,
                        transaksi: false,
                        berita: false,
                        settings: false
                    }));
                } catch (e) {}
            }
        });
    </script>
</head>

<body class="font-sans antialiased theme-light" 
    x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        isMobile: window.innerWidth < 1024
    }" 
    x-effect="$el.classList.toggle('theme-dark', theme === 'dark'); $el.classList.toggle('theme-light', theme === 'light'); localStorage.setItem('theme', theme);"
    @resize.window="sidebarOpen = window.innerWidth >= 1024; isMobile = window.innerWidth < 1024;">
    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black/50 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar -->
        <aside
            class="sidebar-modern flex-shrink-0 flex flex-col transition-all duration-300 shadow-xl z-30 overflow-x-hidden absolute lg:relative inset-y-0 left-0 h-full"
            :class="[
                sidebarOpen ? 'w-64 translate-x-0' : (isMobile ? 'w-0 -translate-x-full overflow-hidden' : 'w-20 translate-x-0'),
                (theme === 'dark' ? 'theme-dark' : 'theme-light')
            ]">

            <div class="h-16 flex items-center justify-center border-b border-white/20 relative">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group cursor-pointer"
                    style="color: var(--sidebar-text)">
                    <img src="{{ asset('images/silarang-logo.png') }}" alt="Logo SI-LARANG"
                        class="h-8 w-8 rounded-md ring-2 ring-white/40" onerror="this.style.display='none'">
                    <span class="text-xl font-bold tracking-wider" x-show="sidebarOpen" x-cloak>SI-LARANG</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">

                <a href="{{ route('dashboard') }}"
                    @click="(() => {
                        const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                        for (let k in s) s[k] = false;
                        localStorage.setItem('sidebarOpenGroups', JSON.stringify(s));
                        $dispatch('sidebar-group-opened', { key: 'none' });
                    })()"
                    class="flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg hover:bg-indigo-500 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-between' : 'justify-center'">

                    <span class="flex items-center gap-2">
                        <i class="fas fa-tachometer-alt"></i>
                        <span x-show="sidebarOpen" x-cloak>Dashboard</span>
                    </span>
                </a>

                <div class="w-full space-y-2">
                    @if(auth()->user()->hasPermission('master_data'))
                    <div x-data="{ key: 'master', open: false, popover: false }" class="relative"
                        @sidebar-group-opened.window="if ($event.detail.key !== key) { open = false; const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = false; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); }"
                        x-init="(() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                            open = s[key] ?? ({{ request()->routeIs('categories.*') || request()->routeIs('products.*') || request()->routeIs('suppliers.*') || request()->routeIs('import.index') ? 'true' : 'false' }}); })()">
                        <button
                            @click="sidebarOpen ? (open = !open, open && $dispatch('sidebar-group-opened', { key: key }), (() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = open; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); })()) : (popover = !popover)"
                            class="w-full flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg cursor-pointer hover:bg-indigo-500 hover:text-white group relative"
                            style="color: var(--sidebar-text)"
                            :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                            <span class="flex items-center gap-2 relative">
                                <i class="fas fa-boxes"></i>
                                <span x-show="sidebarOpen" x-cloak>Master Data</span>
                                
                                @if (isset($lowStockCount) && $lowStockCount > 0)
                                    <span x-show="!sidebarOpen" x-cloak class="absolute -top-1 -right-1 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                                @endif
                                
                            </span>
                            <svg x-show="sidebarOpen" x-cloak :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="sidebarOpen && open" x-cloak x-collapse.duration.300ms
                            class="mt-2 rounded-lg overflow-hidden submenu-stagger"
                            :class="open ? 'submenu-open' : ''" style="background: var(--sidebar-hover)">
                            <a href="{{ route('categories.index') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('categories.*') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Kategori
                            </a>
                            <a href="{{ route('products.index') }}"
                                class="flex items-center justify-between pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('products.*') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                <span>Barang</span>
                                @if (isset($lowStockCount) && $lowStockCount > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $lowStockCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('suppliers.index') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('suppliers.*') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Penyedia
                            </a>
                        </div>
                        <div x-show="!sidebarOpen && popover" x-cloak @click.away="popover=false"
                            class="absolute left-full ml-2 top-0 z-50 w-56 rounded-xl shadow-xl ring-1 ring-black/10 p-2"
                            :style="{ backgroundColor: (theme === 'dark' ? '#1B2230' : '#ffffff'), color: (
                                    theme === 'dark' ? '#E5E7EB' : '#111827') }">
                            <a href="{{ route('categories.index') }}"
                                class="block px-3 py-2 rounded hover:bg-gray-700/40">Kategori</a>
                            <a href="{{ route('products.index') }}"
                                class="block px-3 py-2 rounded hover:bg-gray-700/40">Barang</a>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('transaksi') || auth()->user()->hasPermission('laporan_belanja'))
                    <div x-data="{ key: 'transaksi', open: false, popover: false }" class="relative"
                        @sidebar-group-opened.window="if ($event.detail.key !== key) { open = false; const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = false; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); }"
                        x-init="(() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                            open = s[key] ?? ({{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.list') || request()->routeIs('reports.nota.list') || request()->routeIs('reports.belanja.modal.preview_all') ? 'true' : 'false' }}); })()">
                        <button
                            @click="sidebarOpen ? (open = !open, open && $dispatch('sidebar-group-opened', { key: key }), (() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = open; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); })()) : (popover = !popover)"
                            class="w-full flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg cursor-pointer hover:bg-indigo-500 hover:text-white"
                            style="color: var(--sidebar-text)"
                            :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-exchange-alt"></i>
                                <span x-show="sidebarOpen" x-cloak>Transaksi</span>
                            </span>
                            <svg x-show="sidebarOpen" x-cloak :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="sidebarOpen && open" x-cloak x-collapse.duration.300ms
                            class="mt-2 rounded-lg overflow-hidden submenu-stagger" :class="open ? 'submenu-open' : ''"
                            style="background: var(--sidebar-hover)">
                            
                            @if(auth()->user()->hasPermission('transaksi'))
                            <a href="{{ route('stock.index') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('stock.index') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Mutasi Masuk/Keluar
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('laporan_belanja'))
                            <a href="{{ route('reports.belanja.modal.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.belanja.modal.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Belanja Modal
                            </a>
                            @endif
                        </div>
                        <div x-show="!sidebarOpen && popover" x-cloak @click.away="popover=false"
                            class="absolute left-full ml-2 top-0 z-50 w-56 rounded-xl shadow-xl ring-1 ring-black/10 p-2"
                            :style="{ backgroundColor: (theme === 'dark' ? '#1B2230' : '#ffffff'), color: (
                                    theme === 'dark' ? '#E5E7EB' : '#111827') }">
                            
                            @if(auth()->user()->hasPermission('transaksi'))
                            <a href="{{ route('stock.index') }}"
                                class="block px-3 py-2 rounded hover:bg-gray-700/40">Mutasi Masuk/Keluar</a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('laporan_belanja'))
                            <a href="{{ route('reports.belanja.modal.list') }}"
                                class="block px-3 py-2 rounded hover:bg-gray-700/40">Daftar Belanja Modal</a>
                            @endif
                            
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('laporan_persediaan') || auth()->user()->hasPermission('stock_opname') || auth()->user()->hasPermission('pinjam_pakai'))
                    <div x-data="{ key: 'laporan', open: false }"
                        @sidebar-group-opened.window="if ($event.detail.key !== key) { open = false; const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = false; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); }"
                        x-init="(() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                            open = s[key] ?? ({{ request()->routeIs('reports.index') || request()->routeIs('reports.kartu.tahunan') ? 'true' : 'false' }}); })()">
                        <button
                            @click="sidebarOpen ? (open = !open, open && $dispatch('sidebar-group-opened', { key: key }), (() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = open; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); })()) : (window.location.href='{{ route('reports.index') }}')"
                            class="w-full flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg cursor-pointer hover:bg-indigo-500 hover:text-white"
                            style="color: var(--sidebar-text)"
                            :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-file-alt"></i>
                                <span x-show="sidebarOpen" x-cloak>Laporan</span>
                            </span>
                            <svg x-show="sidebarOpen" x-cloak :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="sidebarOpen && open" x-cloak x-collapse.duration.300ms
                            class="mt-2 rounded-lg overflow-hidden submenu-stagger"
                            :class="open ? 'submenu-open' : ''" style="background: var(--sidebar-hover)">
                            
                            @if(auth()->user()->hasPermission('laporan_persediaan'))
                            <a href="{{ route('reports.index') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.index') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Laporan Persediaan
                            </a>
                            <a href="{{ route('reports.kartu.tahunan') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.kartu.tahunan') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Kartu Persediaan Tahunan
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('stock_opname'))
                            <a href="{{ route('reports.opname.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.opname.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Stock Opname
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('pinjam_pakai'))
                            <a href="{{ route('reports.pinjam.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.pinjam.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Pinjam Pakai
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('surat_pesanan') || auth()->user()->hasPermission('pemeriksaan') || auth()->user()->hasPermission('penerimaan') || auth()->user()->hasPermission('berkas_lainnya'))
                    <div x-data="{ key: 'kwitansi', open: false }"
                        @sidebar-group-opened.window="if ($event.detail.key !== key) { open = false; const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = false; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); }"
                        x-init="(() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                            open = s[key] ?? ({{ request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.nota.list') || request()->routeIs('reports.pemeriksaan.list') || request()->routeIs('reports.penerimaan.list') ? 'true' : 'false' }}); })()">
                        <button
                            @click="sidebarOpen ? (open = !open, open && $dispatch('sidebar-group-opened', { key: key }), (() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = open; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); })()) : (window.location.href='{{ route('reports.kwitansi.list') }}')"
                            class="w-full flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg cursor-pointer hover:bg-indigo-500 hover:text-white"
                            style="color: var(--sidebar-text)"
                            :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-receipt"></i>
                                <span x-show="sidebarOpen" x-cloak>Berkas</span>
                            </span>
                            <svg x-show="sidebarOpen" x-cloak :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transform transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="sidebarOpen && open" x-cloak x-collapse.duration.300ms
                            class="mt-2 rounded-lg overflow-hidden submenu-stagger"
                            :class="open ? 'submenu-open' : ''" style="background: var(--sidebar-hover)">
                            
                            @if(auth()->user()->hasPermission('surat_pesanan'))
                            <a href="{{ route('reports.nota.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.nota.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Surat Pesanan
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('pemeriksaan'))
                            <a href="{{ route('reports.pemeriksaan.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.pemeriksaan.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Pemeriksaan
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('penerimaan'))
                            <a href="{{ route('reports.penerimaan.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.penerimaan.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Penerimaan
                            </a>
                            @endif
                            
                            @if(auth()->user()->hasPermission('berkas_lainnya'))
                            <a href="{{ route('reports.kwitansi.list') }}"
                                class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('reports.kwitansi.list') ? 'bg-indigo-500 text-white' : '' }}"
                                style="color: var(--sidebar-text)">
                                Daftar Kwitansi
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    
                    @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('pengaturan_opd'))
                        <div x-data="{ key: 'settings', open: false }"
                            @sidebar-group-opened.window="if ($event.detail.key !== key) { open = false; const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = false; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); }"
                            x-init="(() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}');
                                open = s[key] ?? ({{ request()->routeIs('settings.opd.*') || request()->routeIs('settings.nota.master.*') || request()->routeIs('settings.backup.*') ? 'true' : 'false' }}); })()">
                            <button
                                @click="sidebarOpen ? (open = !open, open && $dispatch('sidebar-group-opened', { key: key }), (() => { const s = JSON.parse(localStorage.getItem('sidebarOpenGroups') || '{}'); s[key] = open; localStorage.setItem('sidebarOpenGroups', JSON.stringify(s)); })()) : (window.location.href='{{ route('settings.opd.edit') }}')"
                                class="w-full flex items-center px-4 py-3 text-sm font-semibold transition rounded-lg cursor-pointer hover:bg-indigo-500 hover:text-white"
                                style="color: var(--sidebar-text)"
                                :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-gear"></i>
                                    <span x-show="sidebarOpen" x-cloak>Pengaturan</span>
                                </span>
                                <svg x-show="sidebarOpen" x-cloak :class="{ 'rotate-180': open }"
                                    class="w-4 h-4 transform transition-transform duration-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linecap="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="sidebarOpen && open" x-cloak
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-4"
                                class="mt-2 rounded-lg overflow-hidden submenu-stagger"
                                :class="open ? 'submenu-open' : ''" style="background: var(--sidebar-hover)">
                                
                                @if(auth()->user()->hasPermission('pengaturan_opd'))
                                <a href="{{ route('settings.opd.edit') }}"
                                    class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('settings.opd.*') || request()->routeIs('settings.nota.master.*') ? 'bg-indigo-500 text-white' : '' }}"
                                    style="color: var(--sidebar-text)">
                                    Profil & Penandatangan
                                </a>
                                @endif

                                @if (Auth::check() && Auth::user()->isAdmin())

                                    <a href="{{ route('users.index') }}"
                                        class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('users.*') ? 'bg-indigo-500 text-white' : '' }}"
                                        style="color: var(--sidebar-text)">
                                        Pengguna
                                    </a>
                                    <a href="{{ route('activity_log.index') }}"
                                        class="block pl-10 pr-6 py-2 text-sm font-medium transition hover:bg-indigo-500 hover:text-white {{ request()->routeIs('activity_log.index') ? 'bg-indigo-500 text-white' : '' }}"
                                        style="color: var(--sidebar-text)">
                                        Activity Log
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                                   </div>
            </nav>

            {{-- Sidebar Footer - Fixed at bottom --}}
            <div class="mt-auto border-t transition-all duration-300" 
                :class="[
                    sidebarOpen ? 'px-4 py-4' : 'px-0 py-4',
                    theme === 'dark' ? 'border-white/10 bg-black/20' : 'border-slate-200 bg-slate-100/50'
                ]">
                
                <div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-3 opacity-50 text-center" :style="{ color: 'var(--sidebar-text)' }">Developer</p>
                </div>

                <div class="flex items-center justify-center transition-all duration-300" :class="sidebarOpen ? 'gap-3' : 'flex-col gap-4'">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/6285824268216" target="_blank"
                        x-on:mouseenter="$el.style.transform='translateY(-4px)'; $el.querySelector('i').style.color='#25D366'; $el.querySelector('i').style.filter='drop-shadow(0 0 6px rgba(37,211,102,0.8))'; $el.querySelector('i').style.transform='scale(1.15)'"
                        x-on:mouseleave="$el.style.transform=''; $el.querySelector('i').style.color=''; $el.querySelector('i').style.filter=''; $el.querySelector('i').style.transform=''"
                        class="flex items-center justify-center transition-all duration-300 group"
                        :class="[
                            sidebarOpen ? 'w-10 h-10 rounded-xl border transition-colors' : 'w-6 h-6',
                            theme === 'dark' ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-slate-200 hover:bg-slate-50'
                        ]" title="WhatsApp">
                        <i class="fab fa-whatsapp transition-all duration-300" :style="{ color: theme === 'dark' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }" :class="sidebarOpen ? 'text-lg' : 'text-xs'"></i>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:supandialentadu14@gmail.com" target="_blank"
                        x-on:mouseenter="$el.style.transform='translateY(-4px)'; $el.querySelector('i').style.color='#EA4335'; $el.querySelector('i').style.filter='drop-shadow(0 0 6px rgba(234,67,53,0.8))'; $el.querySelector('i').style.transform='scale(1.15)'"
                        x-on:mouseleave="$el.style.transform=''; $el.querySelector('i').style.color=''; $el.querySelector('i').style.filter=''; $el.querySelector('i').style.transform=''"
                        class="flex items-center justify-center transition-all duration-300 group"
                        :class="[
                            sidebarOpen ? 'w-10 h-10 rounded-xl border transition-colors' : 'w-6 h-6',
                            theme === 'dark' ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-slate-200 hover:bg-slate-50'
                        ]" title="Email Developer">
                        <i class="far fa-envelope transition-all duration-300" :style="{ color: theme === 'dark' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }" :class="sidebarOpen ? 'text-lg' : 'text-xs'"></i>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/emonn_65?igsh=MWM4c2JzdjNvZG4xMQ%3D%3D&utm_source=qr" target="_blank"
                        x-on:mouseenter="$el.style.transform='translateY(-4px)'; $el.querySelector('i').style.color='#E1306C'; $el.querySelector('i').style.filter='drop-shadow(0 0 6px rgba(225,48,108,0.8))'; $el.querySelector('i').style.transform='scale(1.15)'"
                        x-on:mouseleave="$el.style.transform=''; $el.querySelector('i').style.color=''; $el.querySelector('i').style.filter=''; $el.querySelector('i').style.transform=''"
                        class="flex items-center justify-center transition-all duration-300 group"
                        :class="[
                            sidebarOpen ? 'w-10 h-10 rounded-xl border transition-colors' : 'w-6 h-6',
                            theme === 'dark' ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-slate-200 hover:bg-slate-50'
                        ]" title="Instagram">
                        <i class="fab fa-instagram transition-all duration-300" :style="{ color: theme === 'dark' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }" :class="sidebarOpen ? 'text-lg' : 'text-xs'"></i>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/share/18J61xd2XQ/?mibextid=wwXIfr" target="_blank"
                        x-on:mouseenter="$el.style.transform='translateY(-4px)'; $el.querySelector('i').style.color='#1877F2'; $el.querySelector('i').style.filter='drop-shadow(0 0 6px rgba(24,119,242,0.8))'; $el.querySelector('i').style.transform='scale(1.15)'"
                        x-on:mouseleave="$el.style.transform=''; $el.querySelector('i').style.color=''; $el.querySelector('i').style.filter=''; $el.querySelector('i').style.transform=''"
                        class="flex items-center justify-center transition-all duration-300 group"
                        :class="[
                            sidebarOpen ? 'w-10 h-10 rounded-xl border transition-colors' : 'w-6 h-6',
                            theme === 'dark' ? 'bg-white/5 border-white/10 hover:bg-white/10' : 'bg-white border-slate-200 hover:bg-slate-50'
                        ]" title="Facebook">
                        <i class="fab fa-facebook-f transition-all duration-300" :style="{ color: theme === 'dark' ? 'rgba(255,255,255,0.3)' : 'rgba(0,0,0,0.3)' }" :class="sidebarOpen ? 'text-lg' : 'text-xs'"></i>
                    </a>
                </div>
            </div>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden" :style="{ backgroundColor: 'var(--body-bg)' }">
            <!-- Topbar -->
            <header class="shadow min-h-[4rem] h-auto flex items-center justify-between px-4 md:px-6 z-20 py-2 transition-colors duration-300"
                :style="{ backgroundColor: theme === 'dark' ? '#12171F' : '#ffffff', color: 'var(--body-text)', borderBottom: theme === 'dark' ? '1px solid rgba(255,255,255,0.08)' : '1px solid #f3f4f6' }">
                <button @click="sidebarOpen = !sidebarOpen" class="focus:outline-none p-1 rounded-lg hover:bg-gray-100 transition"
                    :style="{ color: '#374151' }">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Search Bar or Spacer -->
                <!-- MARQUEE TEXT -->
                <div class="hidden md:block flex-1 mx-6 min-w-0">
                    <div class="marquee-container group">
                        <div class="marquee-text">
                            <span class="inline-flex items-center">
                                <i class="fas fa-bullhorn mr-2 text-indigo-600 group-hover:text-indigo-700"></i>
                                Sistem Informasi Pengelolaan Persediaan Barang (SI-LARANG) • {{ \App\Models\OpdSetting::where('user_id', Auth::id())->value('nama_opd') ?? 'Dinas Komunikasi dan Informatika' }} • Bolaang Mongondow Selatan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="flex items-center space-x-4 flex-shrink-0">
                    @php
                        $unreadCount = Auth::user()->unreadNotifications->count();
                        $lowStockCount = isset($lowStockProducts) ? $lowStockProducts->count() : 0;
                        $totalAlerts = $unreadCount + $lowStockCount;
                    @endphp

                    <div class="relative" x-data="{ notifyOpen: false }">
                        <button @click="notifyOpen = !notifyOpen"
                            class="text-gray-400 hover:text-blue-600 transition relative focus:outline-none cursor-pointer">
                            <i class="fas fa-bell text-xl"></i>
                            @if ($totalAlerts > 0)
                                <span
                                    class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-500 transform translate-x-1/2 -translate-y-1/2 animate-pulse"></span>
                            @endif
                        </button>

                        <div x-show="notifyOpen" x-cloak @click.away="notifyOpen = false"
                            class="fixed md:absolute left-4 right-4 md:left-1/2 md:-translate-x-1/2 md:right-auto md:inset-x-auto mt-2 top-[4.5rem] md:top-full w-auto md:w-80 sm:max-w-sm rounded-xl shadow-2xl z-[9999] ring-1 overflow-hidden transition-all duration-300 transform md:-translate-x-1/2"
                            :class="theme === 'dark' ? 'bg-[#1e293b] ring-white/10' : 'bg-white ring-black/5'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">

                            <div class="px-4 py-3 border-b flex justify-between items-center transition-colors"
                                :class="theme === 'dark' ? 'border-white/10 bg-slate-800/50' : 'border-gray-100 bg-gray-50'">
                                <span class="text-xs font-bold uppercase tracking-wider" :class="theme === 'dark' ? 'text-slate-400' : 'text-gray-500'">Notifikasi Sistem</span>
                                @if($totalAlerts > 0)
                                <span class="bg-red-500 text-white py-0.5 px-2 rounded-full text-[10px] font-bold">{{ $totalAlerts }}</span>
                                @endif
                            </div>

                            <div class="max-h-[70vh] md:max-h-80 overflow-y-auto custom-scrollbar">
                                {{-- Low Stock Alerts (Real-time) --}}
                                @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
                                    <div class="px-4 py-2 border-b transition-colors"
                                        :class="theme === 'dark' ? 'bg-rose-500/10 border-rose-500/20' : 'bg-rose-50/50 border-rose-100'">
                                        <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest">Peringatan Stok</p>
                                    </div>
                                    @foreach($lowStockProducts as $product)
                                        <div class="block px-4 py-3 transition-colors border-b last:border-0 relative"
                                            :class="theme === 'dark' ? 'hover:bg-rose-500/5 border-white/5' : 'hover:bg-rose-50/30 border-gray-50'">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 bg-rose-100 rounded-full h-8 w-8 flex items-center justify-center">
                                                    <i class="fas fa-exclamation-circle text-rose-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-baseline mb-0.5">
                                                        <p class="text-[11px] font-bold truncate" :class="theme === 'dark' ? 'text-slate-200' : 'text-gray-800'">
                                                            {{ $product->name }}
                                                        </p>
                                                        <span class="text-[9px] font-bold text-rose-600 bg-rose-100 px-1.5 py-0.5 rounded flex-shrink-0">STOK RENDAH</span>
                                                    </div>
                                                    <p class="text-[10px] leading-tight" :class="theme === 'dark' ? 'text-slate-400' : 'text-gray-500'">
                                                        Sisa stok: <strong :class="theme === 'dark' ? 'text-rose-400' : 'text-gray-700'">{{ $product->stock }}</strong> {{ $product->unit }}. Segera pengadaan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Database Notifications --}}
                                @if($unreadCount > 0)
                                    <div class="px-4 py-2 border-b transition-colors"
                                        :class="theme === 'dark' ? 'bg-slate-800/50 border-white/5' : 'bg-gray-50 border-gray-100'">
                                        <p class="text-[10px] font-bold uppercase tracking-widest" :class="theme === 'dark' ? 'text-slate-500' : 'text-gray-400'">Riwayat Notifikasi</p>
                                    </div>
                                @endif

                                @forelse (Auth::user()->unreadNotifications as $notification)
                                    <div class="block px-4 py-3 transition group border-b last:border-0 relative"
                                        :class="theme === 'dark' ? 'hover:bg-white/5 border-white/5' : 'hover:bg-gray-50 border-gray-50'">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 bg-indigo-100 rounded-full h-8 w-8 flex items-center justify-center">
                                                <i class="fas fa-bell text-indigo-600 text-xs"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-baseline mb-0.5">
                                                    <p class="text-[11px] font-bold truncate" :class="theme === 'dark' ? 'text-slate-200' : 'text-gray-800'">
                                                        {{ $notification->data['product_name'] ?? 'Peringatan' }}
                                                    </p>
                                                    <p class="text-[9px] whitespace-nowrap ml-2" :class="theme === 'dark' ? 'text-slate-500' : 'text-gray-400'">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <p class="text-[10px] line-clamp-2 leading-tight" :class="theme === 'dark' ? 'text-slate-400' : 'text-gray-500'">
                                                    {{ $notification->data['message'] }}
                                                </p>
                                            </div>
                                        </div>
                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition no-soft">
                                            @csrf
                                            <button type="submit" class="text-gray-300 hover:text-indigo-500 p-1" title="Tandai sudah dibaca">
                                                <i class="fas fa-check-circle text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    @if(!isset($lowStockProducts) || $lowStockProducts->count() == 0)
                                        <div class="py-12 text-center text-gray-400">
                                            <i class="fas fa-bell-slash text-3xl mb-3 block opacity-20"></i>
                                            <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                                        </div>
                                    @endif
                                @endforelse
                            </div>
                            
                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="p-2 bg-gray-50 border-t border-gray-100 text-center">
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="no-soft">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 uppercase tracking-tighter">
                                        Tandai Semua Sudah Dibaca
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Theme Toggle Button -->
                    <button @click="theme = (theme === 'dark' ? 'light' : 'dark')"
                        class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 focus:outline-none"
                        :class="theme === 'dark' ? 'bg-yellow-400/10 text-yellow-300 hover:bg-yellow-400/20' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                        :title="theme === 'dark' ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'">
                        <i class="fas text-lg transition-all duration-300" :class="theme === 'dark' ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <div class="h-6 w-px mx-2" :style="{ backgroundColor: theme === 'dark' ? 'rgba(255,255,255,0.15)' : '#d1d5db' }"></div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center gap-3 focus:outline-none cursor-pointer">
                            <img class="h-9 w-9 rounded-full object-cover ring-2 ring-indigo-200"
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}"
                                alt="User">
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-bold leading-tight" :style="{ color: theme === 'dark' ? '#f1f5f9' : '#111827' }">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs" :style="{ color: theme === 'dark' ? '#94a3b8' : '#6B7280' }">{{ Auth::user()->email }}</p>
                            </div>
                            <i class="fas fa-chevron-down hidden md:block" :style="{ color: theme === 'dark' ? '#64748b' : '#9CA3AF' }"></i>
                        </button>

                        <div x-show="open" x-cloak @click.away="open = false"
                            class="absolute right-0 mt-2 w-64 rounded-xl shadow-xl z-50 ring-1 ring-black ring-opacity-5 overflow-hidden transition-all duration-300"
                            :class="theme === 'dark' ? 'bg-[#1e293b] ring-white/10' : 'bg-white'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100">
                            <div class="p-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <div class="flex items-center gap-3">
                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-white"
                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}"
                                        alt="User">
                                    <div>
                                        <p class="font-bold leading-tight">{{ Auth::user()->name }}</p>
                                        <p class="text-xs opacity-80">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm transition-colors"
                                    :class="theme === 'dark' ? 'hover:bg-slate-700/50 text-slate-200' : 'hover:bg-gray-50 text-gray-700'"
                                    :style="{ color: theme === 'dark' ? '#e2e8f0' : '#374151' }">
                                    <i class="fas fa-user-edit text-indigo-500"></i>
                                    Edit Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="no-soft">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm transition-colors"
                                        :class="theme === 'dark' ? 'hover:bg-slate-700/50 text-slate-200' : 'hover:bg-gray-50 text-gray-700'"
                                        :style="{ color: theme === 'dark' ? '#e2e8f0' : '#374151' }">
                                        <i class="fas fa-sign-out-alt text-rose-500"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Loading Progress Bar -->
            <div id="nav-loader" class="fixed top-0 left-0 right-0 h-1 z-[9999] transition-all duration-300 opacity-0 pointer-events-none" style="background: linear-gradient(90deg, #6366f1 0%, #a855f7 50%, #6366f1 100%); background-size: 200% 100%; animation: navLoading 2s linear infinite;"></div>
            <style>
                @keyframes navLoading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
                .nav-loading-active #nav-loader { opacity: 1; }
            </style>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6 transition-colors duration-300"
                :style="{ backgroundColor: 'var(--body-bg)', color: 'var(--body-text)' }">
                <!-- Page Header & Actions -->
                <div id="page-header" class="mb-4 md:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold" :style="{ color: 'var(--body-text)' }">@yield('header')</h2>
                        <p class="text-xs md:text-sm mt-1" :style="{ color: theme === 'dark' ? '#94a3b8' : '#6B7280' }">@yield('subheader')</p>
                    </div>
                    <div class="flex items-center flex-wrap gap-2">
                        @yield('actions')
                    </div>
                </div>

                @if (session('success'))
                    <div
                        class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <span class="text-green-700 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                            <span class="text-red-700 font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                        <p class="font-bold text-red-700">Action Failed</p>
                        <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
            <footer class="no-print border-t transition-colors duration-300"
                :style="{ backgroundColor: theme === 'dark' ? '#12171F' : '#ffffff', color: theme === 'dark' ? '#94a3b8' : '#6B7280', borderColor: theme === 'dark' ? 'rgba(255,255,255,0.08)' : '#f3f4f6' }">
                <div class="px-6 py-4 flex items-center justify-between">
                    <p class="text-xs md:text-sm font-medium">
                        Copyright © 2026 Emon Alentadu. Seluruh Hak Cipta Dilindungi.
                    </p>
                    <div class="hidden md:flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-shield-alt text-indigo-500"></i>
                            Keamanan Data Terjaga
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-heart text-pink-500"></i>
                            Terima Kasih Telah Menggunakan SI-LARANG
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const main = document.querySelector('main');
            const sidebar = document.querySelector('aside nav');
            const isSameOrigin = (url) => {
                try {
                    const u = new URL(url, window.location.origin);
                    return u.origin === window.location.origin;
                } catch {
                    return false;
                }
            };
            const isActionLink = (a) => {
                const txt = (a.textContent || '').trim();
                const href = a.getAttribute('href') || '';
                return /(edit|lihat|view|show|detail)/i.test(txt) || /(\/edit|\/show|\/view|detail)/i.test(
                href);
            };
            const shouldSoftLink = (a) => {
                const href = a.getAttribute('href') || '';
                if (!href || href.startsWith('#')) return false;
                if (!isSameOrigin(href)) return false;
                if (a.hasAttribute('download') || a.target === '_blank') return false;
                if (a.classList.contains('no-soft')) return false;
                if (/export/i.test(href)) return false;
                return true;
            };
            const setActive = (href) => {
                const anchors = sidebar.querySelectorAll('a[href]');
                anchors.forEach(a => a.classList.remove('bg-indigo-800', 'bg-indigo-500', 'text-white'));
                const target = sidebar.querySelector(`a[href="${href}"]`);
                if (target) target.classList.add('bg-indigo-500', 'text-white');
            };
            const initDatepickr = (root) => {
                if (typeof flatpickr !== 'undefined') {
                    flatpickr(root.querySelectorAll('input[type="date"]'), {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d M Y",
                        locale: "id",
                        disableMobile: "true"
                    });
                }
            };
            const initScripts = (root) => {
                const scripts = root.querySelectorAll('script');
                scripts.forEach(s => {
                    const n = document.createElement('script');
                    if (s.src) {
                        n.src = s.src;
                    } else {
                        n.textContent = s.textContent;
                    }
                    if (s.type) n.type = s.type;
                    root.appendChild(n);
                });
                if (window.Alpine && Alpine.initTree) Alpine.initTree(root);
                initDatepickr(root);
            };

            // Initialize global pickers on initial run
            initDatepickr(document);

            const loader = document.getElementById('nav-loader');
            const swapMain = async (href, push = true) => {
                document.body.classList.add('nav-loading-active');
                try {
                    const res = await fetch(href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!res.ok) throw new Error('Failed');
                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newMain = doc.querySelector('main');
                    if (!newMain) {
                        window.location.href = href;
                        return;
                    }
                    document.title = doc.title || document.title;
                    main.innerHTML = newMain.innerHTML;
                    setActive(res.url);
                    initScripts(main);
                    if (push) history.pushState({}, '', res.url);
                    main.scrollTop = 0;
                } catch (e) {
                    console.error('Nav error:', e);
                    window.location.href = href;
                } finally {
                    document.body.classList.remove('nav-loading-active');
                }
            };
            sidebar.addEventListener('click', (e) => {
                const a = e.target.closest('a[href]');
                if (!a) return;
                if (!shouldSoftLink(a)) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                e.preventDefault();
                const href = a.getAttribute('href');
                swapMain(href, true);
            });
            window.addEventListener('popstate', () => swapMain(window.location.href, false));
            document.addEventListener('click', (e) => {
                const a = e.target.closest('a[href]');
                if (!a) return;
                
                // Sidebar already handled by specific listener, but we check here too for safety
                if (!shouldSoftLink(a)) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                
                // Avoid redundant click on active link
                if (a.getAttribute('href') === window.location.href) {
                    e.preventDefault();
                    return;
                }

                e.preventDefault();
                const href = a.getAttribute('href');
                swapMain(href, true);
            });
            document.addEventListener('submit', async (e) => {
                const form = e.target.closest('form');
                if (!form) return;
                if (form.classList.contains('no-soft')) return;
                const action = form.getAttribute('action') || window.location.href;
                const method = (form.getAttribute('method') || 'GET').toUpperCase();
                if (!isSameOrigin(action)) return;
                e.preventDefault();
                try {
                    const fd = new FormData(form);
                    let url = action;
                    const options = {
                        method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    };

                    if (method === 'GET') {
                        const u = new URL(action, window.location.origin);
                        const params = new URLSearchParams(fd);
                        params.forEach((v, k) => u.searchParams.set(k, v));
                        url = u.toString();
                    } else {
                        options.body = fd;
                    }

                    const res = await fetch(url, {
                        ...options,
                        cache: 'no-cache'
                    });
                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newMain = doc.querySelector('main');
                    if (!newMain) {
                        window.location.href = url;
                        return;
                    }
                    document.title = doc.title || document.title;
                    main.innerHTML = newMain.innerHTML;
                    setActive(res.url);
                    initScripts(main);
                    history.pushState({}, '', res.url);
                    main.scrollTop = 0;
                } catch {
                    window.location.href = action;
                }
            });
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then((registration) => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }).catch((error) => {
                    console.error('ServiceWorker registration failed: ', error);
                });
            });
        }
    </script>
</body>

</html>
