<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="view-transitiblurn" content="same-origin">
  
  <!-- PWA Setup -->
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#4f46e5">
  <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">

  <title>{{ config('app.name', 'Inventory Mobile') }}</title>
  <link rel="icon" type="image/webp" href="{{ asset('images/silarang-logo.webp') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  @vite(['resources/css/mobile.css', 'resources/js/mobile.js'])
  

  <script>
    (() => {
      const dir = sessionStorage.getItem('navDir');
      if (dir) document.documentElement.dataset.navDir = dir;
    })();
  </script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body class="antialiased select-none overflow-hidden transition-colors duration-300" 
  style="height: 100vh; height: 100dvh;"
  x-data="{ 
    mobileMenuOpen: false, 
    masterMenuOpen: false, 
    flowMenuOpen: false, 
    settingsMenuOpen: false, 
    profileMenuOpen: false,
    notifOpen: false,
    scrollingDown: false,
    lastScrollTop: 0,
    handleScroll(e) {
      let st = e.target.scrollTop;
      if (st > this.lastScrollTop && st > 50) {
        this.scrollingDown = true;
      } else {
        this.scrollingDown = false;
      }
      this.lastScrollTop = st <= 0 ? 0 : st;
    }
  }">
  <div class="flex flex-col h-full overflow-hidden">
    <div class="sticky top-0 z-[45]">
      @include('partials.mobile_header')
    </div>

    <!-- Main Content -->
    <main id="page-content" class="flex-1 px-5 pt-4 overflow-y-auto" @scroll="handleScroll">
      @if (session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-4 shadow-sm flex items-start gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-xs"></i>
          </div>
          <div class="min-w-0">
            <div class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Berhasil</div>
            <div class="text-[11px] font-bold text-emerald-700 mt-1 leading-snug">{{ session('success') }}</div>
          </div>
        </div>
      @endif

      @if (session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl px-4 py-4 shadow-sm flex items-start gap-3">
          <div class="w-10 h-10 rounded-2xl bg-red-600 text-white flex items-center justify-center flex-shrink-0">
            <i class="fas fa-exclamation text-xs"></i>
          </div>
          <div class="min-w-0">
            <div class="text-[10px] font-black text-red-800 uppercase tracking-widest">
              {{ \Illuminate\Support\Str::contains(session('error'), 'TUTUP BUKU AKTIF') ? 'Tutup Buku Aktif' : 'Gagal' }}
            </div>
            <div class="text-[11px] font-bold text-red-700 mt-1 leading-snug">{{ session('error') }}</div>
          </div>
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl px-4 py-4 shadow-sm">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-2xl bg-red-600 text-white flex items-center justify-center flex-shrink-0">
              <i class="fas fa-exclamation text-xs"></i>
            </div>
            <div class="min-w-0">
              <div class="text-[10px] font-black text-red-800 uppercase tracking-widest">Gagal</div>
              <ul class="mt-2 space-y-1">
                @foreach ($errors->all() as $msg)
                  <li class="text-[11px] font-bold text-red-700 leading-snug">{{ $msg }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif
      @yield('content')
    </main>
  </div>

  <!-- Low Stock Notification Sheet -->
  <div x-show="notifOpen" data-mobile-sheet class="fixed inset-0 z-[10001] overflow-hidden lg:hidden" 
    x-transition:enter="transition ease-in-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in-out duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak>
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="notifOpen = false"></div>

    <div class="absolute inset-x-0 bottom-0 max-h-[70vh] w-full bg-white rounded-t-[2.5rem] shadow-2xl flex flex-col transition-colors duration-300"
      x-transition:enter="transition ease-out duration-350 transform"
      x-transition:enter-start="translate-y-full"
      x-transition:enter-end="translate-y-0"
      x-transition:leave="transition ease-in duration-250 transform"
      x-transition:leave-start="translate-y-0"
      x-transition:leave-end="translate-y-full">
      
      <div class="w-full flex justify-center py-4">
        <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
      </div>

      <div class="px-6 pb-4 flex items-center justify-between">
        <div>
          <h3 class="font-black text-sm tracking-widest text-gray-900 uppercase transition-colors">Peringatan Stok Rendah</h3>
          <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Hanya menampilkan item kritis</p>
        </div>
        <button @click="notifOpen = false" class="btn-icon-mini bg-gray-50 text-gray-400">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto px-6 pb-8 space-y-2 no-scrollbar">
        @if (isset($lowStockCount) && $lowStockCount > 0 && isset($lowStockProducts))
          @foreach ($lowStockProducts as $lp)
          <div class="flex items-center gap-4 px-4 py-4 rounded-2xl bg-red-50/50 border border-red-100/50 transition">
            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm shadow-red-100">
              <i class="fas fa-exclamation-triangle text-xs"></i>
            </div>
            <div class="min-w-0">
              <p class="text-[11px] font-black text-red-900 truncate uppercase tracking-tight">{{ $lp->name }}</p>
              <p class="text-[9px] font-bold text-red-600 mt-1 uppercase tracking-widest">Tersisa: {{ $lp->stock }} {{ $lp->unit }}</p>
            </div>
          </div>
          @endforeach
        @else
          <div class="p-8 text-center text-gray-300">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p class="text-[10px] font-bold uppercase tracking-widest">Tidak ada stok kritis</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  @include('partials.mobile_bottom_nav')
  @stack('scripts')

  <script>
    (() => {
      const setBottomNavHeight = () => {
        const nav = document.querySelector('nav.bottom-nav');
        if (!nav) return;
        const h = Math.ceil(nav.getBoundingClientRect().height || 0);
        if (h > 0) document.documentElement.style.setProperty('--bottom-nav-height', `${h}px`);
      };
      window.addEventListener('load', setBottomNavHeight, { passive: true });
      window.addEventListener('resize', setBottomNavHeight, { passive: true });
      window.addEventListener('orientationchange', setBottomNavHeight, { passive: true });
      document.addEventListener('DOMContentLoaded', setBottomNavHeight);
      setTimeout(setBottomNavHeight, 0);
    })();
  </script>

  <script>
    (() => {
      const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const supportsViewTransition = 'startViewTransition' in document;
      const getNavIndex = (link, scope) => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return null;
        const links = Array.from(scope.querySelectorAll('a[href]'))
          .filter(a => {
            const h = a.getAttribute('href') || '';
            return h && !h.startsWith('#') && !h.startsWith('javascript:');
          });
        return links.indexOf(link);
      };

      const applyEnter = () => {
        const dir = sessionStorage.getItem('navDir');
        if (!dir || prefersReduced) return;
        if (supportsViewTransition) {
          requestAnimationFrame(() => {
            sessionStorage.removeItem('navDir');
          });
          return;
        }
        const content = document.getElementById('page-content');
        const bottomNav = document.querySelector('nav.bottom-nav');
        requestAnimationFrame(() => {
          if (content) content.classList.add(dir === 'right' ? 'page-enter-right' : 'page-enter-left');
          if (bottomNav) bottomNav.classList.add(dir === 'right' ? 'nav-enter-right' : 'nav-enter-left');
          sessionStorage.removeItem('navDir');
        });
      };

      const registerTransitionLinks = () => {
        const content = document.getElementById('page-content');
        const bottomNav = document.querySelector('nav.bottom-nav');
        const sheets = Array.from(document.querySelectorAll('[data-mobile-sheet]'));
        const normalizePath = (href) => {
          try {
            return new URL(href, window.location.origin).pathname;
          } catch {
            return href;
          }
        };
        const globalIndex = new Map();
        let masterIndex = null;
        let flowIndex = null;
        let settingsIndex = null;
        if (bottomNav) {
          const bottomLinks = Array.from(bottomNav.querySelectorAll('a[href]')).filter(a => {
            const h = a.getAttribute('href') || '';
            return h && !h.startsWith('#') && !h.startsWith('javascript:');
          });
          bottomLinks.forEach((a, idx) => globalIndex.set(normalizePath(a.getAttribute('href')), idx));
          const masterAnchor = bottomNav.querySelector('a[data-master-nav]');
          if (masterAnchor) masterIndex = getNavIndex(masterAnchor, bottomNav);
          const flowAnchor = bottomNav.querySelector('a[data-flow-nav]');
          if (flowAnchor) flowIndex = getNavIndex(flowAnchor, bottomNav);
          const settingsAnchor = bottomNav.querySelector('a[data-settings-nav]');
          if (settingsAnchor) settingsIndex = getNavIndex(settingsAnchor, bottomNav);
        }
        if (masterIndex !== null) {
          document.querySelectorAll('a[data-master-target][href]').forEach((a) => {
            const href = a.getAttribute('href');
            if (!href) return;
            globalIndex.set(normalizePath(href), masterIndex);
          });
        }
        if (flowIndex !== null) {
          document.querySelectorAll('a[data-flow-target][href]').forEach((a) => {
            const href = a.getAttribute('href');
            if (!href) return;
            globalIndex.set(normalizePath(href), flowIndex);
          });
        }
        if (settingsIndex !== null) {
          document.querySelectorAll('a[data-settings-target][href]').forEach((a) => {
            const href = a.getAttribute('href');
            if (!href) return;
            globalIndex.set(normalizePath(href), settingsIndex);
          });
        }

        const bindScope = (scope) => {
          if (!scope) return;
          scope.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', (e) => {
              if (e.defaultPrevented) return;
              if (e.button !== 0) return;
              if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
              if (link.hasAttribute('data-skip-transition')) return;

              const href = link.getAttribute('href');
              if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
              if (href === window.location.href || href === window.location.pathname) return;

              const current = sessionStorage.getItem('navIndex');
              const mapped = globalIndex.get(normalizePath(href));
              const target = String(mapped ?? getNavIndex(link, scope));
              if (current && target && current !== 'null' && target !== 'null') {
                const dir = Number(target) > Number(current) ? 'right' : 'left';
                sessionStorage.setItem('navDir', dir);
                document.documentElement.dataset.navDir = dir;
              }
              sessionStorage.setItem('navIndex', target);

              if (prefersReduced) return;
              if (supportsViewTransition) return;
              e.preventDefault();
              const dir = sessionStorage.getItem('navDir') || 'right';
              if (content) content.classList.add(dir === 'right' ? 'page-exit-right' : 'page-exit-left');
              if (bottomNav) bottomNav.classList.add(dir === 'right' ? 'nav-exit-right' : 'nav-exit-left');
              setTimeout(() => { window.location.href = href; }, 240);
            }, { passive: false });
          });
        };

        bindScope(bottomNav);
        sheets.forEach(bindScope);

        if (bottomNav) {
          const active = bottomNav.querySelector('.active-menu') || bottomNav.querySelector('a[href].active-menu');
          if (active) {
            const idx = getNavIndex(active.closest('a[href]') || active, bottomNav);
            if (idx !== null) sessionStorage.setItem('navIndex', String(idx));
          }
        }
      };

      document.addEventListener('DOMContentLoaded', () => {
        applyEnter();
        registerTransitionLinks();
      });
    })();
  </script>
</body>
</html>
