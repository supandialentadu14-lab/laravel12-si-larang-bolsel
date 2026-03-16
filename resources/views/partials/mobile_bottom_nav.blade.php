<!-- Bottom Sheet Menu -->
<div x-show="mobileMenuOpen" data-mobile-sheet
  class="fixed inset-0 z-[9999] overflow-hidden lg:hidden" 
  x-transition:enter="transition ease-in-out duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in-out duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  x-cloak>
  
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

  <!-- Panel (Bottom Sheet) -->
  <div class="absolute inset-x-0 bottom-0 max-h-[85vh] w-full bg-white rounded-t-[2.5rem] shadow-2xl flex flex-col transition-colors duration-300"
    x-transition:enter="transition ease-out duration-500 transform"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-400 transform"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full">
    
    <!-- Sheet Handle -->
    <div class="w-full flex justify-center py-4" @click="mobileMenuOpen = false">
      <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
    </div>

    <!-- Menu Header -->
    <div class="px-8 pb-6 border-b border-gray-50 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black">
          <i class="fas fa-grid-2"></i>
        </div>
        <div>
          <h3 class="font-black text-sm tracking-widest text-gray-900 uppercase">MENU NAVIGASI</h3>
          <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Semua Akses Halaman</p>
        </div>
      </div>
      <button @click="mobileMenuOpen = false" class="btn-icon-mini bg-gray-50 text-gray-400">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Menu Content -->
    <div class="flex-1 overflow-y-auto py-6 px-6 space-y-2 no-scrollbar pb-24">
      <!-- Notifications Section -->
      @if (isset($lowStockCount) && $lowStockCount > 0)
      <div class="mb-6">
        <div class="flex items-center justify-between mb-4 px-2">
          <p class="text-[9px] font-black text-red-500 uppercase tracking-[0.2em]">Peringatan Stok</p>
          <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[8px] font-black rounded-full uppercase">{{ $lowStockCount }} Item</span>
        </div>
        <div class="space-y-2">
          @foreach ($lowStockProducts as $lp)
          <a href="{{ route('products.index', ['search' => $lp->name]) }}" class="flex items-center gap-4 px-4 py-4 rounded-2xl bg-red-50/50 border border-red-100/50 transition active:scale-[0.98]">
            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm shadow-red-100 ">
              <i class="fas fa-exclamation-triangle text-xs"></i>
            </div>
            <div class="min-w-0">
              <p class="text-[11px] font-black text-red-900 truncate uppercase tracking-tight">{{ $lp->name }}</p>
              <p class="text-[9px] font-bold text-red-600 mt-1 uppercase tracking-widest">Tersisa: {{ $lp->stock }} {{ $lp->unit }}</p>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif

      <div class="grid grid-cols-1 gap-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 ' }}">
          <div class="flex items-center gap-4">
            <i class="fas fa-tachometer-alt w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Dashboard</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>

        <!-- Master Data Group -->
        @if(auth()->user()->hasPermission('master_data'))
        <div x-data="{ open: {{ request()->routeIs('categories.*') || request()->routeIs('products.*') || request()->routeIs('suppliers.*') ? 'true' : 'false' }} }" class="space-y-2">
          <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('categories.*') || request()->routeIs('products.*') || request()->routeIs('suppliers.*') ? 'bg-indigo-50 text-indigo-600 shadow-inner' : 'bg-gray-50 text-gray-600 ' }}">
            <div class="flex items-center gap-4">
              <i class="fas fa-boxes w-5 text-center"></i>
              <span class="text-[11px] font-black uppercase tracking-widest">Master Data</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="open" x-collapse class="px-2 space-y-1">
            <a href="{{ route('categories.index') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('categories.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('categories.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Kategori
            </a>
            <a href="{{ route('suppliers.index') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('suppliers.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('suppliers.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Penyedia
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center justify-between px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('products.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="flex items-center gap-4">
                <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('products.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
                Barang
              </div>
              @if (isset($lowStockCount) && $lowStockCount > 0)
                <span class="px-1.5 py-0.5 bg-red-500 text-white text-[8px] rounded-full">{{ $lowStockCount }}</span>
              @endif
            </a>
          </div>
        </div>
        @endif

        <!-- Transaksi Group -->
        @if(auth()->user()->hasPermission('transaksi') || auth()->user()->hasPermission('laporan_belanja'))
        <div x-data="{ open: {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.list') ? 'true' : 'false' }} }" class="space-y-2">
          <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.list') ? 'bg-indigo-50 text-indigo-600 shadow-inner' : 'bg-gray-50 text-gray-600 ' }}">
            <div class="flex items-center gap-4">
              <i class="fas fa-exchange-alt w-5 text-center"></i>
              <span class="text-[11px] font-black uppercase tracking-widest">Transaksi</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="open" x-collapse class="px-2 space-y-1">
            @if(auth()->user()->hasPermission('transaksi'))
            <a href="{{ route('stock.index') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('stock.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('stock.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Mutasi Masuk/Keluar
            </a>
            @endif
            @if(auth()->user()->hasPermission('laporan_belanja'))
            <a href="{{ route('reports.belanja.modal.list') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('reports.belanja.modal.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.belanja.modal.*') ? 'text-indigo-600' : 'bg-gray-200 ' }}"></div>
              Daftar Belanja Modal
            </a>
            @endif
          </div>
        </div>
        @endif

        <!-- Berkas Group -->
        @if(auth()->user()->hasPermission('surat_pesanan') || auth()->user()->hasPermission('pemeriksaan') || auth()->user()->hasPermission('penerimaan') || auth()->user()->hasPermission('berkas_lainnya'))
        <div x-data="{ open: {{ request()->routeIs('reports.nota.list') || request()->routeIs('reports.pemeriksaan.list') || request()->routeIs('reports.penerimaan.list') || request()->routeIs('reports.kwitansi.list') ? 'true' : 'false' }} }" class="space-y-2">
          <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.nota.list') || request()->routeIs('reports.pemeriksaan.list') || request()->routeIs('reports.penerimaan.list') || request()->routeIs('reports.kwitansi.list') ? 'bg-indigo-50 text-indigo-600 shadow-inner' : 'bg-gray-50 text-gray-600 ' }}">
            <div class="flex items-center gap-4">
              <i class="fas fa-receipt w-5 text-center"></i>
              <span class="text-[11px] font-black uppercase tracking-widest">Berkas</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
          </button>
          <div x-show="open" x-collapse class="px-2 space-y-1">
            @if(auth()->user()->hasPermission('surat_pesanan'))
            <a href="{{ route('reports.nota.list') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('reports.nota.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.nota.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Daftar Surat Pesanan
            </a>
            @endif
            @if(auth()->user()->hasPermission('pemeriksaan'))
            <a href="{{ route('reports.pemeriksaan.list') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('reports.pemeriksaan.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.pemeriksaan.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Daftar Pemeriksaan
            </a>
            @endif
            @if(auth()->user()->hasPermission('penerimaan'))
            <a href="{{ route('reports.penerimaan.list') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('reports.penerimaan.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.penerimaan.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Daftar Penerimaan
            </a>
            @endif
            @if(auth()->user()->hasPermission('berkas_lainnya'))
            <a href="{{ route('reports.kwitansi.list') }}" class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ request()->routeIs('reports.kwitansi.*') ? 'text-indigo-600 bg-white shadow-sm' : 'text-gray-400 ' }}">
              <div class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('reports.kwitansi.*') ? 'bg-indigo-600' : 'bg-gray-200 ' }}"></div>
              Daftar Kwitansi
            </a>
            @endif
          </div>
        </div>
        @endif

        <div class="pt-8 pb-4">
          <p class="px-5 text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] mb-4">Pengaturan Sistem</p>
          
          <div class="space-y-2">
            <a href="{{ route('settings.opd.edit') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('settings.opd.*') ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 ' }}">
              <div class="flex items-center gap-4">
                <i class="fas fa-gear w-5 text-center"></i>
                <span class="text-[11px] font-black uppercase tracking-widest">Instansi</span>
              </div>
              <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 ' }}">
              <div class="flex items-center gap-4">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="text-[11px] font-black uppercase tracking-widest">Pengguna</span>
              </div>
              <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
            </a>
            <a href="{{ route('activity_log.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('activity_log.*') ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 ' }}">
              <div class="flex items-center gap-4">
                <i class="fas fa-history w-5 text-center"></i>
                <span class="text-[11px] font-black uppercase tracking-widest">Log Aktivitas</span>
              </div>
              <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
            </a>
            @endif
          </div>
        </div>
      </div>

      <!-- Logout Section -->
      <div class="p-8 border-t border-gray-50 transition-colors">
        <form method="POST" action="{{ route('logout') }}" class="no-soft">
          @csrf
          <button type="submit" class="w-full flex items-center justify-center gap-3 px-6 py-5 rounded-[1.5rem] bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-[0.15em] transition active:scale-95 border border-transparent ">
            <i class="fas fa-sign-out-alt"></i>
            <span>LOGOUT</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@if(auth()->user()->hasPermission('master_data'))
