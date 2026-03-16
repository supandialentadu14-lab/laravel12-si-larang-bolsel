<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Inventory') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/desktop.css', 'resources/js/desktop.js'])
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
            }
          },
        },
      }
    </script>
  @endif
  {{-- CSS laporan global untuk preview F4, KOP, dan tabel --}}
  <link rel="stylesheet" href="{{ asset('css/report.css') }}">

  <style>
    :root {
      --body-bg: #F8FAFC;
      --body-text: #1E293B;
      --nav-bg: rgba(255, 255, 255, 0.8);
      --accent: #4F46E5;
      --accent-soft: rgba(79, 70, 229, 0.1);
    }

    .theme-dark {
      --body-bg: #0F172A;
      --body-text: #F8FAFC;
      --nav-bg: rgba(30, 41, 59, 0.8);
      --accent: #818CF8;
      --accent-soft: rgba(129, 140, 248, 0.1);
    }

    [x-cloak] { display: none !important; }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: var(--body-bg);
      color: var(--body-text);
    }

    .glass-nav {
      backdrop-filter: blur(12px) saturate(180%);
      -webkit-backdrop-filter: blur(12px) saturate(180%);
      background-color: var(--nav-bg);
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .nav-item {
      position: relative;
      padding: 0.5rem 1rem;
      font-weight: 700;
      font-size: 0.875rem;
      border-radius: 0.75rem;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--body-text);
      opacity: 0.7;
    }

    .nav-item:hover, .nav-item.active {
      opacity: 1;
      background: var(--accent-soft);
      color: var(--accent);
    }

    .nav-dropdown-card {
      position: absolute;
      top: calc(100% + 0.5rem);
      left: 50%;
      transform: translateX(-50%);
      min-width: 220px;
      background: white;
      border-radius: 1.25rem;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      border: 1px solid rgba(0, 0, 0, 0.05);
      padding: 0.75rem;
      z-index: 100;
    }

    .theme-dark .nav-dropdown-card {
      background: #1E293B;
      border-color: rgba(255, 255, 255, 0.05);
    }

    .dropdown-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      font-size: 0.8rem;
      font-weight: 700;
      transition: all 0.2s;
      color: var(--body-text);
      opacity: 0.8;
    }

    .dropdown-link:hover {
      background: var(--accent-soft);
      color: var(--accent);
      opacity: 1;
      transform: translateX(4px);
    }

    /* Dashboard Premium Cards */
    .premium-card {
      background: white;
      border-radius: 2rem;
      padding: 1.5rem;
      border: 1px solid rgba(0, 0, 0, 0.03);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
      transition: all 0.3s ease;
    }

    .theme-dark .premium-card {
      background: #1E293B;
      border-color: rgba(255, 255, 255, 0.03);
    }

    .premium-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.85rem 1.25rem;
      border-radius: 1rem;
      font-size: 0.85rem;
      font-weight: 800;
      color: #94A3B8;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }

    .sidebar-link i {
      font-size: 1.1rem;
      width: 1.5rem;
      text-align: center;
      transition: transform 0.3s;
    }

    .sidebar-link:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #FFFFFF !important;
      transform: translateX(4px);
    }

    .sidebar-link.active {
      background: #4F46E5;
      color: #FFFFFF !important;
      box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .sidebar-link.active i {
      transform: scale(1.1);
    }

    .sub-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.65rem 1rem;
      padding-left: 3.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      color: #94A3B8;
      border-radius: 0.75rem;
      transition: all 0.2s;
    }

    .sub-link:hover {
      color: #6366F1 !important;
      background: rgba(99, 102, 241, 0.1);
    }

    .sub-link.active {
      color: #818CF8;
      background: rgba(129, 140, 248, 0.1);
      border-right: 3px solid #6366F1;
      border-radius: 0.75rem 0 0 0.75rem;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { 
      background: #CBD5E1; 
      border-radius: 10px; 
    }
    .theme-dark ::-webkit-scrollbar-thumb { background: #334155; }

    /* Modern Marquee Animation */
    @keyframes marqueeScroll {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .marquee-container {    
      ba
      overflow: hidden;
      white-space: nowrap;
      position: relative;
    }
    .marquee-content {
      display: inline-flex;
      animation: marqueeScroll 10s linear infinite;
    }
    .marquee-content:hover {
      animation-play-state: paused;
    }

    @media print {
      .no-print { display: none !important; }
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
  <script>
    (function () {
      function ensureTailwindCdn() {
        if (window.__twCdnLoaded) return;
        window.__twCdnLoaded = true;
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
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
              },
            },
          },
        };

        var s = document.createElement('script');
        s.src = 'https://cdn.tailwindcss.com';
        s.async = true;
        document.head.appendChild(s);
      }

      function checkTailwindLoaded() {
        var el = document.getElementById('tw-check');
        if (!el) return;
        var display = window.getComputedStyle(el).display;
        if (display !== 'none') ensureTailwindCdn();
      }

      window.addEventListener('load', function () {
        setTimeout(checkTailwindLoaded, 0);
      });
    })();
  </script>
