<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="view-transition" content="same-origin">
  
  <!-- PWA Setup -->
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#4f46e5">
  <link rel="apple-touch-icon" href="/images/silarang-logo.png">

  <title>{{ config('app.name', 'Inventory Mobile') }}</title>
  <link rel="icon" type="image/webp" href="{{ asset('images/silarang-logo.webp') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <script src="https://cdn.tailwindcss.com"></script>
  @vite(['resources/css/mobile.css', 'resources/js/mobile.js'])
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  

  <script>
    (() => {
      const dir = sessionStorage.getItem('navDir');
      if (dir) document.documentElement.dataset.navDir = dir;
    })();
  </script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

  <style>
    /* FORCE STYLE FOR PRODUCTION - Bypassing Cache */
    nav.bottom-nav {
      background-color: #ffffff !important;
      background: rgba(255, 255, 255, 0.98) !important;
      backdrop-filter: blur(25px) saturate(200%) !important;
      -webkit-backdrop-filter: blur(25px) saturate(200%) !important;
      border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    /* Fix for date input and general input stretching */
    input[type="date"], 
    input[type="text"], 
    input[type="number"],
    select, 
    textarea {
      max-width: 100% !important;
      width: 100% !important;
      box-sizing: border-box !important;
      display: block !important;
      appearance: none !important;
      -webkit-appearance: none !important;
    }

    /* Ensure specific cards don't overflow */
    .bg-app-surface {
      max-width: 100% !important;
      overflow-x: hidden !important;
    }
  </style>
</head>

  <body class="antialiased select-none overflow-hidden" 
  style="height: 100vh; height: 100dvh;"
  x-data="{ 
    mobileMenuOpen: false, 
    masterMenuOpen: false, 
    flowMenuOpen: false, 
    settingsMenuOpen: false, 
    profileMenuOpen: false,
    notifOpen: false,
    unreadChatCount: 0,
    chatNotifOpen: false,
    latestChatMessage: null,
    scrollingDown: false,
    lastScrollTop: 0,
    
    async checkNewMessages() {
      @if(auth()->check() && (auth()->user()->chat_enabled || auth()->user()->isAdmin()))
      try {
        const res = await fetch('{{ route('chat.unread') }}');
        const data = await res.json();
        
        if (data.count > this.unreadChatCount) {
          this.latestChatMessage = data.messages[0];
          // Only show top sheet if we are NOT on the chat page of that user
          if (!window.location.pathname.includes('/chat/')) {
            this.chatNotifOpen = true;
            setTimeout(() => { this.chatNotifOpen = false; }, 5000);
          }
        }
        this.unreadChatCount = data.count;
      } catch (e) {}
      @endif
    },

    handleScroll(e) {
      let st = e.target.scrollTop;
      if (st > this.lastScrollTop && st > 50) {
        this.scrollingDown = true;
      } else {
        this.scrollingDown = false;
      }
      this.lastScrollTop = st <= 0 ? 0 : st;
    }
  }"
  x-init="checkNewMessages(); setInterval(() => checkNewMessages(), 10000)">
  <div class="flex flex-col h-full overflow-hidden">
    <div class="sticky top-0 z-[45]">
      @include('partials.mobile_header')
    </div>

    <!-- Main Content -->
    <main id="page-content" class="flex-1 px-5 pt-4 pb-32 overflow-y-auto" @scroll="handleScroll">
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

  <!-- Low Stock Notification Sheet (Top Position) -->
  <div x-show="notifOpen" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 -translate-y-full" 
    x-transition:enter-end="opacity-100 translate-y-0" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100 translate-y-0" 
    x-transition:leave-end="opacity-0 -translate-y-full" 
    class="fixed inset-0 z-[60] flex items-start">
    <div @click="notifOpen = false" class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    <div class="relative w-full bg-white rounded-b-[32px] overflow-hidden shadow-2xl p-6 pt-12 pb-6 max-h-[85vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-8 mt-2">
        <div>
          <h2 class="text-xl font-black text-gray-900 tracking-tight">Notifikasi</h2>
          <p class="text-xs font-bold text-gray-500 mt-1 uppercase tracking-widest">Informasi Penting</p>
        </div>
        <button @click="notifOpen = false" class="w-10 h-10 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="space-y-4 mb-4">
        @if (isset($lowStockProducts) && $lowStockProducts->count() > 0)
          @foreach ($lowStockProducts as $item)
            <div class="p-5 bg-rose-50 border border-rose-100 rounded-[24px]">
              <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm shadow-rose-200">
                  <i class="fas fa-triangle-exclamation text-xs"></i>
                </div>
                <div>
                  <h3 class="text-xs font-black text-rose-900 tracking-tight">{{ $item->name }}</h3>
                  <p class="text-[11px] font-bold text-rose-700 mt-1 leading-snug">Stok tersisa tinggal {{ $item->stock }} {{ $item->unit }}. Segera lakukan pengadaan baru.</p>
                </div>
              </div>
            </div>
          @endforeach
        @else
          <div class="py-12 text-center">
            <div class="w-16 h-16 rounded-3xl bg-gray-50 flex items-center justify-center text-gray-300 mx-auto mb-4">
              <i class="fas fa-bell-slash text-xl"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada notifikasi baru</p>
          </div>
        @endif
      </div>
      <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4"></div>
    </div>
  </div>

  <!-- Chat Notification Sheet (Top Position) -->
  <div x-show="chatNotifOpen" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 -translate-y-full" 
    x-transition:enter-end="opacity-100 translate-y-0" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100 translate-y-0" 
    x-transition:leave-end="opacity-0 -translate-y-full" 
    class="fixed top-4 left-4 right-4 z-[70]">
    <a :href="latestChatMessage ? '{{ url('chat') }}/' + latestChatMessage.sender_id : '{{ route('chat.index') }}'" class="block bg-indigo-600 rounded-3xl p-5 shadow-2xl shadow-indigo-200 border border-indigo-500/30 backdrop-blur-md">
      <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
          <i class="fas fa-comment-dots text-white text-xs"></i>
        </div>
        <div class="min-w-0 pr-4">
          <div class="text-[9px] font-black text-indigo-100 uppercase tracking-widest">Pesan Baru</div>
          <h3 class="text-xs font-black text-white tracking-tight mt-0.5" x-text="latestChatMessage ? latestChatMessage.sender_name : ''"></h3>
          <p class="text-[11px] font-bold text-indigo-50 mt-1 leading-snug truncate" x-text="latestChatMessage ? latestChatMessage.message : ''"></p>
        </div>
        <button @click.prevent="chatNotifOpen = false" class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
          <i class="fas fa-times text-[10px]"></i>
        </button>
      </div>
    </a>
  </div>

  @include('partials.mobile_bottom_nav')

  <script>
    (() => {
      const supportsViewTransition = 'startViewTransition' in document;
      const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const content = document.getElementById('page-content');
      const bottomNav = document.querySelector('nav.bottom-nav');
      const sheets = document.querySelectorAll('[x-show$="Open"]');

      const applyEnter = () => {
        const dir = sessionStorage.getItem('navDir') || 'right';
        if (content) content.classList.add(dir === 'right' ? 'page-enter-right' : 'page-enter-left');
        if (bottomNav) bottomNav.classList.add(dir === 'right' ? 'nav-enter-right' : 'nav-enter-left');
        sessionStorage.removeItem('navDir');
      };

      const registerTransitionLinks = () => {
        const normalizePath = (p) => {
          if (!p) return '';
          let path = p.split('?')[0].split('#')[0];
          if (path.startsWith(window.location.origin)) path = path.slice(window.location.origin.length);
          return path.replace(/\/$/, '') || '/';
        };

        const getNavIndex = (el, scope) => {
          const links = Array.from(scope.querySelectorAll('a[href]'));
          const idx = links.indexOf(el);
          return idx === -1 ? null : idx;
        };

        const globalIndex = new Map();
        const masterIndex = 0;
        const flowIndex = 1;
        const settingsIndex = 3;

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

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
          window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
              .then(reg => console.log('Service Worker registered'))
              .catch(err => console.log('Service Worker registration failed', err));
          });
        }
      });
    })();
  </script>
</body>
</html>
