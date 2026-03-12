@extends('layouts.admin')

@section('header', 'Dashboard')

@section('actions')
    <form action="{{ route('dashboard') }}" method="GET" id="dateFilterForm"
        class="dash-filter-form flex items-center gap-2 px-3 py-1.5 rounded-xl border shadow-sm backdrop-blur-sm">
        <i class="fas fa-calendar-alt text-indigo-500 text-sm"></i>
        <input type="date" id="dateInput" name="date" value="{{ request('date') ?? date('Y-m-d') }}"
            class="dash-filter-input"
            onchange="this.form.submit()">
        @if(request('date'))
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-red-500 transition ml-1" title="Reset">
                <i class="fas fa-times text-xs"></i>
            </a>
        @endif
    </form>
@endsection

@section('content')
<div class="space-y-8 pb-10">

    {{-- ══════════════════════════════════════════ --}}
    {{-- WELCOME BANNER                            --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 40%, #f64f59 100%);">
        {{-- Decorative circles --}}
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-12 -left-8 w-48 h-48 bg-white/10 rounded-full blur-lg"></div>
        <div class="absolute top-4 right-40 w-20 h-20 bg-yellow-300/20 rounded-full blur-md"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-white/70 text-sm font-medium mb-1">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <h1 class="text-white text-2xl md:text-3xl font-extrabold leading-tight">
                    Halo, {{ Auth::user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-white/60 text-sm mt-1">
                    Selamat datang di SI-LARANG — Sistem Informasi Pengelolaan Persediaan Barang
                </p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('stock.create') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white rounded-xl text-sm font-semibold transition-all border border-white/20 hover:scale-105">
                    <i class="fas fa-plus-circle"></i> Tambah Transaksi
                </a>
                <a href="{{ route('stock.index') }}"
                    class="flex items-center gap-2 px-4 py-2 bg-white text-purple-700 hover:bg-yellow-50 rounded-xl text-sm font-semibold transition-all shadow-lg hover:scale-105">
                    <i class="fas fa-list"></i> Lihat Stok
                </a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- TOP KPI CARDS (4 cards)                   --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Produk --}}
        <div class="relative overflow-hidden rounded-2xl p-5 group hover:-translate-y-1 transition-all duration-300 cursor-default"
            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); box-shadow: 0 10px 30px rgba(79, 172, 254, 0.35);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-white/25 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-box text-white text-lg"></i>
                </div>
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider">Total Produk</p>
                <p class="text-white text-3xl font-black mt-1">{{ $totalProducts }}</p>
                <p class="text-white/60 text-xs mt-2 font-medium">{{ $totalCategories }} Kategori</p>
            </div>
        </div>

        {{-- Card 2: Total Stok --}}
        <div class="relative overflow-hidden rounded-2xl p-5 group hover:-translate-y-1 transition-all duration-300 cursor-default"
            style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); box-shadow: 0 10px 30px rgba(67, 233, 123, 0.35);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-white/25 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-cubes text-white text-lg"></i>
                </div>
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider">Total Stok</p>
                <p class="text-white text-3xl font-black mt-1">{{ number_format($totalStock) }}</p>
                <p class="text-white/60 text-xs mt-2 font-medium">Unit tersedia</p>
            </div>
        </div>

        {{-- Card 3: Nilai Valuasi --}}
        <div class="relative overflow-hidden rounded-2xl p-5 group hover:-translate-y-1 transition-all duration-300 cursor-default"
            style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); box-shadow: 0 10px 30px rgba(253, 160, 133, 0.35);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-white/25 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider">Nilai Persediaan</p>
                <p class="text-white text-xl md:text-2xl font-black mt-1">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</p>
                <p class="text-white/60 text-xs mt-2 font-medium">Total valuasi aset</p>
            </div>
        </div>

        {{-- Card 4: Supplier --}}
        <div class="relative overflow-hidden rounded-2xl p-5 group hover:-translate-y-1 transition-all duration-300 cursor-default"
            style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); box-shadow: 0 10px 30px rgba(161, 140, 209, 0.35);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
            <div class="absolute -right-2 bottom-0 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-white/25 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-handshake text-white text-lg"></i>
                </div>
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider">Penyedia</p>
                <p class="text-white text-3xl font-black mt-1">{{ $supplierCount }}</p>
                <p class="text-white/60 text-xs mt-2 font-medium">Partner aktif</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- ACTIVITY CARDS (Today)                    --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Barang Masuk --}}
        <div class="dash-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);">
                <i class="fas fa-arrow-circle-down text-2xl text-green-700"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Masuk Hari Ini</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $inToday }} <span class="text-sm font-normal text-gray-400">item</span></p>
                <p class="text-xs text-green-600 font-semibold">+ Rp {{ number_format($valueInToday, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Barang Keluar --}}
        <div class="dash-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <i class="fas fa-arrow-circle-up text-2xl text-red-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Keluar Hari Ini</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $outToday }} <span class="text-sm font-normal text-gray-400">item</span></p>
                <p class="text-xs text-red-500 font-semibold">− Rp {{ number_format($valueOutToday, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Total Transaksi --}}
        <div class="dash-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                <i class="fas fa-exchange-alt text-2xl text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $transactionsToday }} <span class="text-sm font-normal text-gray-400">aktivitas</span></p>
                <p class="text-xs text-blue-500 font-semibold">{{ $pinjamCount }} Dokumen Pinjam Pakai</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- MAIN CHARTS ROW                           --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Stock Movement Chart (2/3) --}}
        <div class="dash-card lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-50">
                <div>
                    <h2 class="text-base font-extrabold text-gray-800">Pergerakan Stok Hari Ini</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Berdasarkan jam transaksi</p>
                </div>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-emerald-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 font-semibold text-rose-500">
                        <span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="h-72">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Category Donut Chart (1/3) --}}
        <div class="dash-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 pt-5 pb-4 border-b border-gray-50">
                <h2 class="text-base font-extrabold text-gray-800">Distribusi Kategori</h2>
                <p class="text-xs text-gray-400 mt-0.5">Stok per kategori barang</p>
            </div>
            <div class="p-5">
                @if($categoryValues->sum() > 0)
                    <div class="relative mx-auto" style="width:180px; height:180px;">
                        <canvas id="categoryChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-black text-gray-800">{{ $categoryValues->sum() }}</span>
                            <span class="text-xs text-gray-400">Total</span>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2" id="categoryLegend"></div>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                        <i class="fas fa-chart-pie text-5xl mb-3"></i>
                        <p class="text-sm">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- MONTHLY TREND + ACTIVITY + CRITICAL STOCK  --}}
    {{-- ══════════════════════════════════════════ --}}
    {{-- Remove misplaced closing div --}}

    {{-- ══════════════════════════════════════════ --}}
    {{-- TOP PRODUCTS + VALUE TREND                --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Products List --}}
        <div class="dash-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-slate-50/50">
                <h2 class="text-base font-extrabold text-gray-800">5 Barang Terpopuler</h2>
                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Berdasarkan frekuensi transaksi</p>
            </div>
            <div class="p-4 space-y-3">
                @foreach($topProducts as $idx => $p)
                <div class="flex items-center gap-3 p-2 rounded-xl border border-transparent hover:border-indigo-100 hover:bg-indigo-50/30 transition group">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ $idx + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate group-hover:text-indigo-700 transition">{{ $p->name }}</p>
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">{{ $p->category->name ?? 'Tanpa Kategori' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-indigo-600">{{ $p->transactions_count }}</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase">Aksi</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Value Trend Bar Chart (Monthly Valuation) --}}
        <div class="dash-card lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-50">
                <div>
                    <h2 class="text-base font-extrabold text-gray-800">Tren Valuasi Pengadaan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Nilai Rupiah 6 Bulan terakhir</p>
                </div>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-emerald-600">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 font-semibold text-rose-500">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="valueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Monthly Trend Bar Chart --}}
        <div class="dash-card lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-50">
                <div>
                    <h2 class="text-base font-extrabold text-gray-800">Tren Pengadaan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">6 Bulan terakhir</p>
                </div>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-violet-600">
                        <span class="w-3 h-3 rounded-sm bg-violet-500 inline-block"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 font-semibold text-pink-500">
                        <span class="w-3 h-3 rounded-sm bg-pink-400 inline-block"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="p-5">
                <div class="h-60">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Activity Feed --}}
        <div class="dash-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                <h2 class="text-base font-extrabold text-gray-800">Aktivitas Terbaru</h2>
                <a href="{{ route('stock.index') }}"
                    class="text-xs font-bold text-indigo-500 hover:text-indigo-700 transition">Lihat Semua →</a>
            </div>
            <div class="flex-1 overflow-y-auto divide-y divide-gray-50 max-h-[280px] dash-scroll">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold
                        {{ $tx->type == 'in' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <i class="fas {{ $tx->type == 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $tx->product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $tx->date->format('H:i') }}</p>
                    </div>
                    <span class="text-sm font-bold {{ $tx->type == 'in' ? 'text-green-600' : 'text-red-500' }} whitespace-nowrap">
                        {{ $tx->type == 'in' ? '+' : '−' }}{{ $tx->quantity }}
                    </span>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center p-8 text-gray-300">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p class="text-xs">Belum ada transaksi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- CRITICAL STOCK TABLE                      --}}
    {{-- ══════════════════════════════════════════ --}}


</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- SCRIPTS                                       --}}
{{-- ══════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Colour Palette ─────────────────────────── */
    const PALETTE = ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6','#a855f7','#ec4899','#14b8a6','#f97316','#84cc16'];

    /* ── Theme detection ─────────────────────────── */
    const isDark    = document.body.classList.contains('theme-dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : '#f3f4f6';
    const tickColor = isDark ? '#64748b' : '#9ca3af';

    /* ── Helper: gradient ───────────────────────── */
    function makeLinearGradient(ctx, top, bottom) {
        const g = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
        g.addColorStop(0, top);
        g.addColorStop(1, bottom);
        return g;
    }

    /* ════════════════════════════════════════════ */
    /*  1. STOCK MOVEMENT CHART (per-hour area)    */
    /* ════════════════════════════════════════════ */
    const sc = document.getElementById('stockChart');
    if (sc) {
        const ctx = sc.getContext('2d');
        const gradIn  = makeLinearGradient(ctx, 'rgba(52,211,153,0.55)', 'rgba(52,211,153,0.04)');
        const gradOut = makeLinearGradient(ctx, 'rgba(251,113,133,0.55)', 'rgba(251,113,133,0.04)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($dataIn) !!},
                        borderColor: '#10b981',
                        borderWidth: 2.5,
                        backgroundColor: gradIn,
                        fill: true, tension: 0.45,
                        pointRadius: 3, pointHoverRadius: 6,
                        pointBackgroundColor: '#fff', pointBorderColor: '#10b981', pointBorderWidth: 2,
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($dataOut) !!},
                        borderColor: '#f43f5e',
                        borderWidth: 2.5,
                        backgroundColor: gradOut,
                        fill: true, tension: 0.45,
                        pointRadius: 3, pointHoverRadius: 6,
                        pointBackgroundColor: '#fff', pointBorderColor: '#f43f5e', pointBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index', intersect: false,
                        backgroundColor: '#1e293b', titleColor: '#f8fafc',
                        bodyColor: '#94a3b8', padding: 12, cornerRadius: 10,
                        callbacks: { label: c => ` ${c.dataset.label}: ${c.parsed.y} Unit` }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: tickColor, font: { size: 10 },
                            callback: (val, idx) => idx % 3 === 0 ? this.getLabelForValue(val) : '',
                            maxRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { color: tickColor, font: { size: 10 }, precision: 0 }
                    }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });
    }

    /* ════════════════════════════════════════════ */
    /*  2. CATEGORY DOUGHNUT CHART                 */
    /* ════════════════════════════════════════════ */
    const cc = document.getElementById('categoryChart');
    if (cc) {
        const catLabels = {!! json_encode($categoryLabels) !!};
        const catValues = {!! json_encode($categoryValues) !!};
        new Chart(cc.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: PALETTE,
                    borderWidth: 3, borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                cutout: '72%',
                plugins: { legend: { display: false }, tooltip: {
                    backgroundColor: '#1e293b', titleColor: '#f8fafc', bodyColor: '#94a3b8',
                    padding: 10, cornerRadius: 8,
                }}
            }
        });
        /* Custom Legend */
        const legend = document.getElementById('categoryLegend');
        if (legend) {
            catLabels.forEach((label, i) => {
                legend.innerHTML += `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${PALETTE[i % PALETTE.length]}"></span>
                            <span class="text-xs text-gray-600 font-medium">${label}</span>
                        </div>
                        <span class="text-xs font-bold text-gray-800">${catValues[i]}</span>
                    </div>`;
            });
        }
    }

    /* ════════════════════════════════════════════ */
    /*  3. MONTHLY TREND BAR CHART                 */
    /* ════════════════════════════════════════════ */
    const mc = document.getElementById('monthlyChart');
    if (mc) {
        const mctx = mc.getContext('2d');
        const gradMIn  = mctx.createLinearGradient(0,0,0,240);
        gradMIn.addColorStop(0, '#818cf8'); gradMIn.addColorStop(1, '#6366f1');
        const gradMOut = mctx.createLinearGradient(0,0,0,240);
        gradMOut.addColorStop(0, '#fb7185'); gradMOut.addColorStop(1, '#ec4899');

        new Chart(mctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($monthlyIn) !!},
                        backgroundColor: gradMIn, borderRadius: 8,
                        borderSkipped: false, barPercentage: 0.55, categoryPercentage: 0.65,
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($monthlyOut) !!},
                        backgroundColor: gradMOut, borderRadius: 8,
                        borderSkipped: false, barPercentage: 0.55, categoryPercentage: 0.65,
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b', titleColor: '#f8fafc',
                        bodyColor: '#94a3b8', padding: 12, cornerRadius: 10,
                        callbacks: { label: c => ` ${c.dataset.label}: ${c.parsed.y} Unit` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false },
                         ticks: { color: tickColor, font: { size: 11 }, precision: 0 } }
                }
            }
        });
    }
    /* ════════════════════════════════════════════ */
    /*  4. VALUE TREND CHART (Monthly Rupiah)      */
    /* ════════════════════════════════════════════ */
    const vtc = document.getElementById('valueTrendChart');
    if (vtc) {
        const vtctx = vtc.getContext('2d');
        new Chart(vtctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [
                    {
                        label: 'Nilai Masuk',
                        data: {!! json_encode($monthlyValueIn) !!},
                        backgroundColor: '#10b981', borderRadius: 5,
                    },
                    {
                        label: 'Nilai Keluar',
                        data: {!! json_encode($monthlyValueOut) !!},
                        backgroundColor: '#f43f5e', borderRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + ' jt';
                                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + ' rb';
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
    /* ───── Dash Filter Form ───── */
    .dash-filter-form {
        background: var(--card-bg) !important;
        border: 1px solid var(--card-border) !important;
        display: inline-flex !important;
        align-items: center;
        padding: 0.5rem 1rem !important;
        border-radius: 1rem !important;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow) !important;
    }
    
    .dash-filter-form .dash-filter-input, 
    .dash-filter-form .flatpickr-input { 
        color: var(--body-text) !important; 
        background: transparent !important;
        border: none !important;
        font-weight: 800 !important;
        font-size: 1rem !important;
        width: 160px !important;
        cursor: pointer !important;
        margin: 0 !important;
        padding: 0 0 0 8px !important;
        outline: none !important;
        box-shadow: none !important;
        display: inline-block !important;
        visibility: visible !important;
    }

    .theme-dark .dash-filter-form i {
        color: var(--accent) !important;
    }

    .theme-dark .dash-filter-input::-webkit-calendar-picker-indicator,
    .theme-dark .flatpickr-input::-webkit-calendar-picker-indicator { 
        filter: invert(1); 
    }

    /* ───── Smooth scrollbar ───── */
    .dash-scroll::-webkit-scrollbar { width: 4px; }
    .dash-scroll::-webkit-scrollbar-track { background: transparent; }
    .dash-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    .dash-scroll::-webkit-scrollbar-thumb:hover { background: #d1d5db; }

    /* ══════════════════════════════════════════════════
       DARK MODE — triggered by .theme-dark on <body>
    ════════════════════════════════════════════════════ */

    /* Filter form */
</style>
@endsection