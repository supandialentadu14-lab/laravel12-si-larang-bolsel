<!DOCTYPE html>
<html lang="id">

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

  @vite(['resources/css/mobile.css', 'resources/js/mobile.js'])
  

  <script>
    (() => {
      const dir = sessionStorage.getItem('navDir');
      if (dir) document.documentElement.dataset.navDir = dir;
    })();
  </script>


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

  <div id="page-progress" class="fixed top-0 left-0 h-0.5 bg-indigo-600 z-[9999] transition-all duration-300 pointer-events-none" style="width: 0%"></div>

  @include('partials.mobile_bottom_nav')

  <script>
    (() => {
      const pageContent = document.getElementById('page-content');
      const progressBar = document.getElementById('page-progress');
      const bottomNav = document.querySelector('nav.bottom-nav');
      
      const setProgress = (w) => {
        progressBar.style.width = w + '%';
        if (w >= 100) {
          setTimeout(() => { 
            progressBar.style.opacity = '0';
            setTimeout(() => {
              progressBar.style.width = '0%';
              progressBar.style.opacity = '1';
            }, 300);
          }, 500);
        }
      };

      const startProgress = () => {
        progressBar.style.opacity = '1';
        let w = 5;
        setProgress(w);
        const inv = setInterval(() => {
          if (w < 90) {
            w += (90 - w) * 0.15;
          } else if (w < 98) {
            w += 0.2;
          }
          setProgress(w);
          if (w >= 98) clearInterval(inv);
        }, 150);
        return inv;
      };

      const updateContent = (html, url, push = true) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.getElementById('page-content');
        
        if (!newContent) {
            setProgress(100);
            window.location.href = url;
            return;
        }
        
        document.title = doc.title;
        pageContent.innerHTML = newContent.innerHTML;
        
        if (push) history.pushState({}, '', url);
        
        const scripts = pageContent.querySelectorAll('script');
        scripts.forEach(s => {
          const n = document.createElement('script');
          if (s.src) n.src = s.src;
          else n.textContent = s.textContent;
          pageContent.appendChild(n);
        });

        if (window.Alpine) Alpine.discover();
        pageContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        const newNav = doc.querySelector('nav.bottom-nav');
        if (newNav && bottomNav) bottomNav.innerHTML = newNav.innerHTML;
        
        setProgress(100);
      };

      const handleLinkClick = async (e) => {
        const link = e.target.closest('a[href]');
        if (!link || link.classList.contains('no-soft')) return;
        if (link.origin !== window.location.origin) return;
        if (link.target === '_blank' || e.metaKey || e.ctrlKey) return;
        
        const href = link.getAttribute('href');
        if (href.startsWith('#') || href.startsWith('javascript:')) return;
        
        e.preventDefault();
        const pid = startProgress();
        try {
          const res = await fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          const html = await res.text();
          clearInterval(pid);
          updateContent(html, res.url);
        } catch (err) {
          clearInterval(pid);
          setProgress(100);
          window.location.href = href;
        }
      };

      const handleFormSubmit = async (e) => {
        const form = e.target.closest('form');
        if (!form || form.classList.contains('no-soft')) return;
        if (form.method.toUpperCase() === 'GET') return;
        
        e.preventDefault();
        const pid = startProgress();
        const formData = new FormData(form);
        const action = form.getAttribute('action') || window.location.href;
        
        try {
          const res = await fetch(action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const html = await res.text();
          clearInterval(pid);
          updateContent(html, res.url);
        } catch (err) {
          clearInterval(pid);
          setProgress(100);
          form.submit();
        }
      };

      // document.addEventListener('click', handleLinkClick);
      // document.addEventListener('submit', handleFormSubmit);
      // window.addEventListener('popstate', ...);

      // Native validation message customization
      document.addEventListener('invalid', (function() {
        return function(e) {
          // e.preventDefault(); // Do not prevent default, keep the bubble
          const input = e.target;
          let label = '';
          
          const labelEl = input.closest('.space-y-1.5')?.querySelector('label') 
                        || input.parentElement?.querySelector('label')
                        || document.querySelector(`label[for="${input.id || ''}"]`);
                        
          if (labelEl) {
            label = labelEl.textContent.trim().split('(')[0].trim().replace(':', '');
          } else if (input.placeholder) {
            label = input.placeholder.split('...')[0].trim().replace('Masukkan ', '').replace('Input ', '').replace('Contoh: ', '');
          }
          
          if (!label || label.length > 30) {
            label = input.getAttribute('name') ? input.getAttribute('name').replace('_', ' ') : 'ini';
          }
          
          // Title case simple name
          if (label === label.toLowerCase()) {
            label = label.charAt(0).toUpperCase() + label.slice(1);
          }
          
          input.setCustomValidity('Kolom ' + label + ' harus diisi');
        };
      })(), true);

      // Listener to clear native validation message when user starts typing
      document.addEventListener('input', function(e) {
        if (e.target.willValidate) {
          e.target.setCustomValidity('');
        }
      }, true);

      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('/sw.js').catch(() => {});
        });
      }
    })();
  </script>
</body>
</html>
