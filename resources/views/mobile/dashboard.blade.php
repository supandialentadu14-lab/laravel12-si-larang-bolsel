@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up">

  @if(auth()->user()->first_login && (!auth()->user()->password_updated_at || !auth()->user()->avatar_updated_at))
    {{-- Mobile First Login Banner --}}
    <div class="animate__animated animate__fadeInDown">
      <div class="bg-indigo-600 rounded-[2rem] p-6 text-white shadow-lg shadow-indigo-100 relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 space-y-4">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl">
              <i class="fas fa-rocket animate-pulse"></i>
            </div>
            <div>
              <h3 class="text-lg font-black tracking-tight">Halo, Selamat Datang!</h3>
              <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest">SI-LARANG Bolsel</p>
            </div>
          </div>
          <p class="text-[10px] font-bold leading-relaxed uppercase tracking-wider bg-black/10 p-3 rounded-2xl border border-white/10">
            Demi keamanan, silakan segera <span class="text-white underline">Lengkapi Foto Profil</span> dan <span class="text-white underline">Perbarui Password</span> Anda.
          </p>
          <div class="flex gap-2">
            <a href="{{ route('profile.edit') }}" class="flex-1 py-3.5 bg-white text-indigo-700 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
                Lengkapi Profil
            </a>
            <form action="{{ route('users.welcome.dismiss') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="w-12 h-12 bg-indigo-700/50 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
  <!-- Welcome Header -->
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-extrabold text-app-main tracking-tight">Halo, {{ Auth::user()->name }}!</h2>
      <p class="text-xs text-app-muted font-medium tracking-wide">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-500 text-white shadow-lg shadow-indigo-100 relative overflow-hidden flex items-center justify-center">
      <div class="absolute -top-3 -right-3 w-12 h-12 bg-white/20 rounded-full blur-xl"></div>
      <div class="absolute -bottom-5 -left-5 w-16 h-16 bg-white/10 rounded-full blur-2xl"></div>
      <div class="relative">
        <i class="fas fa-cubes-stacked text-lg"></i>
        @if (isset($lowStockCount) && $lowStockCount > 0)
          <span class="absolute -top-1.5 -right-2 min-w-4 h-4 px-1 rounded-full bg-red-500 text-white text-[8px] font-black flex items-center justify-center ring-2 ring-white">
            {{ $lowStockCount }}
          </span>
        @endif
      </div>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="grid grid-cols-2 gap-4">
    <div class="p-4 rounded-3xl bg-indigo-600 text-white shadow-xl shadow-indigo-100 flex flex-col justify-between h-32 relative overflow-hidden">
      <div class="absolute -right-2 -top-2 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
      <i class="fas fa-box text-xl opacity-40"></i>
      <div>
        <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Total Stok</p>
        <p class="text-2xl font-black">{{ number_format($totalStock) }}</p>
      </div>
    </div>
    <div class="p-4 rounded-3xl bg-emerald-500 text-white shadow-xl shadow-emerald-100 flex flex-col justify-between h-32 relative overflow-hidden">
      <div class="absolute -right-2 -top-2 w-16 h-16 bg-white/10 rounded-full blur-xl"></div>
      <i class="fas fa-arrow-down text-xl opacity-40"></i>
      <div>
        <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Masuk (Hari Ini)</p>
        <p class="text-2xl font-black">+{{ $inToday }}</p>
      </div>
    </div>
  </div>

  <!-- Inventory Valuation -->
  <div class="p-5 rounded-3xl glass-card shadow-sm border border-app-main flex items-center justify-between">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
        <i class="fas fa-wallet"></i>
      </div>
      <div>
        <p class="text-[10px] font-bold text-app-muted uppercase tracking-widest">Nilai Persediaan</p>
        <p class="text-lg font-black text-app-main">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</p>
      </div>
    </div>
    <i class="fas fa-chevron-right text-gray-300"></i>
  </div>

  <!-- Ringkasan Data -->
  <div class="grid grid-cols-2 gap-4">
    <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
      <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
        <i class="fas fa-boxes"></i>
      </div>
      <div>
        <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Barang</p>
        <p class="text-xl font-black text-app-main">{{ number_format($totalProducts) }}</p>
      </div>
    </div>
    <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
      <div class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 flex items-center justify-center">
        <i class="fas fa-tags"></i>
      </div>
      <div>
        <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Kategori</p>
        <p class="text-xl font-black text-app-main">{{ number_format($totalCategories) }}</p>
      </div>
    </div>
    <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
      <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
        <i class="fas fa-truck"></i>
      </div>
      <div>
        <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Penyedia</p>
        <p class="text-xl font-black text-app-main">{{ number_format($supplierCount) }}</p>
      </div>
    </div>
    <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
      <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 flex items-center justify-center">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div>
        <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Stok Rendah</p>
        <p class="text-xl font-black text-app-main">{{ number_format($lowStockCount) }}</p>
      </div>
    </div>
  </div>

  <!-- Ringkasan Dokumen -->
  <div>
    <h3 class="text-sm font-extrabold text-app-main mb-4 px-1 uppercase tracking-widest">Ringkasan Dokumen</h3>
    <div class="grid grid-cols-2 gap-4">
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-violet-50 dark:bg-violet-900/20 text-violet-600 dark:text-violet-400 flex items-center justify-center">
          <i class="fas fa-file-contract"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Belanja Modal</p>
          <p class="text-xl font-black text-app-main">{{ number_format($belanjaModalCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-fuchsia-50 dark:bg-fuchsia-900/20 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center">
          <i class="fas fa-file-invoice"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Nota Pesanan</p>
          <p class="text-xl font-black text-app-main">{{ number_format($notaCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 flex items-center justify-center">
          <i class="fas fa-check-double"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Pemeriksaan</p>
          <p class="text-xl font-black text-app-main">{{ number_format($pemeriksaanCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
          <i class="fas fa-file-download"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Penerimaan</p>
          <p class="text-xl font-black text-app-main">{{ number_format($penerimaanCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
          <i class="fas fa-receipt"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Kwitansi</p>
          <p class="text-xl font-black text-app-main">{{ number_format($kwitansiCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
          <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">BA Opname</p>
          <p class="text-xl font-black text-app-main">{{ number_format($opnameCount ?? 0) }}</p>
        </div>
      </div>
      <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-900/20 text-slate-600 dark:text-slate-400 flex items-center justify-center">
          <i class="fas fa-people-carry-box"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest">Pinjam Pakai</p>
          <p class="text-xl font-black text-app-main">{{ number_format($pinjamCount ?? 0) }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Performance Analytics -->
  <div>
    <h3 class="text-sm font-extrabold text-app-main mb-4 px-1 uppercase tracking-widest">Analisis Performa</h3>
    <div class="p-6 rounded-[2.5rem] bg-app-surface border border-app-main shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <p class="text-[10px] font-black text-app-muted uppercase tracking-widest">Tren 6 Bulan</p>
        <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[9px] font-black rounded-full uppercase">Update Realtime</span>
      </div>
      <div class="space-y-4">
        @foreach($monthlyLabels->take(-3) as $idx => $lbl)
        <div class="space-y-2">
            <div class="flex justify-between text-[10px] font-bold uppercase tracking-tight">
                <span class="text-app-main">{{ $lbl }}</span>
                <span class="text-indigo-600">{{ number_format($monthlyIn[$idx]) }} In / {{ number_format($monthlyOut[$idx]) }} Out</span>
            </div>
            <div class="h-1.5 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden flex">
                @php
                    $total = $monthlyIn[$idx] + $monthlyOut[$idx];
                    $pIn = $total > 0 ? ($monthlyIn[$idx] / $total) * 100 : 0;
                    $pOut = $total > 0 ? ($monthlyOut[$idx] / $total) * 100 : 0;
                @endphp
                <div class="h-full bg-indigo-500" style="width: {{ $pIn }}%"></div>
                <div class="h-full bg-rose-400" style="width: {{ $pOut }}%"></div>
            </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  @if(auth()->user()->isAdmin())
  <!-- System Control -->
  <div class="pb-10">
    <h3 class="text-sm font-extrabold text-app-main mb-4 px-1 uppercase tracking-widest">Kontrol Sistem</h3>
    <div class="p-6 rounded-[2.5rem] bg-indigo-600 shadow-xl shadow-indigo-100 dark:shadow-none text-white overflow-hidden relative">
        <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
        <div class="relative flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-black uppercase tracking-widest">Keamanan Data</p>
                <p class="text-[9px] font-medium opacity-80 uppercase leading-relaxed">Cek dan buat cadangan <br>database secara rutin.</p>
            </div>
            <a href="{{ route('backups.index') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                <i class="fas fa-server mr-2"></i> Kelola
            </a>
        </div>
    </div>
  </div>
  @endif

  <!-- Quick Actions Scroll -->
  <div>
    <h3 class="text-sm font-extrabold text-gray-900 mb-4 px-1 uppercase tracking-widest">Aksi Cepat</h3>
    <div class="flex gap-4 overflow-x-auto no-scrollbar pb-2">
      <a href="{{ route('stock.create', ['type' => 'in']) }}" class="flex-shrink-0 w-24 flex flex-col items-center gap-2">
        <div class="btn-icon-mini bg-app-surface shadow-sm border border-app-main text-green-500 text-xl">
          <i class="fas fa-plus-circle"></i>
        </div>
        <span class="text-[10px] font-bold text-app-muted uppercase">Barang Masuk</span>
      </a>
      <a href="{{ route('stock.create', ['type' => 'out']) }}" class="flex-shrink-0 w-24 flex flex-col items-center gap-2">
        <div class="btn-icon-mini bg-app-surface shadow-sm border border-app-main text-red-500 text-xl">
          <i class="fas fa-minus-circle"></i>
        </div>
        <span class="text-[10px] font-bold text-app-muted uppercase">Barang Keluar</span>
      </a>
      <a href="{{ route('stock.index') }}" class="flex-shrink-0 w-24 flex flex-col items-center gap-2">
        <div class="btn-icon-mini bg-app-surface shadow-sm border border-app-main text-blue-500 text-xl">
          <i class="fas fa-search"></i>
        </div>
        <span class="text-[10px] font-bold text-app-muted uppercase">Cari Barang</span>
      </a>
      <a href="{{ route('reports.index') }}" class="flex-shrink-0 w-24 flex flex-col items-center gap-2">
        <div class="btn-icon-mini bg-app-surface shadow-sm border border-app-main text-purple-500 text-xl">
          <i class="fas fa-print"></i>
        </div>
        <span class="text-[10px] font-bold text-app-muted uppercase">Cetak</span>
      </a>
      @if(auth()->user()->isAdmin())
      <a href="{{ route('activity_log.index') }}" class="flex-shrink-0 w-24 flex flex-col items-center gap-2">
        <div class="btn-icon-mini bg-app-surface shadow-sm border border-app-main text-slate-500 text-xl">
          <i class="fas fa-history"></i>
        </div>
        <span class="text-[10px] font-bold text-app-muted uppercase">Audit Log</span>
      </a>
      @endif
    </div>
  </div>

  <!-- Recent Transactions -->
  <div>
    <div class="flex items-center justify-between mb-4 px-1">
      <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-widest">Aktivitas Terkini</h3>
      <a href="{{ route('stock.index') }}" class="btn-mini btn-mini-outline !px-3 !py-1.5 !rounded-xl !text-[9px]">Lihat Semua</a>
    </div>
    <div class="space-y-3">
      @forelse($recentTransactions as $tx)
        <div class="p-4 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center justify-between active:scale-[0.98] transition">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl {{ $tx->type == 'in' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }} flex items-center justify-center text-sm">
              <i class="fas {{ $tx->type == 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
            </div>
            <div class="min-w-0">
              <p class="text-sm font-bold text-app-main truncate">{{ $tx->product?->name ?? '-' }}</p>
              <p class="text-[10px] text-app-muted font-medium uppercase tracking-wider">{{ $tx->date->format('H:i') }} • {{ $tx->user->name ?? 'Admin' }}</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm font-black {{ $tx->type == 'in' ? 'text-emerald-500' : 'text-rose-500' }}">
              {{ $tx->type == 'in' ? '+' : '-' }}{{ number_format($tx->quantity) }}
            </p>
            <p class="text-[9px] text-app-muted font-bold uppercase tracking-tighter">{{ $tx->product?->unit ?? 'Unit' }}</p>
          </div>
        </div>
      @empty
        <div class="p-10 text-center text-gray-300">
          <i class="fas fa-inbox text-4xl mb-3"></i>
          <p class="text-xs font-bold uppercase tracking-widest">Belum ada aktivitas</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
