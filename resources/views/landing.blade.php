<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SI-LARANG</title>
  <link rel="icon" type="image/webp" href="{{ asset('images/silarang-logo.webp') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  {{-- Preload Critical Assets --}}
  <link rel="preload" href="{{ asset('images/silarang-logo.webp') }}" as="image" type="image/webp">
  <link rel="preload" href="{{ asset('images/login-bg-neww.webp') }}" as="image" type="image/webp">
  
  {{-- Specialized Optimized Assets --}}
  @vite(['resources/css/landing.css', 'resources/js/landing.js'])

  {{-- Asynchronous Non-Blocking CSS --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" media="print" onload="this.media='all'">

  <style>
    /* Critical CSS for Hero - Inlined for instant paint */
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; text-rendering: optimizeSpeed; }
    .hero-gradient { background: linear-gradient(to right, #4f46e5, #9333ea); -webkit-background-clip: text; color: transparent; }
    #home { min-height: 100vh; display: flex; align-items: center; }
    nav { position: fixed; width: 100%; z-index: 50; top: 0; }
    
    /* Optimization for non-visible sections */
    section { contain: paint; content-visibility: auto; contain-intrinsic-size: 1px 500px; }
    
    /* Extreme speed for mobile: hide heavy decorative elements */
    @media (max-width: 767px) {
      .animate-blob, .blur-[120px], [class*="animate-blob"], .animate__animated { 
        display: none !important; 
        animation: none !important;
        transition: none !important;
      }
      .hero-image-container { transform: none !important; }
      nav { background: rgba(255,255,255,0.9) !important; backdrop-filter: none !important; }
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden selection:bg-indigo-100 selection:text-indigo-900" x-data="{ mobileMenu: false }">

  <!-- Navbar -->
  <nav class="fixed top-0 w-full z-50 transition-all duration-300" 
    :class="window.pageYOffset > 20 ? 'glass py-3 shadow-sm' : 'bg-transparent py-6'"
    @scroll.window="window.pageYOffset > 20 ? $el.classList.add('glass', 'py-3', 'shadow-sm') : $el.classList.remove('glass', 'py-3', 'shadow-sm'); $el.classList.contains('glass') ? '' : $el.classList.add('py-6')">
    <div class="container mx-auto px-6 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/silarang-logo.webp') }}" alt="Logo" class="h-10 w-10 object-contain" width="40" height="40" decoding="async" fetchpriority="high">
        <span class="text-xl font-black tracking-tighter text-indigo-900">SI-LARANG</span>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center gap-8">
        <a href="#home" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Beranda</a>
        <a href="#services" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Layanan</a>
        <a href="#about" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Tentang</a>
        <a href="#contact" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Kontak</a>
        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-black rounded-full shadow-lg shadow-indigo-200 hover:bg-slate-800 transition transform hover:-translate-y-0.5">
          MASUK
        </a>
      </div>

      <!-- Mobile Toggle -->
      <button class="md:hidden text-slate-800" @click="mobileMenu = !mobileMenu">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden bg-white/95 backdrop-blur-xl absolute top-full left-0 w-full border-b border-slate-100 py-6 px-6 space-y-4" 
      x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
      <a href="#home" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Beranda</a>
      <a href="#services" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Layanan</a>
      <a href="#about" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Tentang</a>
      <a href="#contact" @click="mobileMenu = false" class="block text-lg font-bold text-slate-800">Kontak</a>
      <a href="{{ route('login') }}" class="block w-full py-4 bg-indigo-600 text-white text-center font-black rounded-2xl shadow-lg">MASUK</a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    <!-- Animated Gradients -->
    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-100/40 rounded-full filter blur-[120px] animate-blob z-0"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-100/40 rounded-full filter blur-[120px] animate-blob animation-delay-2000 z-0"></div>
    
    <div class="container mx-auto px-6 relative z-10 grid md:grid-cols-2 gap-12 items-center">
      <div class="animate__animated animate__fadeInLeft">
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-[1.1] tracking-tight mb-6">
          Kelola Persediaan <br>
          <span class="hero-gradient">Lebih Cerdas & Cepat.</span>
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
          SI-LARANG (Sistem Informasi Pengelolaan Persediaaan Barang) adalah solusi digital pengelolaan persediaan barang milik daerah pada Pemerintah Kabupaten Bolaang Mongondow Selatan.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('login') }}" class="px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-2xl shadow-indigo-200 hover:bg-slate-800 transition transform hover:-translate-y-1">
            Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="#services" class="px-8 py-4 bg-white text-slate-800 font-black rounded-2xl border border-slate-100 shadow-xl shadow-slate-100 hover:bg-slate-50 transition transform hover:-translate-y-1">
            Lihat Fitur
          </a>
        </div>
      </div>
      <div class="relative animate__animated animate__fadeInRight hero-image-container">
        <div class="relative w-full max-w-lg mx-auto">
          <!-- Floating UI Elements Mockup style -->
          <div class="absolute inset-0 bg-indigo-600/5 rounded-[1.5rem] md:rounded-[4rem] rotate-3 md:rotate-6 scale-95 hidden md:block"></div>
          <div class="absolute inset-0 bg-indigo-600/10 rounded-[1.5rem] md:rounded-[4rem] -rotate-2 md:-rotate-3 scale-95 hidden md:block"></div>
          <div class="relative bg-white rounded-[1.5rem] md:rounded-[4rem] shadow-2xl border border-slate-50 overflow-hidden">
             <img src="{{ asset('images/login-bg-neww.webp') }}" class="w-full h-auto block" fetchpriority="high" width="800" height="600" decoding="async">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-24 bg-white relative">
    <div class="container mx-auto px-6">
      <div class="text-center mb-20 animate__animated animate__fadeInUp">
        <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-3">Layanan Kami</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Efisien. Akurat. Terintegrasi.</h3>
      </div>

      <div class="grid md:grid-cols-4 gap-8">
        <!-- Service 1 -->
        <div class="group p-8 bg-slate-50 rounded-[3rem] border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-2xl transition duration-500">
          <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition duration-500">
            <i class="fas fa-boxes-stacked text-2xl"></i>
          </div>
          <h4 class="text-xl font-extrabold text-slate-900 mb-4 uppercase tracking-tight">Manajemen Stok</h4>
          <p class="text-sm text-slate-500 font-bold leading-relaxed">Pantau ketersediaan barang secara real-time dari mana saja.</p>
        </div>

        <!-- Service 2 -->
        <div class="group p-8 bg-slate-50 rounded-[3rem] border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-2xl transition duration-500">
          <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition duration-500">
            <i class="fas fa-file-invoice text-2xl"></i>
          </div>
          <h4 class="text-xl font-extrabold text-slate-900 mb-4 uppercase tracking-tight">Pelaporan</h4>
          <p class="text-sm text-slate-500 font-bold leading-relaxed">Hasilkan laporan persediaan otomatis yang akurat dan siap cetak.</p>
        </div>

        <!-- Service 3 -->
        <div class="group p-8 bg-slate-50 rounded-[3rem] border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-2xl transition duration-500">
          <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-amber-600 group-hover:text-white transition duration-500">
            <i class="fas fa-file-signature text-2xl"></i>
          </div>
          <h4 class="text-xl font-extrabold text-slate-900 mb-4 uppercase tracking-tight">Berita Acara</h4>
          <p class="text-sm text-slate-500 font-bold leading-relaxed">Cetak berbagai dokumen administrasi (BA) hanya dengan satu klik.</p>
        </div>

        <!-- Service 4 -->
        <div class="group p-8 bg-slate-50 rounded-[3rem] border border-transparent hover:border-indigo-100 hover:bg-white hover:shadow-2xl transition duration-500">
          <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:bg-purple-600 group-hover:text-white transition duration-500">
            <i class="fas fa-users-gear text-2xl"></i>
          </div>
          <h4 class="text-xl font-extrabold text-slate-900 mb-4 uppercase tracking-tight">Kolaborasi</h4>
          <p class="text-sm text-slate-500 font-bold leading-relaxed">Sistem multi-user untuk koordinasi antar bidang dalam OPD.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-24 bg-slate-50">
    <div class="container mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
      <div class="order-2 md:order-1 relative w-full">
        <div class="absolute inset-0 bg-indigo-600/10 rounded-[1.5rem] md:rounded-[4rem] rotate-3"></div>
        <div class="relative bg-white rounded-[1.5rem] md:rounded-[4rem] shadow-xl border border-slate-100 overflow-hidden group">
          <img src="{{ asset('images/login-bg-new.webp') }}" class="w-full h-auto block transform group-hover:scale-110 transition duration-700" loading="lazy" width="800" height="600" decoding="async">
        </div>
      </div>
      <div class="order-1 md:order-2">
        <h2 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-3">Tentang Aplikasi</h2>
        <h3 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight mb-8">Optimalisasi Aset <br>Menuju Bolsel Digital</h3>
        <p class="text-lg text-slate-500 font-medium leading-relaxed mb-6">
          SI-LARANG (Sistem Informasi Pengelolaan Persediaan Barang) merupakan inisiatif digital untuk mendigitalisasi proses penatausahaan barang milik daerah di Pemerintah Kabupaten Bolaang Mongondow Selatan.
        </p>
        <p class="text-lg text-slate-500 font-medium leading-relaxed mb-8">
          Dengan SI-LARANG, setiap OPD dapat melakukan input, monitoring, dan pelaporan barang secara transparan, akuntabel, dan tepat waktu guna mendukung tata kelola keuangan yang baik.
        </p>
        
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-24 bg-white">
    <div class="container mx-auto px-6">
      <div class="max-w-5xl mx-auto glass rounded-[4rem] p-12 md:p-20 shadow-2xl relative overflow-hidden border-2 border-indigo-50">
        <!-- Decoration -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-50 rounded-full blur-3xl opacity-50"></div>

        <div class="grid md:grid-cols-2 gap-16 relative z-10">
          <div>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight mb-6 uppercase">Hubungi Kami</h3>
            <p class="text-slate-500 font-medium mb-10 leading-relaxed">Punya pertanyaan seputar SI-LARANG? Kami siap membantu mengoptimalkan sistem di instansi Anda.</p>
            
            <div class="space-y-6">
              <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 shadow-sm">
                  <i class="fas fa-location-dot"></i>
                </div>
                <div>
                  <p class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter">Lokasi Perkantoran</p>
                  <p class="text-sm font-bold text-slate-800">Kompleks Perkantoran Pemerintah Daerah Bolaang Mongondow Selatan</p>
                </div>
              </div>
              <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 shadow-sm">
                  <i class="fas fa-envelope"></i>
                </div>
                <div>
                  <p class="text-[10px] font-black text-emerald-400 uppercase tracking-tighter">Email Dukungan</p>
                  <p class="text-sm font-bold text-slate-800">support@silarang.bolselkab.go.id</p>
                </div>
              </div>
            </div>

            <div class="mt-12 flex gap-4">
              <a href="https://www.facebook.com/share/18J61xd2XQ/?mibextid=wwXIfr" target="_blank" class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition shadow-sm border border-slate-50"><i class="fab fa-facebook-f"></i></a>
              <a href="https://www.instagram.com/emonn_65?igsh=MWM4c2JzdjNvZG4xMQ%3D%3D&utm_source=qr" target="_blank" class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-600 hover:text-white transition shadow-sm border border-slate-50"><i class="fab fa-instagram"></i></a>
              <a href="https://wa.me/6285824268216" target="_blank" class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-green-600 hover:text-white transition shadow-sm border border-slate-50"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>
          
          <div class="bg-indigo-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-indigo-100">
            <h4 class="text-xl font-black uppercase tracking-widest mb-6">Konsultasi Gratis</h4>
            <div class="space-y-4">
              <div class="p-4 bg-white/10 rounded-2xl border border-white/20">
                <p class="text-[10px] font-black uppercase text-indigo-200 tracking-widest mb-1">Butuh Akun?</p>
                <p class="text-sm font-bold">Hubungi Administrator Pengelola Barang pada Dinas terkait untuk pembuatan akun sistem.</p>
              </div>
              <div class="p-4 bg-white/10 rounded-2xl border border-white/20">
                <p class="text-[10px] font-black uppercase text-indigo-200 tracking-widest mb-1">Developer</p>
                <p class="text-sm font-bold">Dikembangkan oleh Emon Alentadu (DISKOMINFO Bolsel)</p>
              </div>
            </div>
            <a href="https://wa.me/6285824268216" class="mt-8 block w-full py-4 bg-white text-indigo-600 text-center font-black rounded-2xl shadow-lg hover:bg-slate-50 transition">
              <i class="fab fa-whatsapp mr-2"></i> CHAT WHATSAPP
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-12 bg-slate-900 text-white">
    <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/silarang-logo.webp') }}" alt="Logo" class="h-8 w-8 object-contain" loading="lazy" width="32" height="32" decoding="async">
        <span class="text-xl font-black tracking-tighter">SI-LARANG</span>
      </div>
      
      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] text-center">
        &copy; 2026 Emon Alentadu<br>
        <span class="text-indigo-400/50">All Rights Reserved.</span>
      </p>

      <div class="flex gap-6">
        <a href="#home" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">Home</a>
        <a href="#services" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">Services</a>
        <a href="#about" class="text-[10px] font-black text-slate-400 hover:text-white uppercase">About</a>
      </div>
    </div>
  </footer>

</body>
</html>
