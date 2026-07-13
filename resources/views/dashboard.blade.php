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
  
  @if(auth()->user()->first_login && (!auth()->user()->password_updated_at || !auth()->user()->avatar_updated_at))
    {{-- Welcome Banner for Initial Setup --}}
    <div class="mb-8 animate__animated animate__fadeInDown">
      <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-100 relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="flex items-center gap-6 text-center md:text-left">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl">
              <i class="fas fa-rocket animate-bounce"></i>
            </div>
            <div class="space-y-1">
              <h3 class="text-xl font-black uppercase tracking-tight">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
              <p class="text-[11px] font-bold opacity-80 max-w-md leading-relaxed uppercase tracking-wider">
                Sandi akun Anda saat ini dibuat otomatis oleh sistem. Demi keamanan, silakan <span class="text-white underline decoration-white/40">Ganti Password</span> dan <span class="text-white underline decoration-white/40">Lengkapi Foto Profil</span> Anda.
              </p>
            </div>
          </div>
          <div class="flex items-center gap-3">
              <a href="{{ route('profile.edit') }}" class="px-8 py-3.5 bg-white text-indigo-700 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all">
                  Lengkapi Profil
              </a>
              <form action="{{ route('users.welcome.dismiss') }}" method="POST">
                  @csrf
                  <button type="submit" class="w-12 h-12 bg-indigo-700/50 hover:bg-indigo-700 text-white rounded-full flex items-center justify-center transition-all group">
                      <i class="fas fa-times group-hover:rotate-90 transition-transform"></i>
                  </button>
              </form>
          </div>
        </div>
      </div>
    </div>
  @endif
  
  <!-- 1. Welcome Header (Mobile Style) -->
  <div class="flex items-center justify-between bg-app-surface p-5 lg:p-8 rounded-[2rem] lg:rounded-[2.5rem] border border-app-main shadow-sm transition-colors duration-300">
    <div class="space-y-1">
      <h2 class="text-xl lg:text-3xl font-black text-app-main tracking-tight transition-colors duration-300">Hello, {{ Auth::user()->name }}!</h2>
      <p class="text-[10px] lg:text-sm text-app-muted font-black uppercase tracking-widest transition-colors duration-300">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
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
  <div class="p-5 lg:p-8 rounded-[2rem] lg:rounded-[2.5rem] bg-app-surface shadow-sm border border-app-main flex items-center justify-between group hover:border-amber-200/50 transition-all duration-300">
    <div class="flex items-center gap-4 lg:gap-8">
      <div class="w-14 h-14 lg:w-20 lg:h-20 rounded-2xl lg:rounded-[2rem] bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl lg:text-4xl group-hover:scale-110 transition-transform">
        <i class="fas fa-wallet"></i>
      </div>
      <div>
        <p class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-widest mb-1 transition-colors duration-300">Nilai Persediaan Keseluruhan</p>
        <p class="text-xl lg:text-4xl font-black text-app-main transition-colors duration-300">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</p>
      </div>
    </div>
    <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
      <i class="fas fa-chevron-right"></i>
    </div>
  </div>

  <!-- 4. Ringkasan Data (Grid 2x2 style from mobile but bigger) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all duration-300">
      <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-2xl">
        <i class="fas fa-boxes-stacked"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Total Barang</p>
        <p class="text-2xl font-black text-app-main transition-colors">{{ number_format($totalProducts) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all duration-300">
      <div class="w-14 h-14 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center text-2xl">
        <i class="fas fa-tags"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Jenis Belanja</p>
        <p class="text-2xl font-black text-app-main transition-colors">{{ number_format($totalCategories) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all duration-300">
      <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-2xl">
        <i class="fas fa-truck-fast"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Penyedia</p>
        <p class="text-2xl font-black text-app-main transition-colors">{{ number_format($supplierCount) }}</p>
      </div>
    </div>
    <div class="p-6 rounded-[2rem] bg-rose-500/5 dark:bg-rose-500/10 border border-rose-500/20 shadow-sm flex items-center gap-6 hover:translate-y-[-4px] transition-all duration-300">
      <div class="w-14 h-14 rounded-2xl bg-white dark:bg-rose-500 text-rose-500 dark:text-white flex items-center justify-center text-2xl shadow-sm shadow-rose-100 dark:shadow-none">
        <i class="fas fa-warning"></i>
      </div>
      <div>
        <p class="text-[10px] font-black text-rose-400 dark:text-rose-300 uppercase tracking-widest leading-none mb-1 transition-colors">Stok Rendah</p>
        <p class="text-2xl font-black text-rose-600 dark:text-rose-400 transition-colors">{{ number_format($lowStockCount) }}</p>
      </div>
    </div>
  </div>

  <!-- 5. Ringkasan Dokumen (Enhanced for desktop) -->
  <div class="space-y-6">
    <h3 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] lg:tracking-[0.3em] px-2 mb-4 transition-colors">Ringkasan Dokumen Berkas</h3>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
      @foreach([
        ['label' => 'Belanja Modal', 'count' => $belanjaModalCount, 'icon' => 'fa-file-contract', 'color' => 'indigo'],
        ['label' => 'Nota Pesanan', 'count' => $notaCount, 'icon' => 'fa-file-invoice', 'color' => 'violet'],
        ['label' => 'Pemeriksaan', 'count' => $pemeriksaanCount, 'icon' => 'fa-check-double', 'color' => 'teal'],
        ['label' => 'Penerimaan', 'count' => $penerimaanCount, 'icon' => 'fa-file-download', 'color' => 'emerald'],
        ['label' => 'Kwitansi', 'count' => $kwitansiCount, 'icon' => 'fa-receipt', 'color' => 'amber'],
        ['label' => 'BA Opname', 'count' => $opnameCount, 'icon' => 'fa-clipboard-check', 'color' => 'cyan'],
        ['label' => 'Pinjam Pakai', 'count' => $pinjamCount, 'icon' => 'fa-people-carry-box', 'color' => 'slate']
      ] as $doc)
        <div class="p-5 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4 group hover:border-indigo-500/30 transition-all duration-300">
          <div class="w-12 h-12 rounded-2xl bg-{{ $doc['color'] }}-500/10 text-{{ $doc['color'] }}-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
            <i class="fas {{ $doc['icon'] }}"></i>
          </div>
          <div>
            <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">{{ $doc['label'] }}</p>
            <p class="text-xl font-black text-app-main transition-colors">{{ number_format($doc['count'] ?? 0) }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 5b. Sebaran BTS -->
  <div class="space-y-6">
    <div class="flex items-center justify-between px-2 mb-4">
      <h3 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] lg:tracking-[0.3em] transition-colors">Sebaran Jaringan BTS</h3>
      <a href="{{ route('bts-towers.index') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline transition-colors">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
      <div class="p-5 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4 group hover:border-indigo-500/30 transition-all duration-300">
        <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
          <i class="fas fa-tower-cell"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Total BTS</p>
          <p class="text-xl font-black text-app-main transition-colors">{{ number_format($btsTotal) }}</p>
        </div>
      </div>
      <div class="p-5 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4 group hover:border-emerald-500/30 transition-all duration-300">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
          <i class="fas fa-signal"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Aktif</p>
          <p class="text-xl font-black text-emerald-500 transition-colors">{{ number_format($btsAktif) }}</p>
        </div>
      </div>
      <div class="p-5 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4 group hover:border-slate-400/30 transition-all duration-300">
        <div class="w-12 h-12 rounded-2xl bg-slate-500/10 text-slate-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
          <i class="fas fa-power-off"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Tidak Aktif</p>
          <p class="text-xl font-black text-slate-500 transition-colors">{{ number_format($btsTidakAktif) }}</p>
        </div>
      </div>
      <div class="p-5 rounded-3xl bg-app-surface border border-app-main shadow-sm flex items-center gap-4 group hover:border-amber-500/30 transition-all duration-300">
        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
          <i class="fas fa-wrench"></i>
        </div>
        <div>
          <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none mb-1 transition-colors">Maintenance</p>
          <p class="text-xl font-black text-amber-500 transition-colors">{{ number_format($btsMaintenance) }}</p>
        </div>
      </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Status Donut --}}
      <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
        <h4 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] mb-4 transition-colors">Status Operasional</h4>
        <div class="flex justify-center"><canvas id="btsStatusChart" style="max-height:200px;"></canvas></div>
      </div>

      {{-- Provider Bar --}}
      <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
        <h4 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] mb-4 transition-colors">BTS per Provider</h4>
        <div class="flex justify-center"><canvas id="btsProviderChart" style="max-height:200px;"></canvas></div>
      </div>

      {{-- Kecamatan Bar --}}
      <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
        <h4 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] mb-4 transition-colors">BTS per Kecamatan</h4>
        <div style="max-height:200px;overflow-y:auto;"><canvas id="btsKecamatanChart"></canvas></div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- BTS by Provider (text) --}}
      <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
        <h4 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] mb-4 transition-colors">Detail Provider</h4>
        <div class="space-y-3">
          @forelse($btsByProvider as $item)
            @php
              $pct = $btsTotal > 0 ? ($item->total / $btsTotal) * 100 : 0;
            @endphp
            <div class="space-y-1.5">
              <div class="flex justify-between items-center">
                <span class="text-[10px] font-black text-app-main uppercase tracking-wider transition-colors">{{ $item->provider }}</span>
                <span class="text-[10px] font-black text-app-muted transition-colors">{{ $item->total }} <span class="text-app-muted opacity-60">({{ number_format($pct, 0) }}%)</span></span>
              </div>
              <div class="h-2 w-full bg-app-bg rounded-full overflow-hidden border border-app-main transition-colors">
                <div class="h-full bg-indigo-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          @empty
            <p class="text-[10px] text-app-muted font-bold uppercase tracking-widest">Belum ada data BTS</p>
          @endforelse
        </div>
      </div>

      {{-- BTS by Kecamatan (text) --}}
      <div class="p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
        <h4 class="text-[10px] lg:text-xs font-black text-app-muted uppercase tracking-[0.2em] mb-4 transition-colors">Detail Kecamatan</h4>
        <div class="space-y-3">
          @forelse($btsByKecamatan as $item)
            @php
              $pct = $btsTotal > 0 ? ($item->total / $btsTotal) * 100 : 0;
            @endphp
            <div class="space-y-1.5">
              <div class="flex justify-between items-center">
                <span class="text-[10px] font-black text-app-main uppercase tracking-wider transition-colors">{{ $item->kecamatan }}</span>
                <span class="text-[10px] font-black text-app-muted transition-colors">{{ $item->total }} <span class="text-app-muted opacity-60">({{ number_format($pct, 0) }}%)</span></span>
              </div>
              <div class="h-2 w-full bg-app-bg rounded-full overflow-hidden border border-app-main transition-colors">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
              </div>
            </div>
          @empty
            <p class="text-[10px] text-app-muted font-bold uppercase tracking-widest">Belum ada data BTS</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- Chart.js for BTS --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
  <script>
    (function() {
      const providerColors = {
        'Telkomsel': '#e74c3c', 'Indosat': '#f39c12', 'XL Axiata': '#3498db',
        'Tri (3)': '#9b59b6', 'Smartfren': '#2ecc71', 'Lainnya': '#95a5a6'
      };
      const statusColors = { 'Aktif': '#34d399', 'Tidak Aktif': '#94a3b8', 'Maintenance': '#fbbf24' };

      // Status Donut
      const statusData = @json($btsChartData);
      new Chart(document.getElementById('btsStatusChart'), {
        type: 'doughnut',
        data: {
          labels: statusData.map(d => d.label),
          datasets: [{
            data: statusData.map(d => d.value),
            backgroundColor: statusData.map(d => statusColors[d.label] || '#6b7280'),
            borderWidth: 2,
            borderColor: 'rgba(255,255,255,0.05)',
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: true, cutout: '65%',
          plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af', font: { size: 10, weight: 'bold' }, padding: 12 } } }
        }
      });

      // Provider Bar
      const provLabels = @json($btsChartLabels);
      const provData = @json($btsChartValues);
      new Chart(document.getElementById('btsProviderChart'), {
        type: 'bar',
        data: {
          labels: provLabels,
          datasets: [{
            data: provData,
            backgroundColor: provLabels.map(p => providerColors[p] || '#95a5a6'),
            borderRadius: 6, barThickness: 28,
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: true, indexAxis: 'y',
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', font: { size: 10 } } },
            y: { grid: { display: false }, ticks: { color: '#d1d5db', font: { size: 10, weight: 'bold' } } }
          }
        }
      });

      // Kecamatan Bar
      const kecLabels = @json($btsKecLabels);
      const kecData = @json($btsKecValues);
      new Chart(document.getElementById('btsKecamatanChart'), {
        type: 'bar',
        data: {
          labels: kecLabels,
          datasets: [{
            data: kecData,
            backgroundColor: '#818cf8',
            borderRadius: 6, barThickness: 22,
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: true,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#d1d5db', font: { size: 9, weight: 'bold' }, maxRotation: 45 } },
            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af', font: { size: 10 } } }
          }
        }
      });
    })();
  </script>

  <!-- 6. Analisis Performa & Control System (Side by side for desktop) -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    {{-- Tren Performa --}}
    <div class="p-10 rounded-[2.5rem] bg-app-surface border border-app-main shadow-sm transition-colors duration-300">
      <div class="flex items-center justify-between mb-8">
        <h3 class="text-sm font-black text-app-main uppercase tracking-[0.2em] transition-colors">Analisis Performa (Bulanan)</h3>
        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-500 text-[10px] font-black rounded-full uppercase tracking-widest">3 Bulan Terakhir</span>
      </div>
      <div class="space-y-6">
        @foreach($monthlyLabels->take(-3) as $idx => $lbl)
          <div class="space-y-3">
            <div class="flex justify-between items-end">
              <div>
                <span class="text-[11px] font-black text-app-main uppercase tracking-tighter transition-colors">{{ $lbl }}</span>
              </div>
              <div class="text-right">
                <span class="text-[10px] font-bold text-app-muted uppercase tracking-widest transition-colors">
                  In: <span class="text-indigo-500">{{ number_format($monthlyIn[$idx]) }}</span> / 
                  Out: <span class="text-rose-500">{{ number_format($monthlyOut[$idx]) }}</span>
                </span>
              </div>
            </div>
            <div class="h-3 w-full bg-app-bg rounded-full overflow-hidden flex border border-app-main transition-colors">
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

    @if(auth()->user()->isAdmin())
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
          <a href="{{ route('backups.index') }}" 
            class="inline-flex items-center justify-center gap-3 px-4 py-5 bg-white text-indigo-700 rounded-full font-black uppercase text-[11px] tracking-[0.2em] shadow-xl hover:scale-105 active:scale-95 transition-all w-full sm:w-auto">
            <i class="px-4 fas fa-cloud-arrow-up text-lg"></i> MANAJEMEN BACKUP
          </a>
        </div>
      </div>
    </div>
    @endif
  </div>

  <!-- 7. Aksi Cepat Section (Mockup Match - Forced Single Row) -->
  <div class="bg-app-surface p-5 lg:p-10 rounded-[2rem] lg:rounded-[2.5rem] border border-app-main shadow-sm space-y-6 lg:space-y-10 transition-colors duration-300">
    <div class="px-4 py-2 lg:py-4">
      <h3 class="text-center py-2 text-[10px] lg:text-sm font-black text-app-main uppercase tracking-[0.2em] transition-colors">Aksi Cepat</h3>
    </div>
    
    <div class="flex flex-row flex-wrap items-center justify-between px-2 lg:px-10">
      {{-- Barang Masuk --}}
      <a href="{{ route('stock.create', ['type' => 'in']) }}" class="flex flex-col items-center gap-3 group">
        <div class="w-16 h-16 rounded-full bg-app-bg border border-app-main shadow-sm flex items-center justify-center text-emerald-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-plus-circle"></i>
        </div>
        <span class="text-[9px] font-black text-app-main uppercase tracking-widest text-center leading-tight transition-colors">BARANG MASUK</span>
      </a>

      {{-- Barang Keluar --}}
      <a href="{{ route('stock.create', ['type' => 'out']) }}" class="flex flex-col items-center gap-3 group">
        <div class="w-16 h-16 rounded-full bg-app-bg border border-app-main shadow-sm flex items-center justify-center text-rose-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-minus-circle"></i>
        </div>
        <span class="text-[9px] font-black text-app-main uppercase tracking-widest text-center leading-tight transition-colors">BARANG KELUAR</span>
      </a>

      {{-- Cari Barang --}}
      <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-3 group">
        <div class="w-16 h-16 rounded-full bg-app-bg border border-app-main shadow-sm flex items-center justify-center text-blue-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-search"></i>
        </div>
        <span class="text-[9px] font-black text-app-main uppercase tracking-widest text-center leading-tight transition-colors">CARI BARANG</span>
      </a>

      {{-- Cetak --}}
      <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-3 group">
        <div class="w-16 h-16 rounded-full bg-app-bg border border-app-main shadow-sm flex items-center justify-center text-purple-600 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-print"></i>
        </div>
        <span class="text-[9px] font-black text-app-main uppercase tracking-widest text-center leading-tight transition-colors">CETAK</span>
      </a>

      {{-- Audit Log --}}
      <a href="{{ route('activity_log.index') }}" class="flex flex-col items-center gap-3 group">
        <div class="w-16 h-16 rounded-full bg-app-bg border border-app-main shadow-sm flex items-center justify-center text-slate-500 text-2xl group-hover:scale-110 group-hover:shadow-md transition-all duration-300">
          <i class="fas fa-history"></i>
        </div>
        <span class="text-[9px] font-black text-app-main uppercase tracking-widest text-center leading-tight transition-colors">AUDIT LOG</span>
      </a>
    </div>
  </div>

  <!-- 8. Recent Activity (Desktop Table/List Style) -->
  <div class="space-y-6">
    <div class="flex items-center justify-between px-2 mb-4">
      <h3 class="text-sm font-black text-app-main uppercase tracking-[0.2em] transition-colors">Aktivitas Gudang Hari Ini</h3>
      <a href="{{ route('stock.index') }}" class="text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline transition-colors">Lihat Detail Riwayat</a>
    </div>
    <div class="space-y-4">
      @forelse($recentTransactions as $tx)
        <div class="p-4 lg:p-6 rounded-[2rem] bg-app-surface border border-app-main shadow-sm flex items-center justify-between group hover:border-indigo-500/30 transition-all duration-300">
          <div class="flex items-center gap-4 lg:gap-6">
            <div class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl lg:rounded-2xl flex items-center justify-center text-sm lg:text-xl shadow-md border border-app-main
              {{ $tx->type == 'in' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
              <i class="fas {{ $tx->type == 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
            </div>
            <div class="min-w-0">
              <p class="text-sm lg:text-lg font-black text-app-main truncate transition-colors">{{ $tx->product?->name ?? 'Produk Dihapus' }}</p>
              <p class="text-[10px] text-app-muted font-black uppercase tracking-widest mt-1 transition-colors">
                <i class="far fa-clock mr-1 text-indigo-400"></i> {{ $tx->date->format('H:i') }} • 
                <span class="text-app-muted opacity-80 transition-colors">{{ $tx->user->name ?? 'System' }}</span>
              </p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-2xl font-black {{ $tx->type == 'in' ? 'text-emerald-500' : 'text-rose-500' }}">
              {{ $tx->type == 'in' ? '+' : '−' }}{{ number_format($tx->quantity) }}
            </p>
            <p class="text-[10px] text-app-muted font-extrabold uppercase tracking-widest leading-none transition-colors">{{ $tx->product?->unit ?? 'Unit' }}</p>
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