<!-- Bottom Sheet Master Data -->
<div x-show="masterMenuOpen"
  data-mobile-sheet
  class="fixed inset-0 z-[10000] overflow-hidden lg:hidden"
  x-transition:enter="transition ease-in-out duration-250"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in-out duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  x-cloak>

  <div class="absolute inset-0 bg-gray-900/30 backdrop-blur-md" @click="masterMenuOpen = false"></div>

  <div class="absolute inset-x-0 bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-2xl"
    x-transition:enter="transition ease-out duration-350 transform"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-250 transform"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full">

    <div class="w-full flex justify-center py-4" @click="masterMenuOpen = false">
      <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
    </div>

    <div class="px-8 pb-6 border-b border-gray-50 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
          <i class="fas fa-boxes"></i>
        </div>
        <div>
          <h3 class="font-black text-sm tracking-widest text-gray-900 uppercase">MASTER DATA</h3>
          <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Kategori, Barang, Penyedia</p>
        </div>
      </div>
      <button type="button" @click="masterMenuOpen = false" class="btn-icon-mini bg-gray-50 text-gray-400">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="p-6 pb-10">
      <div class="grid grid-cols-1 gap-2">
        <a data-master-target href="{{ route('categories.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="masterMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-tags w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Kategori</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
<a data-master-target href="{{ route('suppliers.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('suppliers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="masterMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-truck w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Penyedia</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        <a data-master-target href="{{ route('products.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="masterMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-boxes w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Barang</span>
          </div>
          @if (isset($lowStockCount) && $lowStockCount > 0)
            <span class="px-2 py-0.5 bg-red-500 text-white text-[8px] font-black rounded-full">{{ $lowStockCount }}</span>
          @else
            <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
          @endif
        </a>

        
      </div>
    </div>
  </div>
