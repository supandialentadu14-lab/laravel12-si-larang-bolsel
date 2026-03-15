@extends('layouts.admin')

@section('header', 'Dashboard')
@section('subheader', 'Statistik Pengelolaan Persediaan Barang • ' . now()->locale('id')->isoFormat('MMMM Y'))

@section('actions')
  <form action="{{ route('dashboard') }}" method="GET" id="dateFilterForm"
    class="flex items-center gap-2 px-4 py-2 rounded-2xl border border-slate-200 shadow-sm backdrop-blur-sm bg-white/50 transition-all hover:bg-white">
    <i class="fas fa-calendar-alt text-indigo-500 text-sm"></i>
    <input type="date" id="dateInput" name="date" value="{{ request('date') ?? date('Y-m-d') }}"
      class="bg-transparent border-none text-slate-800 font-black text-sm outline-none cursor-pointer"
      onchange="this.form.submit()">
    @if(request('date'))
      <a href="{{ route('dashboard') }}" class="text-slate-300 hover:text-rose-500 transition ml-1" title="Reset">
        <i class="fas fa-times-circle text-xs"></i>
      </a>
    @endif
  </form>
@endsection

@section('content')
<div class="space-y-6 pb-20 animate-fadeIn">
  
  <!-- 1. Welcome Header (Mobile Style) -->
  <div class="flex items-center justify-between bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <div class="space-y-1">
      <h2 class="text-3xl font-black text-slate-800 tracking-tight">Hello, {{ Auth::user()->name }}!</h2>
      <p class="text-sm text-slate-400 font-black uppercase tracking-widest">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    <div class="w-20 h-20 rounded-[2rem] bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-500 text-white shadow-xl shadow-indigo-100 relative overflow-hidden flex items-center justify-center">
      <div class="absolute -top-6 -right-6 w-16 h-16 bg-white/20 rounded-full blur-xl"></div>
      <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
      <div class="relative">
        <i class="fas fa-cubes-stacked text-3xl"></i>
        @if (isset($lowStockCount) && $lowStockCount > 0)
          <span class="absolute -top-2 -right-3 min-w-6 h-6 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-black flex items-center justify-center ring-4 ring-white">
            {{ $lowStockCount }}
          </span>
        @endif
      </div>
    </div>
  </div>

  <!-- 2. Quick Stats Cards (2-column layout for desktop to keep it readable) -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-white">
    {{-- Total Stok --}}
    <div class="p-8 rounded-[2.5rem] bg-indigo-600 shadow-2xl shadow-indigo-100 flex flex-col justify-between h-48 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-500">
      <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform"></div>
      <i class="fas fa-box text-5xl opacity-20 group-hover:rotate-12 transition-transform"></i>
      <div>
        <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-1">Total Kuantitas Stok</p>
        <p class="text-5xl font-black leading-none">{{ number_format($totalStock) }}</p>
      </div>
    </div>
    
    {{-- Masuk Hari Ini --}}
    <div class="p-8 rounded-[2.5rem] bg-emerald-500 shadow-2xl shadow-emerald-100 flex flex-col justify-between h-48 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-500">
      <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform"></div>
      <i class="fas fa-arrow-down-to-bracket text-5xl opacity-20 group-hover:translate-x-2 transition-transform"></i>
      <div>
        <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-1">Masuk (Hari Ini)</p>
        <p class="text-5xl font-black leading-none">+{{ number_format($inToday) }}</p>
      </div>
    </div>
  </div>

  <!-- 3. Inventory Valuation (Horizontal Card) -->
  <div class="p-8 rounded-[2.5rem] bg-white shadow-sm border border-slate-100 flex items-center justify-between group hover:border-amber-200 transition-colors">
    <div class="flex items-center gap-8">
      <div class="w-20 h-20 rounded-[2rem] bg-amber-50 text-amber-500 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform">
        <i class="fas fa-wallet"></i>
      </div>
      <div>
        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Nilai Persediaan Keseluruhan</p>
        <p class="text-4xl font-black text-slate-800">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</p>
      </div>
    </div>
    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
      <i class="fas fa-chevron-right"></i>
    </div>
  </div>

  <!-- 4. Ringkasan Data (Grid 2x2 style from mobile but bigger) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="p-6 rounded-[2rem] bg-white border border-slate-100 shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all">
      <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
        <i class="fas fa-boxes-stacked"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Barang</p>
        <p class="text-2xl font-black text-slate-800">{{ number_format($totalProducts) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-white border border-slate-100 shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all">
      <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl">
        <i class="fas fa-tags"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Jenis Belanja</p>
        <p class="text-2xl font-black text-slate-800">{{ number_format($totalCategories) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-white border border-slate-100 shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all">
      <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
        <i class="fas fa-truck-fast"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Penyedia</p>
        <p class="text-2xl font-black text-slate-800">{{ number_format($supplierCount) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-rose-50 border border-rose-100 shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all">
      <div class="w-14 h-14 rounded-2xl bg-white text-rose-500 flex items-center justify-center text-2xl shadow-sm shadow-rose-100">
        <i class="fas fa-warning"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest leading-none mb-1">Stok Rendah</p>
        <p class="text-2xl font-black text-rose-600">{{ number_format($lowStockCount) }}</p>
      </div>
    </div>
  </div>

  <!-- 5. Ringkasan Dokumen (Enhanced for desktop) -->
  <div class="space-y-6">
    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] px-2 mb-4">Ringkasan Dokumen Berkas</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach([
        ['label' => 'Belanja Modal', 'count' => $belanjaModalCount, 'icon' => 'fa-file-contract', 'color' => 'indigo'],
        ['label' => 'Nota Pesanan', 'count' => $notaCount, 'icon' => 'fa-file-invoice', 'color' => 'violet'],
        ['label' => 'Pemeriksaan', 'count' => $pemeriksaanCount, 'icon' => 'fa-check-double', 'color' => 'teal'],
        ['label' => 'Penerimaan', 'count' => $penerimaanCount, 'icon' => 'fa-file-download', 'color' => 'emerald'],
        ['label' => 'Kwitansi', 'count' => $kwitansiCount, 'icon' => 'fa-receipt', 'color' => 'amber'],
        ['label' => 'BA Opname', 'count' => $opnameCount, 'icon' => 'fa-clipboard-check', 'color' => 'cyan'],
        ['label' => 'Pinjam Pakai', 'count' => $pinjamCount, 'icon' => 'fa-people-carry-box', 'color' => 'slate']
      ] as $doc)
        <div class="p-5 rounded-3xl bg-white border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-indigo-200 transition-all">
          <div class="w-12 h-12 rounded-2xl bg-{{ $doc['color'] }}-50 text-{{ $doc['color'] }}-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fas {{ $doc['icon'] }}"></i>
          </div>
          <div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ $doc['label'] }}</p>
            <p class="text-xl font-black text-slate-800">{{ number_format($doc['count'] ?? 0) }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 6. Analisis Performa & Control System (Side by side for desktop) -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    {{-- Tren Performa --}}
    <div class="p-10 rounded-[2.5rem] bg-white border border-slate-100 shadow-sm">
      <div class="flex items-center justify-between mb-8">
        <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Analisis Performa (Bulanan)</h3>
        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-widest">3 Bulan Terakhir</span>
      </div>
      <div class="space-y-6">
        @foreach($monthlyLabels->take(-3) as $idx => $lbl)
          <div class="space-y-3">
            <div class="flex justify-between items-end">
              <div>
                <span class="text-[11px] font-black text-slate-800 uppercase tracking-tighter">{{ $lbl }}</span>
              </div>
              <div class="text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                  In: <span class="text-indigo-600">{{ number_format($monthlyIn[$idx]) }}</span> / 
                  Out: <span class="text-rose-500">{{ number_format($monthlyOut[$idx]) }}</span>
                </span>
              </div>
            </div>
            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
              @php
                $total = $monthlyIn[$idx] + $monthlyOut[$idx];
                $pIn = $total > 0 ? ($monthlyIn[$idx] / $total) * 100 : 0;
                $pOut = $total > 0 ? ($monthlyOut[$idx] / $total) * 100 : 0;
              @endphp
              <div class="h-full bg-indigo-500 transition-all duration-1000" style="width: {{ $pIn }}%"></div>
              <div class="h-full bg-rose-400 transition-all duration-1000" style="width: {{ $pOut }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- System Control Card --}}
    <div class="p-10 rounded-[2.5rem] bg-indigo-600 shadow-2xl shadow-indigo-100 text-white relative overflow-hidden flex flex-col justify-center items-center text-center">
      <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute top-10 left-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-3xl"></div>
      
      <div class="relative z-10 space-y-8 max-w-sm">
        <div class="space-y-4">
          <p class="text-xl font-black uppercase tracking-[0.3em]">KEAMANAN & KONTROL</p>
          <p class="text-sm font-medium opacity-80 leading-relaxed">Jamin keamanan data inventaris Anda dengan pencadangan basis data berkala ke penyimpanan aman.</p>
        </div>
        <div>
          <a href="{{ route('backup.run') }}" 
            class="inline-flex items-center justify-center gap-3 px-4 py-5 bg-white text-indigo-700 rounded-full font-black uppercase text-[11px] tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all w-full sm:w-auto">
            <i class="px-4 fas fa-cloud-arrow-up text-lg"></i> JALANKAN BACKUP
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- 7. Aksi Cepat Section (Mockup Match - Forced Single Row) -->
  <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-10">
    <div class="px-4 py-4">
      <h3 class="text-center py-2 text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Aksi Cepat</h3>
    </div>
    
    <div class="flex flex-row items-start justify-between gap-2">
      {{-- Barang Masuk --}}
      <a href="{{ route('stock.create', ['type' => 'in']) }}" class="flex flex-col items-center gap-3 group flex-1">
        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-emerald-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-plus-circle"></i>
        </div>
        <span class="text-[9px] font-black text-slate-700 uppercase tracking-widest text-center leading-tight">BARANG MASUK</span>
      </a>

      {{-- Barang Keluar --}}
      <a href="{{ route('stock.create', ['type' => 'out']) }}" class="flex flex-col items-center gap-3 group flex-1">
        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-rose-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-minus-circle"></i>
        </div>
        <span class="text-[9px] font-black text-slate-700 uppercase tracking-widest text-center leading-tight">BARANG KELUAR</span>
      </a>

      {{-- Cari Barang --}}
      <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-3 group flex-1">
        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-blue-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-search"></i>
        </div>
        <span class="text-[9px] font-black text-slate-700 uppercase tracking-widest text-center leading-tight">CARI BARANG</span>
      </a>

      {{-- Cetak --}}
      <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-3 group flex-1">
        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-purple-600 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-print"></i>
        </div>
        <span class="text-[9px] font-black text-slate-700 uppercase tracking-widest text-center leading-tight">CETAK</span>
      </a>

      {{-- Audit Log --}}
      <a href="{{ route('activity_log.index') }}" class="flex flex-col items-center gap-3 group flex-1">
        <div class="w-16 h-16 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-history"></i>
        </div>
        <span class="text-[9px] font-black text-slate-700 uppercase tracking-widest text-center leading-tight">AUDIT LOG</span>
      </a>
    </div>
  </div>

  <!-- 8. Recent Activity (Desktop Table/List Style) -->
  <div class="space-y-6">
    <div class="flex items-center justify-between px-2 mb-4">
      <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Aktivitas Gudang Hari Ini</h3>
      <a href="{{ route('stock.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Lihat Detail Riwayat</a>
    </div>
    <div class="space-y-4">
      @forelse($recentTransactions as $tx)
        <div class="p-6 rounded-[2rem] bg-white border border-slate-50 shadow-sm flex items-center justify-between group hover:border-indigo-100 transition-all">
          <div class="flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-md border border-slate-50
              {{ $tx->type == 'in' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
              <i class="fas {{ $tx->type == 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
            </div>
            <div class="min-w-0">
              <p class="text-lg font-black text-slate-800 truncate">{{ $tx->product?->name ?? 'Produk Dihapus' }}</p>
              <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1">
                <i class="far fa-clock mr-1 text-indigo-400"></i> {{ $tx->date->format('H:i') }} • 
                <span class="text-slate-500">{{ $tx->user->name ?? 'System' }}</span>
              </p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-2xl font-black {{ $tx->type == 'in' ? 'text-emerald-500' : 'text-rose-500' }}">
              {{ $tx->type == 'in' ? '+' : '−' }}{{ number_format($tx->quantity) }}
            </p>
            <p class="text-[10px] text-slate-300 font-extrabold uppercase tracking-widest leading-none">{{ $tx->product?->unit ?? 'Unit' }}</p>
          </div>
        </div>
      @empty
        <div class="p-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
          <div class="w-20 h-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">
            <i class="fas fa-inbox"></i>
          </div>
          <p class="text-xs font-black text-slate-300 uppercase tracking-widest italic">Belum ada aktivitas transaksi hari ini</p>
        </div>
      @endforelse
    </div>
  </div>

</div>
@endsection