</head>

<body class="font-sans antialiased bg-[#F4F7FA] min-h-screen" 
  x-data="{ 
    unreadChatCount: 0,
    notifOpen: false,
    checkNewMessages() {
      fetch('{{ route('chat.unread') }}')
        .then(res => res.json())
        .then(data => { this.unreadChatCount = data.count; })
        .catch(e => {});
    }
  }" 
  x-init="checkNewMessages(); setInterval(() => checkNewMessages(), 15000)">

  <!-- Application Boxed Wrapper -->
  <div class="{{ Request::is('reports*') ? 'max-w-none xl:max-w-[1440px]' : 'max-w-[1240px]' }} mx-auto bg-white h-screen overflow-hidden shadow-[0_0_60px_-15px_rgba(0,0,0,0.1)] flex flex-col border-x border-slate-100 relative">

  <div class="flex flex-row flex-1 min-h-0 overflow-hidden">
    <!-- Sidebar Navigation -->
    <aside id="sidebar-main" class="w-64 flex flex-col no-print bg-[#0F172A] border-r border-slate-800/50 z-10" style="background-color: #0F172A;">
      <div class="p-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
            <img src="{{ asset('images/silarang-logo.png') }}" class="w-7 h-7 object-contain brightness-0 invert" onerror="this.style.display='none'">
          </div>
          <div class="flex flex-col">
            <span class="text-lg font-black tracking-tight text-white leading-none">SI-LARANG</span>
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Bolsel</span>
          </div>
        </a>
      </div>

      <nav x-data="{ 
        activeMenu: '{{ request()->is('products*') || request()->is('categories*') || request()->is('suppliers*') || request()->is('import/products*') ? 'master' : (request()->is('stock*') || request()->is('reports*') ? 'transaksi' : (request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'manajemen' : 'none')) }}' 
      }" class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
        <div class="pb-4">

          <a href="{{ route('dashboard') }}" 
             @click="activeMenu = 'none'"
             class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-grid-2"></i> Dashboard
          </a>
        </div>

        <div class="pb-4">
          <button @click="activeMenu = (activeMenu === 'master' ? 'none' : 'master')" 
                  class="sidebar-link w-full"
                  :class="activeMenu === 'master' || {{ json_encode(request()->is('products*') || request()->is('categories*') || request()->is('suppliers*') || request()->is('import/products*')) }} ? 'active' : ''">
            <i class="fas fa-database"></i> 
            <span class="flex-1 text-left">Master Data</span>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="activeMenu === 'master' ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="activeMenu === 'master'" x-transition class="mt-1 space-y-1">
            <a href="{{ route('categories.index') }}" class="sub-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Jenis Belanja</a>
            <a href="{{ route('suppliers.index') }}" class="sub-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">Penyedia</a>
            <a href="{{ route('products.index') }}" class="sub-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Data Barang</a>
          </div>
        </div>

        <div class="pb-4">
          <button @click="activeMenu = (activeMenu === 'transaksi' ? 'none' : 'transaksi')" 
                  class="sidebar-link w-full"
                  :class="activeMenu === 'transaksi' || {{ json_encode(request()->is('stock*') || request()->is('reports*')) }} ? 'active' : ''">
            <i class="fas fa-exchange-alt"></i> 
            <span class="flex-1 text-left">Transaksi</span>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="activeMenu === 'transaksi' ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="activeMenu === 'transaksi'" x-transition class="mt-1 space-y-1">
            <a href="{{ route('stock.index') }}" class="sub-link {{ request()->routeIs('stock.index') ? 'active' : '' }}">Mutasi Masuk/Keluar</a>
            <a href="{{ route('reports.belanja.modal.list') }}" class="sub-link {{ request()->routeIs('reports.belanja.modal.list') ? 'active' : '' }}">Daftar Belanja</a>
            <a href="{{ route('reports.nota.list') }}" class="sub-link {{ request()->routeIs('reports.nota.list') ? 'active' : '' }}">Nota Pesanan</a>
            <a href="{{ route('reports.pemeriksaan.list') }}" class="sub-link {{ request()->routeIs('reports.pemeriksaan.list') ? 'active' : '' }}">Pemeriksaan</a>
            <a href="{{ route('reports.penerimaan.list') }}" class="sub-link {{ request()->routeIs('reports.penerimaan.list') ? 'active' : '' }}">Penerimaan (BASTB)</a>
            <a href="{{ route('reports.kwitansi.list') }}" class="sub-link {{ request()->routeIs('reports.kwitansi.list') ? 'active' : '' }}">Kwitansi</a>
            <a href="{{ route('reports.opname.list') }}" class="sub-link {{ request()->routeIs('reports.opname.list') ? 'active' : '' }}">BA Opname</a>
            <a href="{{ route('reports.pinjam.list') }}" class="sub-link {{ request()->routeIs('reports.pinjam.list') ? 'active' : '' }}">Pinjam Pakai</a>
          </div>
        </div>

        <div class="pb-4">
          <button @click="activeMenu = (activeMenu === 'manajemen' ? 'none' : 'manajemen')" 
                  class="sidebar-link w-full"
                  :class="activeMenu === 'manajemen' || {{ json_encode(request()->is('settings*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*')) }} ? 'active' : ''">
            <i class="fas fa-sliders-h"></i> 
            <span class="flex-1 text-left">Manajemen</span>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="activeMenu === 'manajemen' ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="activeMenu === 'manajemen'" x-transition class="mt-1 space-y-1">
            <a href="{{ route('settings.opd.index') }}" class="sub-link {{ request()->routeIs('settings.opd.index') ? 'active' : '' }}">Instansi (OPD)</a>
            @if(Auth::user()->isAdmin())
              <a href="{{ route('users.index') }}" class="sub-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Pengguna</a>
              <a href="{{ route('activity_log.index') }}" class="sub-link {{ request()->routeIs('activity_log.*') ? 'active' : '' }}">Log Aktivitas</a>
            @endif
          </div>
        </div>

        <div class="pb-4">
          <a href="{{ route('profile.edit') }}" 
             @click="activeMenu = 'none'"
             class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Profil
          </a>
        </div>
      </nav>
    </aside>

    <!-- Main Content Wrapper (Header + Content) -->
    <div class="flex-1 flex flex-col min-w-0 bg-white relative">
      <!-- Minimal Top Header (Fixed Position) -->
      <header class="h-20 sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-50 flex items-center justify-between px-8 no-print z-20">
        <div class="flex-none flex items-center gap-4">
          <div class="relative max-w-xs w-full lg:block hidden">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" placeholder="Cari di SI-LARANG..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 placeholder:text-slate-300 focus:ring-2 focus:ring-indigo-100 outline-none">
          </div>
        </div>

        <!-- Full Width Marquee Bar -->
        <div class="hidden lg:flex flex-1 items-center overflow-hidden ml-8 mr-48">
          <div class="marquee-container w-full group">
            <div class="marquee-content flex items-center gap-20">
              @php
                $opdSetting = \Illuminate\Support\Facades\Cache::remember('opd_setting_' . Auth::id(), 3600, function() {
                    return \App\Models\OpdSetting::where('user_id', Auth::id())->first();
                });
                $opdName = $opdSetting->nama_opd ?? 'OPD';
                $welcomeText = "Selamat datang di SI-LARANG (Sistem Informasi Pengelolaan Persediaan Barang). " . $opdName . " Kabupaten Bolaang Mongondow Selatan";
                $marqueeText = $welcomeText;
              @endphp
              <div class="flex items-center gap-10">
                <span class="text-[20px] font-black text-slate-400 uppercase tracking-[0.15em] flex items-center gap-4">
                  {{ $marqueeText }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-6 pl-10 border-l border-slate-50">
          <!-- Chat -->
          <a href="{{ route('chat.index') }}" class="w-11 h-11 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition relative">
            <i class="fas fa-comment-dots text-lg"></i>
            <template x-if="unreadChatCount > 0">
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white" x-text="unreadChatCount"></span>
            </template>
          </a>

          <!-- Notifications -->
          <button @click="notifOpen = true" class="w-11 h-11 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition relative">
            <i class="fas fa-bell text-lg"></i>
            @if (isset($lowStockCount) && $lowStockCount > 0)
              <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-bounce-subtle">
                {{ $lowStockCount }}
              </span>
            @endif
          </button>
 
          <!-- Profile Photo Only -->
          <div class="w-10 h-10 rounded-xl bg-white overflow-hidden border border-slate-100 shadow-sm flex-shrink-0 p-0.5">
            <img class="w-full h-full rounded-[10px] object-cover" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4F46E5&color=ffffff' }}">
          </div>
        </div>
      </header>

      <div class="flex-1 overflow-y-auto custom-scrollbar px-8 py-10 bg-slate-50/50">
        <!-- Breadcrumbs/Subheader -->
        <div class="flex items-center justify-between mb-2 no-print">
          <div id="page-header">
            <h1 class="text-[2.2rem] font-black text-slate-800 tracking-tight leading-none transition-all">@yield('header')</h1>
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.3em] mt-3">@yield('subheader')</p>
          </div>
          <div id="page-actions" class="flex items-center gap-3">
            @yield('actions')
          </div>
        </div>

        <!-- Feedback Alerts -->
        @if (session('success'))
          <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-[2rem] flex items-center gap-4 text-emerald-800 animate-fadeIn">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-lg shadow-emerald-200">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="flex-1">
              <p class="font-black text-sm">Berhasil!</p>
              <p class="text-xs opacity-80">{{ session('success') }}</p>
            </div>
          </div>
        @endif

        @if (session('error') || $errors->any())
          <div class="mb-8 p-6 bg-rose-50 border border-rose-100 rounded-[2rem] flex items-center gap-4 text-rose-800 animate-fadeIn">
            <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl shadow-lg shadow-rose-200">
              <i class="fas fa-times-circle"></i>
            </div>
            <div class="flex-1">
              <p class="font-black text-sm">Terjadi Kesalahan</p>
              <p class="text-xs opacity-80">{{ session('error') ?? 'Silakan periksa inputan Anda.' }}</p>
            </div>
          </div>
        @endif
        
        <!-- Main Dynamic Content -->
        <main id="app-content" class="transition-all duration-300">
          @yield('content')
        </main>
      </div>

      <footer class="px-8 py-4 bg-white border-t border-slate-50 no-print z-10 shadow-[0_-5px_20px_-10px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6" id="footer-content">
          <!-- Left: Copyright -->
          <p class="text-slate-300 font-bold text-[9px] uppercase tracking-widest">
            Copyright &copy; 2026 Emon Alentadu . SI-LARANG BOLSEL
          </p>

          <!-- Right: Contact Developer & Socials -->
          <div class="flex items-center gap-6">
            <a href="https://wa.me/6282194680004" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-500 transition-all shadow-sm active:scale-90 hover:scale-110">
              <i class="fab fa-whatsapp text-sm"></i>
            </a>
            <a href="https://instagram.com/emon_alentadu" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all shadow-sm active:scale-90 hover:scale-110">
              <i class="fab fa-instagram text-sm"></i>
            </a>
            <a href="https://facebook.com/emon.alentadu" target="_blank" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 transition-all shadow-sm active:scale-90 hover:scale-110">
              <i class="fab fa-facebook-f text-sm"></i>
            </a>
          </div>
        </div>
      </footer>
    </div>
  </div>