</div>
@endif

<!-- Bottom Sheet Pengaturan -->
<div x-show="settingsMenuOpen"
  data-mobile-sheet
  class="fixed inset-0 z-[10000] overflow-hidden lg:hidden"
  x-transition:enter="transition ease-in-out duration-250"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in-out duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  x-cloak>

  <div class="absolute inset-0 bg-gray-900/30 backdrop-blur-md" @click="settingsMenuOpen = false"></div>

  <div class="absolute inset-x-0 bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-2xl"
    x-transition:enter="transition ease-out duration-350 transform"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-250 transform"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full">

    <div class="w-full flex justify-center py-4" @click="settingsMenuOpen = false">
      <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
    </div>

    <div class="px-8 pb-6 border-b border-gray-50 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
          <i class="fas fa-gear"></i>
        </div>
        <div>
          <h3 class="font-black text-sm tracking-widest text-gray-900 uppercase">PENGATURAN</h3>
          <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Instansi & Akun</p>
        </div>
      </div>
      <button type="button" @click="settingsMenuOpen = false" class="btn-icon-mini bg-gray-50 text-gray-400">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="p-6 pb-10">
      <div class="grid grid-cols-1 gap-2">
        <a data-settings-target href="{{ route('settings.opd.edit') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('settings.opd.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="settingsMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-university w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Instansi</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>

        @if(auth()->user()->isAdmin())
        <a data-settings-target href="{{ route('users.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="settingsMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-users-gear w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Pengguna</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>

        <a data-settings-target href="{{ route('activity_log.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('activity_log.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="settingsMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-history w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Log Aktivitas</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>

