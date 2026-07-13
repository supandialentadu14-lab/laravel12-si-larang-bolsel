<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SIMPATI</title>
  <link rel="icon" type="image/png" href="{{ asset('images/simpatis.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="preload" href="{{ asset('images/simpatis.png') }}" as="image" type="image/png">
  <link rel="preload" href="{{ asset('images/login-bg-neww.png') }}" as="image" type="image/png">
  @vite(['resources/css/landing.css', 'resources/js/landing.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; text-rendering: optimizeSpeed; }
    .hero-gradient { background: linear-gradient(to right, #4f46e5, #9333ea); -webkit-background-clip: text; color: transparent; }
    .hero-gradient-bts { background: linear-gradient(to right, #f59e0b, #ef4444); -webkit-background-clip: text; color: transparent; }
    #home { min-height: 100vh; display: flex; align-items: center; }
    nav { position: fixed; width: 100%; z-index: 50; top: 0; }
    .leaflet-container { z-index: 1; }
    .leaflet-tile-pane { z-index: 1; }
    #landing-map { position: relative; z-index: 1; }
    .stat-bar { height: 8px; border-radius: 4px; transition: width 1s ease; }
    @media (max-width: 767px) {
      .animate-blob, .blur-[120px], [class*="animate-blob"], .animate__animated { display: none !important; animation: none !important; transition: none !important; }
      .hero-image-container { transform: none !important; }
      nav { background: rgba(255,255,255,0.9) !important; backdrop-filter: none !important; }
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-900 selection:bg-indigo-100 selection:text-indigo-900" x-data="{ mobileMenu: false }">

  <!-- Navbar -->
  <nav class="fixed top-0 w-full z-50 transition-all duration-300"
    :class="window.pageYOffset > 20 ? 'glass py-3 shadow-sm' : 'bg-transparent py-6'"
    @scroll.window="window.pageYOffset > 20 ? $el.classList.add('glass', 'py-3', 'shadow-sm') : $el.classList.remove('glass', 'py-3', 'shadow-sm'); $el.classList.contains('glass') ? '' : $el.classList.add('py-6')">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/simpatis.png') }}" alt="Logo" class="h-10 w-10 object-contain" width="40" height="40" decoding="async" fetchpriority="high">
        <span class="text-xl font-black tracking-tighter text-indigo-900">SIMPATI</span>
      </div>
      <div class="hidden md:flex items-center gap-8">
        <a href="#home" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Beranda</a>
        <a href="#services" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Layanan</a>
        <a href="#bts" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Sebaran BTS</a>
        <a href="#about" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Tentang</a>
        <a href="#contact" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Kontak</a>
        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-full shadow-lg shadow-indigo-200 hover:bg-slate-800 transition transform hover:-translate-y-0.5">MASUK</a>
      </div>
      <button class="md:hidden text-slate-800" @click="mobileMenu = !mobileMenu"><i class="fas fa-bars text-2xl"></i></button>
    </div>
    <div class="md:hidden bg-white/95 backdrop-blur-xl absolute top-full left-0 w-full border-b border-slate-100 py-6 px-6 space-y-4"
      x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
      <a href="#home" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Beranda</a>
      <a href="#services" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Layanan</a>
      <a href="#bts" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Sebaran BTS</a>
      <a href="#about" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Tentang</a>
      <a href="#contact" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Kontak</a>
      <a href="{{ route('login') }}" class="block w-full py-4 bg-indigo-600 text-white text-center font-black rounded-2xl shadow-lg">MASUK</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-100/40 rounded-full filter blur-[120px] animate-blob z-0"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-100/40 rounded-full filter blur-[120px] animate-blob animation-delay-2000 z-0"></div>

    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto relative z-10 grid md:grid-cols-2 gap-12 items-center">
      <div class="animate__animated animate__fadeInLeft">
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-[1.1] tracking-tight mb-6">
          Kelola Persediaan <br>& Pantau <span class="hero-gradient-bts">Jaringan Telekomunikasi.</span>
        </h1>
        <div class="flex items-center gap-6 mb-6">
          <img src="{{ asset('images/bolsel.webp') }}" alt="Bolsel" class="h-16 w-auto contrast-125 opacity-70" width="150" height="64" loading="lazy" decoding="async">
          <div class="h-10 w-px bg-slate-200"></div>
          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mt-1">Pemerintah Kabupaten</p>
            <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Bolaang Mongondow Selatan</p>
          </div>
        </div>
        <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10 max-w-lg">
           Platform digital terpadu untuk manajemen persediaan barang daerah dan pemetaan jaringan telekomunikasi di Kabupaten Bolaang Mongondow Selatan.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('login') }}" class="px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-slate-800 transition transform hover:-translate-y-1">
            Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="#bts" class="px-8 py-4 bg-white text-slate-800 font-black rounded-2xl border border-slate-100 shadow-xl shadow-slate-100 hover:bg-slate-50 transition transform hover:-translate-y-1">
            Lihat Peta BTS <i class="fas fa-map-marker-alt ml-2 text-red-500"></i>
          </a>
        </div>
      </div>
      <div class="relative animate__animated animate__fadeInRight hero-image-container">
        <div class="relative w-full max-w-lg mx-auto">
          <div class="absolute inset-0 bg-indigo-600/5 rounded-[1.5rem] md:rounded-[4rem] rotate-3 md:rotate-6 scale-95 hidden md:block"></div>
          <div class="absolute inset-0 bg-indigo-600/10 rounded-[1.5rem] md:rounded-[4rem] -rotate-2 md:-rotate-3 scale-95 hidden md:block"></div>
          <div class="relative bg-white rounded-[1.5rem] md:rounded-[4rem] shadow-2xl border border-slate-50 overflow-hidden">
            <img src="{{ asset('images/login-bg-neww.png') }}" class="w-full h-auto block" fetchpriority="high" width="800" height="600" decoding="async">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-20 bg-white relative">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto">
      <div class="text-center mb-14">
        <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-3">Layanan Kami</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Efisien. Akurat. Terintegrasi.</h3>
        <p class="text-slate-400 font-medium text-sm mt-3 max-w-lg mx-auto">Semua yang Anda butuhkan untuk mengelola persediaan dan memantau infrastruktur BTS dalam satu platform.</p>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-5">
        <div class="group text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-100 hover:bg-white hover:shadow-xl transition duration-300">
          <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 group-hover:text-white transition duration-300">
            <i class="fas fa-boxes-stacked text-xl text-indigo-500 group-hover:text-white transition duration-300"></i>
          </div>
          <h4 class="text-xs font-extrabold text-slate-900 mb-1.5 uppercase tracking-tight">Manajemen Stok</h4>
          <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">Pantau ketersediaan barang real-time.</p>
        </div>
        <div class="group text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-emerald-100 hover:bg-white hover:shadow-xl transition duration-300">
          <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
            <i class="fas fa-file-invoice text-xl text-emerald-500 group-hover:text-white transition duration-300"></i>
          </div>
          <h4 class="text-xs font-extrabold text-slate-900 mb-1.5 uppercase tracking-tight">Pelaporan</h4>
          <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">Laporan persediaan otomatis & siap cetak.</p>
        </div>
        <div class="group text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-amber-100 hover:bg-white hover:shadow-xl transition duration-300">
          <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-600 group-hover:text-white transition duration-300">
            <i class="fas fa-file-signature text-xl text-amber-500 group-hover:text-white transition duration-300"></i>
          </div>
          <h4 class="text-xs font-extrabold text-slate-900 mb-1.5 uppercase tracking-tight">Berita Acara</h4>
          <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">Cetak dokumen administrasi 1 klik.</p>
        </div>
        <div class="group text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-red-100 hover:bg-white hover:shadow-xl transition duration-300">
          <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-red-600 group-hover:text-white transition duration-300">
            <i class="fas fa-tower-cell text-xl text-red-500 group-hover:text-white transition duration-300"></i>
          </div>
          <h4 class="text-xs font-extrabold text-slate-900 mb-1.5 uppercase tracking-tight">Peta BTS</h4>
          <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">Pemetaan BTS 7 kecamatan Bolsel.</p>
        </div>
        <div class="group text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-purple-100 hover:bg-white hover:shadow-xl transition duration-300">
          <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-600 group-hover:text-white transition duration-300">
            <i class="fas fa-users-gear text-xl text-purple-500 group-hover:text-white transition duration-300"></i>
          </div>
          <h4 class="text-xs font-extrabold text-slate-900 mb-1.5 uppercase tracking-tight">Kolaborasi</h4>
          <p class="text-[10px] text-slate-400 font-semibold leading-relaxed">Multi-user koordinasi antar bidang.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Sebaran BTS Section -->
  <section id="bts" class="py-24 bg-slate-50">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto">
      <div class="text-center mb-16">
        <h2 class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-3">Pemetaan Jaringan</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Sebaran BTS Kab. Bolsel</h3>
        <p class="text-slate-500 font-medium mt-4 max-w-2xl mx-auto">Pemetaan Base Transceiver Station (BTS) di seluruh wilayah Kabupaten Bolaang Mongondow Selatan berdasarkan data provider telekomunikasi.</p>
      </div>

      {{-- Stat Cards --}}
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center hover:shadow-lg transition">
          <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl"><i class="fas fa-tower-cell"></i></div>
          <p class="text-3xl font-black text-slate-900">{{ number_format($btsTotal) }}</p>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total BTS</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center hover:shadow-lg transition">
          <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl"><i class="fas fa-signal"></i></div>
          <p class="text-3xl font-black text-emerald-600">{{ number_format($btsAktif) }}</p>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Aktif</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center hover:shadow-lg transition">
          <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl"><i class="fas fa-map-location-dot"></i></div>
          <p class="text-3xl font-black text-blue-600">7</p>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kecamatan</p>
        </div>
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center hover:shadow-lg transition">
          <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl"><i class="fas fa-network-wired"></i></div>
          <p class="text-3xl font-black text-amber-600">{{ $btsProvider->count() }}</p>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Provider</p>
        </div>
      </div>

      {{-- Peta Full Width --}}
      <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 mb-10">
        <div class="px-8 pt-8 pb-4">
          <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest"><i class="fas fa-map-marked-alt text-red-500 mr-2"></i>Peta Sebaran BTS</h4>
        </div>
        <div id="landing-map" style="height:480px; margin:0 1rem 1rem 1rem; border-radius:1rem; border:1px solid #f1f5f9;"></div>
      </div>

      {{-- Kecamatan + Provider Side by Side --}}
      <div class="grid md:grid-cols-2 gap-8">
        {{-- Per Kecamatan --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
          <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6"><i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>Per Kecamatan</h4>
          <div class="space-y-3">
            @forelse($btsKecamatan as $item)
              @php $pct = $btsTotal > 0 ? ($item->total / $btsTotal) * 100 : 0; @endphp
              <div>
                <div class="flex justify-between mb-1">
                  <span class="text-[11px] font-bold text-slate-600 uppercase">{{ $item->kecamatan }}</span>
                  <span class="text-[11px] font-black text-slate-900">{{ $item->total }}</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                  <div class="stat-bar bg-indigo-500" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            @empty
              <p class="text-xs text-slate-400 font-bold text-center py-4">Belum ada data</p>
            @endforelse
          </div>
        </div>

        {{-- Per Provider --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
          <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-6"><i class="fas fa-tower-cell text-red-500 mr-2"></i>Per Provider</h4>
          <div class="space-y-3">
            @php $providerColors = ['Telkomsel'=>'#e74c3c','Indosat'=>'#f39c12','XL Axiata'=>'#3498db','Tri (3)'=>'#9b59b6','Smartfren'=>'#2ecc71','Lainnya'=>'#95a5a6']; @endphp
            @forelse($btsProvider as $item)
              @php
                $pct = $btsTotal > 0 ? ($item->total / $btsTotal) * 100 : 0;
                $color = $providerColors[$item->provider] ?? '#95a5a6';
              @endphp
              <div>
                <div class="flex justify-between mb-1">
                  <span class="text-[11px] font-bold text-slate-600 uppercase flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:{{ $color }}"></span>{{ $item->provider }}
                  </span>
                  <span class="text-[11px] font-black text-slate-900">{{ $item->total }}</span>
                </div>
                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                  <div class="stat-bar" style="width: {{ $pct }}%; background: {{ $color }};"></div>
                </div>
              </div>
            @empty
              <p class="text-xs text-slate-400 font-bold text-center py-4">Belum ada data</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-20 bg-slate-50">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto">
      <div class="text-center mb-14">
        <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-3">Tentang Kami</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Menuju Bolsel Digital</h3>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
        <div>
          <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 rounded-full px-4 py-1.5 mb-5">
            <i class="fas fa-building text-[10px]"></i>
            <span class="text-[10px] font-black uppercase tracking-widest">Pemerintah Kab. Bolsel</span>
          </div>
          <h4 class="text-2xl font-black text-slate-900 tracking-tight mb-4 leading-tight">Persediaan & Pemetaan BTS dalam Satu Platform</h4>
          <p class="text-sm text-slate-500 font-medium leading-relaxed mb-4">
            SIMPATI menggabungkan manajemen persediaan barang milik daerah dengan pemetaan jaringan telekomunikasi di Kabupaten Bolaang Mongondow Selatan.
          </p>
          <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
            Setiap OPD dapat melakukan input, monitoring, dan pelaporan barang secara transparan, sekaligus memantau kondisi infrastruktur telekomunikasi di 7 kecamatan.
          </p>
          <div class="grid grid-cols-2 gap-3">
            <div class="flex items-center gap-2">
              <i class="fas fa-check text-[10px] text-emerald-500 bg-emerald-50 w-6 h-6 rounded-full flex items-center justify-center"></i>
              <span class="text-[11px] font-bold text-slate-600">Real-time</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fas fa-check text-[10px] text-emerald-500 bg-emerald-50 w-6 h-6 rounded-full flex items-center justify-center"></i>
              <span class="text-[11px] font-bold text-slate-600">Multi-User</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fas fa-check text-[10px] text-emerald-500 bg-emerald-50 w-6 h-6 rounded-full flex items-center justify-center"></i>
              <span class="text-[11px] font-bold text-slate-600">Peta Interaktif</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fas fa-check text-[10px] text-emerald-500 bg-emerald-50 w-6 h-6 rounded-full flex items-center justify-center"></i>
              <span class="text-[11px] font-bold text-slate-600">Export PDF & Excel</span>
            </div>
          </div>
        </div>
        <div class="relative">
          <div class="absolute -inset-3 bg-indigo-100 rounded-[2rem] rotate-2 opacity-50"></div>
          <div class="relative bg-white rounded-[2rem] shadow-lg border border-slate-100 overflow-hidden group">
            <img src="{{ asset('images/login-bg-neww.png') }}" class="w-full h-auto block transform group-hover:scale-105 transition duration-700" loading="lazy" width="800" height="600" decoding="async">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-16 bg-white">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto">
      <div class="text-center mb-10">
        <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-3">Kontak</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Hubungi Kami</h3>
        <p class="text-slate-400 font-medium text-sm mt-3">Pertanyaan, masukan, atau butuh bantuan teknis? Kami siap membantu.</p>
      </div>

      <div class="max-w-5xl mx-auto">
        <!-- Main Contact Row -->
        <div class="grid md:grid-cols-4 gap-4 mb-5">
          <!-- Lokasi -->
          <a href="https://www.google.com/maps/search/92HW+RHQ,+Unnamed+Road,+Tabilaa,+Kec.+Bolaang+Uki,+Kabupaten+Bolaang+Mongondow+Selatan,+Sulawesi+Utara" target="_blank" class="bg-slate-50 rounded-2xl p-5 border border-slate-100 block hover:shadow-lg hover:border-indigo-100 transition-all duration-300 group">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition"><i class="fas fa-location-dot text-xs"></i></div>
              <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Lokasi</h5>
            </div>
            <p class="text-[11px] font-semibold text-slate-500 leading-relaxed">Kompleks Perkantoran Pemerintah Daerah Kab. Bolaang Mongondow Selatan</p>
            <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest mt-2 inline-flex items-center gap-1 group-hover:underline">Lihat Peta <i class="fas fa-external-link-alt"></i></span>
          </a>
          <!-- Telepon -->
          <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0"><i class="fas fa-phone text-xs"></i></div>
              <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Telepon</h5>
            </div>
            <p class="text-[11px] font-semibold text-slate-500">0858-2426-8216</p>
            <p class="text-[11px] font-semibold text-slate-500">Senin - Jumat</p>
            <a href="tel:+6285824268216" class="text-[9px] font-bold text-amber-600 uppercase tracking-widest mt-2 inline-flex items-center gap-1 hover:underline">Hubungi <i class="fas fa-arrow-right"></i></a>
          </div>
          <!-- WhatsApp -->
          <a href="https://wa.me/6285824268216" target="_blank" class="bg-green-50 rounded-2xl p-5 border border-green-100 block hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 bg-green-100 text-green-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-green-600 group-hover:text-white transition"><i class="fab fa-whatsapp text-xs"></i></div>
              <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">WhatsApp</h5>
            </div>
            <p class="text-[11px] font-semibold text-slate-500">0858-2426-8216</p>
            <p class="text-[11px] font-semibold text-slate-500">Respon cepat</p>
            <span class="text-[9px] font-bold text-green-600 uppercase tracking-widest mt-2 inline-flex items-center gap-1 group-hover:underline">Chat Sekarang <i class="fas fa-arrow-right"></i></span>
          </a>
          <!-- Email -->
          <a href="mailto:supandialentadu14@gmail.com" class="bg-slate-50 rounded-2xl p-5 border border-slate-100 block hover:shadow-lg hover:border-emerald-100 transition-all duration-300 group">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition"><i class="fas fa-envelope text-xs"></i></div>
              <h5 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Email</h5>
            </div>
            <p class="text-[11px] font-semibold text-slate-500 break-all">supandialentadu14@gmail.com</p>
            <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest mt-2 inline-flex items-center gap-1 group-hover:underline">Kirim Email <i class="fas fa-arrow-right"></i></span>
          </a>
        </div>

        <!-- Developer Row -->
        <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-50 rounded-2xl p-4 border border-slate-100">
          <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-sm font-black shrink-0">E</div>
            <div class="min-w-0">
              <h5 class="text-xs font-black text-slate-900">Emon Alentadu <span class="font-semibold text-slate-400">- DISKOMINFO Bolsel</span></h5>
            </div>
          </div>
          <div class="flex items-center gap-2.5">
            <a href="https://www.facebook.com/share/18J61xd2XQ/?mibextid=wwXIfr" target="_blank" class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-300 hover:text-blue-600 hover:shadow transition border border-slate-100"><i class="fab fa-facebook-f text-xs"></i></a>
            <a href="https://www.instagram.com/emonn_65?igsh=MWM4c2JzdjNvZG4xMQ%3D%3D&utm_source=qr" target="_blank" class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-300 hover:text-red-500 hover:shadow transition border border-slate-100"><i class="fab fa-instagram text-xs"></i></a>
            <a href="https://wa.me/6285824268216" target="_blank" class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-slate-300 hover:text-green-600 hover:shadow transition border border-slate-100"><i class="fab fa-whatsapp text-xs"></i></a>
            <div class="w-px h-5 bg-slate-200 mx-0.5"></div>
            <a href="https://wa.me/6285824268216" target="_blank" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-md shadow-indigo-200 whitespace-nowrap">
              <i class="fab fa-whatsapp mr-1"></i> Chat
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-12 bg-slate-900 text-white">
    <div class="w-full max-w-none px-6 md:px-12 lg:px-20 mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/simpatis.png') }}" alt="Logo" class="h-8 w-8 object-contain" loading="lazy" width="32" height="32" decoding="async">
        <span class="text-xl font-black tracking-tighter">SIMPATI</span>
      </div>
      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] text-center">
        &copy; 2026 Emon Alentadu<br>
        <span class="text-indigo-400/50">All Rights Reserved.</span>
      </p>
      <div class="flex gap-6">
        <a href="#home" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">Home</a>
        <a href="#bts" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">BTS</a>
        <a href="#about" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">About</a>
      </div>
    </div>
  </footer>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const btsTotal = {{ $btsTotal }};

      const providerColors = {
        'Telkomsel': '#e74c3c', 'Indosat': '#f39c12', 'XL Axiata': '#3498db',
        'Tri (3)': '#9b59b6', 'Smartfren': '#2ecc71', 'Lainnya': '#95a5a6'
      };

      const allPoints = @json($allBtsPoints);

      const mapEl = document.getElementById('landing-map');
      if (!mapEl) return;

      const map = L.map('landing-map', { scrollWheelZoom: false }).setView([0.4317, 123.4817], 11);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 18
      }).addTo(map);

      const markers = [];
      if (allPoints && allPoints.length > 0) {
        allPoints.forEach(function(p) {
          if (!p.latitude || !p.longitude) return;
          const color = providerColors[p.provider] || '#95a5a6';
          const icon = L.divIcon({
            className: '',
            html: '<div style="width:22px;height:22px;border-radius:50%;background:' + color + ';border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
            iconSize: [22, 22], iconAnchor: [11, 11]
          });
          const m = L.marker([p.latitude, p.longitude], {icon: icon}).addTo(map);
          m.bindPopup('<b>' + p.nama_bts + '</b><br>' + p.provider + '<br>' + p.kecamatan + '<br><span style="color:' + color + '">' + (p.status_operasional || '-') + '</span>');
          markers.push(m);
        });
        if (markers.length > 0) {
          map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
        }
      }

      const legend = L.control({position: 'bottomright'});
      legend.onAdd = function() {
        const div = L.DomUtil.create('div');
        div.style.cssText = 'background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:10px 14px;font-size:10px;color:#334155;box-shadow:0 2px 8px rgba(0,0,0,0.08);';
        let html = '<div style="font-weight:800;margin-bottom:5px;font-size:11px;">Provider</div>';
        for (const [name, c] of Object.entries(providerColors)) {
          if (allPoints && allPoints.some(p => p.provider === name)) {
            html += '<div style="display:flex;align-items:center;gap:6px;margin-bottom:2px;"><span style="width:8px;height:8px;border-radius:50%;background:' + c + ';flex-shrink:0;"></span>' + name + '</div>';
          }
        }
        div.innerHTML = html;
        return div;
      };
      legend.addTo(map);

      setTimeout(function() { map.invalidateSize(); }, 100);
    });
  </script>

</body>
</html>