</div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const main = document.querySelector('#app-content');
      const nav = document.querySelector('nav');
      
      const isSameOrigin = (url) => {
        try {
          const u = new URL(url, window.location.origin);
          return u.origin === window.location.origin;
        } catch { return false; }
      };

      const shouldSoftLink = (a) => {
        const href = a.getAttribute('href') || '';
        if (!href || href.startsWith('#')) return false;
        if (!isSameOrigin(href)) return false;
        if (a.hasAttribute('download') || a.target === '_blank') return false;
        if (a.classList.contains('no-soft') || href.includes('logout')) return false;
        return true;
      };

      const setActive = (href) => {
        // Ensure href is a comparable path
        let path = href;
        try { path = new URL(href, window.location.origin).pathname; } catch(e) {}

        // Handle single sidebar links
        const sideLinks = nav.querySelectorAll('.sidebar-link');
        sideLinks.forEach(link => {
          link.classList.remove('active');
          try {
            const linkPath = new URL(link.getAttribute('href'), window.location.origin).pathname;
            if (linkPath === path) link.classList.add('active');
          } catch(e) {}
        });

        // Handle sub links and their parents
        const subLinks = nav.querySelectorAll('.sub-link');
        subLinks.forEach(link => {
          link.classList.remove('active');
          try {
            const linkPath = new URL(link.getAttribute('href'), window.location.origin).pathname;
            if (linkPath === path) {
              link.classList.add('active');
              // Make parent sidebar-link active
              const parentGroup = link.closest('.pb-4');
              if (parentGroup) {
                const parentBtn = parentGroup.querySelector('.sidebar-link');
                if (parentBtn) parentBtn.classList.add('active');
              }
            }
          } catch(e) {}
        });
      };

      const initScripts = (root) => {
        const scripts = root.querySelectorAll('script');
        scripts.forEach(s => {
          const n = document.createElement('script');
          if (s.src) n.src = s.src;
          else n.textContent = s.textContent;
          if (s.type) n.type = s.type;
          root.appendChild(n);
        });
        if (window.Alpine && Alpine.initTree) Alpine.initTree(root);
      };

      const swapMain = async (href, push = true) => {
        try {
          const res = await fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          if (!res.ok) throw new Error('Failed');
          const html = await res.text();
          const doc = new DOMParser().parseFromString(html, 'text/html');
          const newMain = doc.querySelector('#app-content') || doc.querySelector('main');
          
          if (!newMain) { window.location.href = href; return; }
          
          document.title = doc.title || document.title;
          main.innerHTML = newMain.innerHTML;
          
          // Update Page Header & Actions
          const newHeader = doc.querySelector('#page-header');
          const newActions = doc.querySelector('#page-actions');
          if (newHeader) document.querySelector('#page-header').innerHTML = newHeader.innerHTML;
          if (newActions) document.querySelector('#page-actions').innerHTML = newActions.innerHTML;

          setActive(href);
          initScripts(main);
          if (push) history.pushState({}, '', href);
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch { window.location.href = href; }
      };

      document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href]');
        if (!a || !shouldSoftLink(a)) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        
        e.preventDefault();
        swapMain(a.getAttribute('href'), true);
      });

      window.addEventListener('popstate', () => swapMain(window.location.href, false));

      document.addEventListener('submit', async (e) => {
        const form = e.target.closest('form');
        const action = form.getAttribute('action') || window.location.href;
        if (form.classList.contains('no-soft') || action.includes('logout')) return;
        const method = (form.getAttribute('method') || 'GET').toUpperCase();
        if (!isSameOrigin(action)) return;
        
        e.preventDefault();
        try {
          const fd = new FormData(form);
          let url = action;
          const options = {
            method,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          };

          if (method === 'GET') {
            const u = new URL(action, window.location.origin);
            const params = new URLSearchParams(fd);
            params.forEach((v, k) => u.searchParams.set(k, v));
            url = u.toString();
          } else {
            options.body = fd;
          }

          const res = await fetch(url, options);
          const html = await res.text();
          const doc = new DOMParser().parseFromString(html, 'text/html');
          const newMain = doc.querySelector('#app-content') || doc.querySelector('main');
          
          if (!newMain) { window.location.href = url; return; }
          
          document.title = doc.title || document.title;
          main.innerHTML = newMain.innerHTML;
          
          const newHeader = doc.querySelector('#page-header');
          const newActions = doc.querySelector('#page-actions');
          if (newHeader) document.querySelector('#page-header').innerHTML = newHeader.innerHTML;
          if (newActions) document.querySelector('#page-actions').innerHTML = newActions.innerHTML;

          initScripts(main);
          history.pushState({}, '', url);
          window.scrollTo({ top: 0, behavior: 'smooth' });
        } catch { window.location.href = action; }
      });
    });
  </script>

  <script>
    (() => {
      const findPreviewPaper = () => {
        return document.querySelector('#print-area.preview-paper')
          || document.querySelector('#print-area .preview-paper')
          || document.querySelector('.preview-paper');
      };

      const fitPreviewToViewport = () => {
        const paper = findPreviewPaper();
        if (paper) {
          document.documentElement.dataset.docPreview = '1';
        } else {
          delete document.documentElement.dataset.docPreview;
        }
        if (!paper) return;
        if (window.matchMedia('print').matches) return;

        const isMobile = window.matchMedia('(max-width: 768px)').matches;
        const existingViewport = document.querySelector('[data-doc-fit-viewport]');

        if (!isMobile) {
          if (existingViewport) {
            existingViewport.style.height = '';
            existingViewport.style.overflow = '';
          }
          paper.style.transform = '';
          paper.style.transformOrigin = '';
          paper.style.margin = '';
          return;
        }

        let viewport = existingViewport;
        if (!viewport) {
          viewport = document.createElement('div');
          viewport.dataset.docFitViewport = '1';
          viewport.style.position = 'relative';
          viewport.style.width = '100%';
          viewport.style.overflow = 'hidden';
          viewport.style.marginTop = '12px';
          viewport.style.marginBottom = '12px';
          const parent = paper.parentElement;
          parent.insertBefore(viewport, paper);
          viewport.appendChild(paper);
        }

        const top = viewport.getBoundingClientRect().top;
        const availableHeight = Math.max(240, window.innerHeight - top - 16);
        viewport.style.height = `${availableHeight}px`;

        paper.style.transform = 'none';
        paper.style.transformOrigin = 'top left';
        paper.style.margin = '0';

        const naturalWidth = paper.scrollWidth;
        const naturalHeight = paper.scrollHeight;
        if (!naturalWidth || !naturalHeight) return;

        const scale = Math.min(viewport.clientWidth / naturalWidth, viewport.clientHeight / naturalHeight);
        const left = Math.max(0, (viewport.clientWidth - (naturalWidth * scale)) / 2);
        paper.style.transform = `scale(${scale}) translateX(${left / scale}px)`;
      };

      document.addEventListener('DOMContentLoaded', () => requestAnimationFrame(fitPreviewToViewport));
      window.addEventListener('resize', () => requestAnimationFrame(fitPreviewToViewport));
      window.addEventListener('orientationchange', () => requestAnimationFrame(fitPreviewToViewport));
      const main = document.querySelector('main');
      if (main && 'MutationObserver' in window) {
        const obs = new MutationObserver(() => requestAnimationFrame(fitPreviewToViewport));
        obs.observe(main, { childList: true, subtree: true });
      }
    })();
  </script>

  <style>
    #print-area, .preview-paper {
      overflow-wrap: anywhere;
      word-break: break-word;
      hyphens: auto;
    }
    #print-area * {
      box-sizing: border-box;
      max-width: 100%;
    }
    #print-area table {
      table-layout: fixed;
      width: 100% !important;
    }
    #print-area td, #print-area th {
      white-space: normal !important;
      word-break: break-word;
      overflow-wrap: anywhere;
    }
    #print-area p,
    #print-area span,
    #print-area div,
    #print-area li {
      white-space: normal !important;
    }
    #print-area img, #print-area svg, #print-area canvas {
      max-width: 100% !important;
      height: auto !important;
    }

    @media (max-width: 768px) {
      html[data-doc-preview="1"] #page-header {
        position: sticky;
        top: 0;
        z-index: 50;
        background: rgba(243, 244, 246, 0.86);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(229, 231, 235, 0.75);
        border-radius: 18px;
        padding: 12px;
        margin-bottom: 14px;
      }

      html[data-doc-preview="1"] #page-header > div:first-child h2 {
        font-size: 18px;
        line-height: 1.2;
      }

      html[data-doc-preview="1"] #page-header > div:first-child p {
        font-size: 12px;
      }

      html[data-doc-preview="1"] #page-actions {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
      }

      html[data-doc-preview="1"] #page-actions form {
        display: contents;
      }

      html[data-doc-preview="1"] #page-actions .btn,
      html[data-doc-preview="1"] #page-actions button.btn {
        width: 100%;
        justify-content: center;
        padding: 12px 14px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 13px;
      }

      html[data-doc-preview="1"] #page-actions .btn.ml-2,
      html[data-doc-preview="1"] #page-actions button.ml-2,
      html[data-doc-preview="1"] #page-actions form.ml-2 {
        margin-left: 0 !important;
      }
    }
  </style>

    </div>
  </div>

  <!-- Notification Drawer (Desktop Version - Sleek Top Popover) -->
  <div x-show="notifOpen" x-cloak class="fixed inset-0 z-[100]">
    <div @click="notifOpen = false" 
         x-show="notifOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-transparent"></div>
    
    <div x-show="notifOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-[-10px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-10px]"
         class="absolute top-24 right-10 w-full max-w-[320px] bg-rose-500/95 backdrop-blur-md rounded-3xl shadow-[0_20px_60px_-15px_rgba(244,63,94,0.3)] flex flex-col border border-rose-400/50 overflow-hidden">
      
      <div class="px-5 py-4 border-b border-white/10 flex items-center justify-between">
        <div>
          <h2 class="text-xs font-black text-white tracking-tight uppercase">Notifikasi</h2>
        </div>
        <button @click="notifOpen = false" class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 transition-all active:scale-95">
          <i class="fas fa-times text-[10px]"></i>
        </button>
      </div>

      <div class="max-h-[350px] p-5 overflow-y-auto custom-scrollbar">
        @if (isset($lowStockProducts) && $lowStockProducts->count() > 0)
          <div class="space-y-3">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
              <p class="text-[8px] font-black text-white/90 uppercase tracking-widest">Stok Rendah</p>
            </div>
            @foreach ($lowStockProducts as $item)
              <div class="p-3 bg-white/10 border border-white/10 rounded-2xl hover:bg-white/20 transition-colors">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-triangle-exclamation text-[10px]"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                      <h3 class="text-[10px] font-black text-white truncate uppercase">{{ $item->name }}</h3>
                      <span class="px-1.5 py-0.5 bg-white text-rose-500 text-[8px] font-black rounded-md">{{ $item->stock }}</span>
                    </div>
                    <p class="text-[9px] font-bold text-white/70 mt-0.5 lowercase">{{ $item->unit }} tersisa</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="flex flex-col items-center justify-center text-center py-8">
            <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-white/50 mb-4">
              <i class="fas fa-bell-slash text-base"></i>
            </div>
            <h3 class="text-[11px] font-black text-white/90 tracking-tight uppercase">Semua Terkendali</h3>
            <p class="text-[8px] font-bold text-white/60 mt-1">Tidak ada notifikasi aktif</p>
          </div>
        @endif
      </div>

      <div class="px-5 py-3 bg-black/10 border-t border-white/10 flex items-center justify-center">
        <button @click="notifOpen = false" class="text-[9px] font-black text-white/80 uppercase tracking-widest hover:text-white transition-colors">Tutup Notifikasi</button>
      </div>
    </div>
  </div>

  <style>
    @keyframes bounce-subtle {
      0%, 100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
      50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
    }
    .animate-bounce-subtle {
      animation: bounce-subtle 3s infinite;
    }
  </style>

  </div> <!-- End of Application Boxed Wrapper -->
</body>
</html>