@if(
  auth()->user()->hasPermission('transaksi') ||
  auth()->user()->hasPermission('laporan_belanja') ||
  auth()->user()->hasPermission('surat_pesanan') ||
  auth()->user()->hasPermission('pemeriksaan') ||
  auth()->user()->hasPermission('penerimaan') ||
  auth()->user()->hasPermission('berkas_lainnya') ||
  auth()->user()->hasPermission('stock_opname') ||
  auth()->user()->hasPermission('pinjam_pakai')
)
<!-- Bottom Sheet Transaksi & Berkas -->
<div x-show="flowMenuOpen"
  data-mobile-sheet
  class="fixed inset-0 z-[10000] overflow-hidden lg:hidden"
  x-transition:enter="transition ease-in-out duration-250"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition ease-in-out duration-200"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  x-cloak>

  <div class="absolute inset-0 bg-gray-900/30 backdrop-blur-md" @click="flowMenuOpen = false"></div>

  <div class="absolute inset-x-0 bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-2xl"
    x-transition:enter="transition ease-out duration-350 transform"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-250 transform"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full">

    <div class="w-full flex justify-center py-4" @click="flowMenuOpen = false">
      <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
    </div>

    <div class="px-8 pb-6 border-b border-gray-50 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
          <i class="fas fa-folder-open"></i>
        </div>
        <div>
          <h3 class="font-black text-sm tracking-widest text-gray-900 uppercase">TRANSAKSI & BERKAS</h3>
          <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Mutasi & Dokumen</p>
        </div>
      </div>
      <button type="button" @click="flowMenuOpen = false" class="btn-icon-mini bg-gray-50 text-gray-400">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <div class="p-6 pb-10">
      <div class="grid grid-cols-1 gap-2">
        @if(auth()->user()->hasPermission('transaksi'))
        <a data-flow-target href="{{ route('stock.index') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('stock.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-exchange-alt w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Mutasi Masuk/Keluar</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('laporan_belanja'))
        <a data-flow-target href="{{ route('reports.belanja.modal.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.belanja.modal.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-file-contract w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Daftar Belanja Modal</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('surat_pesanan'))
        <a data-flow-target href="{{ route('reports.nota.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.nota.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-file-invoice w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Nota Pesanan</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('pemeriksaan'))
        <a data-flow-target href="{{ route('reports.pemeriksaan.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.pemeriksaan.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-check-double w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Pemeriksaan</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('penerimaan'))
        <a data-flow-target href="{{ route('reports.penerimaan.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.penerimaan.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-file-download w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Penerimaan</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('berkas_lainnya'))
        <a data-flow-target href="{{ route('reports.kwitansi.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.kwitansi.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-receipt w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Kwitansi</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('stock_opname'))
        <a data-flow-target href="{{ route('reports.opname.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.opname.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-clipboard-check w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Berita Acara Opname</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif

        @if(auth()->user()->hasPermission('pinjam_pakai'))
        <a data-flow-target href="{{ route('reports.pinjam.list') }}" class="flex items-center justify-between px-5 py-4 rounded-2xl transition {{ request()->routeIs('reports.pinjam.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}" @click="flowMenuOpen = false">
          <div class="flex items-center gap-4">
            <i class="fas fa-people-carry-box w-5 text-center"></i>
            <span class="text-[11px] font-black uppercase tracking-widest">Pinjam Pakai</span>
          </div>
          <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

<!-- Bottom Navigation (Full Scrollable & Auto Center) -->
<nav x-init="$nextTick(() => { const activeItem = $el.querySelector('.active-menu'); if (activeItem) activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' }); })"
  :class="scrollingDown ? 'translate-y-full opacity-0 pointer-events-none' : 'translate-y-0 opacity-100 pointer-events-auto'"
  class="transition-all duration-500 ease-in-out transform fixed bottom-0 left-0 right-0 z-[9999] glass-card border-t border-gray-100/50 bottom-nav shadow-[0_-4px_20px_rgba(0,0,0,0.08)] lg:hidden">
  <div class="flex items-center justify-center min-w-full overflow-x-auto no-scrollbar py-4 scroll-smooth snap-x snap-mandatory">
    <!-- Master Barang (Left 1) -->
    @if(auth()->user()->hasPermission('master_data'))
    <a data-master-nav data-skip-transition href="{{ route('products.index') }}" @click.prevent="masterMenuOpen = true" class="no-soft flex flex-col items-center gap-1 shrink-0 flex-1 snap-center {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'active-menu scale-110' : '' }}">
      <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-50 text-slate-400' }}">
        <i class="fas fa-layer-group text-lg"></i>
      </div>
      <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'text-indigo-600' : 'text-slate-500 font-bold' }}">Master</span>
    </a>
    @endif

    <!-- Berkas (Left 2) -->
    @if(
      auth()->user()->hasPermission('transaksi') ||
      auth()->user()->hasPermission('laporan_belanja') ||
      auth()->user()->hasPermission('surat_pesanan') ||
      auth()->user()->hasPermission('pemeriksaan') ||
      auth()->user()->hasPermission('penerimaan') ||
      auth()->user()->hasPermission('berkas_lainnya') ||
      auth()->user()->hasPermission('stock_opname') ||
      auth()->user()->hasPermission('pinjam_pakai')
    )
    <a data-flow-nav data-skip-transition href="{{ route('stock.index') }}" @click.prevent="flowMenuOpen = true" class="no-soft flex flex-col items-center gap-1 shrink-0 flex-1 snap-center {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'active-menu scale-110' : '' }}">
      <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 ' : 'bg-slate-50 text-slate-400 ' }}">
        <i class="fas fa-folder-open text-lg"></i>
      </div>
      <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'text-indigo-600 ' : 'text-slate-500 font-bold' }}">Berkas</span>
    </a>
    @endif

    <!-- Beranda (Always Center) -->
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 shrink-0 flex-1 snap-center {{ request()->routeIs('dashboard') ? 'active-menu scale-110' : '' }}">
      <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-slate-50 text-slate-400 border border-slate-100' }}">
        <i class="fas fa-home text-lg"></i>
      </div>
      <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-slate-500 font-bold' }}">Beranda</span>
    </a>

    <!-- Manajemen (Right 1) -->
    <a data-settings-nav data-skip-transition href="{{ route('settings.opd.edit') }}" @click.prevent="settingsMenuOpen = true" class="no-soft flex flex-col items-center gap-1 shrink-0 flex-1 snap-center {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'active-menu scale-110' : '' }}">
      <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 ' : 'bg-slate-50 text-slate-400 ' }}">
        <i class="fas fa-sliders text-lg"></i>
      </div>
      <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'text-indigo-600 ' : 'text-slate-500 font-bold' }}">Manajemen</span>
    </a>

    <!-- Profil (Right 2) -->
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 shrink-0 flex-1 snap-center {{ request()->routeIs('profile.*') ? 'active-menu scale-110' : '' }}">
      <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('profile.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 ' : 'bg-slate-50 text-slate-400 ' }}">
        <i class="fas fa-user-circle text-lg"></i>
      </div>
      <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('profile.*') ? 'text-indigo-600 ' : 'text-slate-500 font-bold' }}">Profil</span>
    </a>
  </div>
</nav>
