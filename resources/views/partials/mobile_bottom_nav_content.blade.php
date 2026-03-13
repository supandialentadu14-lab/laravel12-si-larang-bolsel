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
    <div class="absolute inset-x-0 bottom-0 max-h-[85vh] w-full bg-white rounded-t-[2.5rem] shadow-2xl flex flex-col"
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
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
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
            <!-- Menu items content here... -->
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

<nav x-data="{ 
    init() { 
        this.$nextTick(() => {
            const activeItem = this.$el.querySelector('.active-menu');
            if (activeItem) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        });
    }
}" class="w-full glass-card border-t border-gray-100/50 bottom-nav shadow-[0_-4px_20px_rgba(0,0,0,0.08)] lg:hidden">
    <div class="flex items-center overflow-x-auto no-scrollbar py-4 scroll-smooth snap-x snap-mandatory">
        <!-- Master Barang -->
        @if(auth()->user()->hasPermission('master_data'))
        <a data-master-nav data-skip-transition href="{{ route('products.index') }}" @click.prevent="masterMenuOpen = true" class="flex flex-col items-center gap-1 shrink-0 basis-1/5 snap-center {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'active-menu scale-110' : 'text-gray-300' }}">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-transparent text-gray-300' }}">
                <i class="fas fa-layer-group text-lg"></i>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'text-indigo-600' : 'text-gray-300' }}">Master</span>
        </a>
        @endif

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
        <a data-flow-nav data-skip-transition href="{{ route('stock.index') }}" @click.prevent="flowMenuOpen = true" class="flex flex-col items-center gap-1 shrink-0 basis-1/5 snap-center {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'active-menu scale-110' : 'text-gray-300' }}">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-transparent text-gray-300' }}">
                <i class="fas fa-folder-open text-lg"></i>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('stock.*') || request()->routeIs('reports.belanja.modal.*') || request()->routeIs('reports.nota.*') || request()->routeIs('reports.pemeriksaan.*') || request()->routeIs('reports.penerimaan.*') || request()->routeIs('reports.kwitansi.*') || request()->routeIs('reports.opname.*') || request()->routeIs('reports.pinjam.*') ? 'text-indigo-600' : 'text-gray-300' }}">Berkas</span>
        </a>
        @endif

        <!-- Beranda -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 shrink-0 basis-1/5 snap-center {{ request()->routeIs('dashboard') ? 'active-menu scale-110' : 'text-gray-300' }}">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-transparent text-gray-300' }}">
                <i class="fas fa-home text-lg"></i>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-300' }}">Beranda</span>
        </a>

        <a data-settings-nav data-skip-transition href="{{ route('settings.opd.edit') }}" @click.prevent="settingsMenuOpen = true" class="flex flex-col items-center gap-1 shrink-0 basis-1/5 snap-center {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'active-menu scale-110' : 'text-gray-300' }}">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-transparent text-gray-300' }}">
                <i class="fas fa-sliders text-lg"></i>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('settings.opd.*') || request()->routeIs('users.*') || request()->routeIs('activity_log.*') ? 'text-indigo-600' : 'text-gray-300' }}">Manajemen</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 shrink-0 basis-1/5 snap-center {{ request()->routeIs('profile.*') ? 'active-menu scale-110' : 'text-gray-300' }}">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center transition-all duration-300 {{ request()->routeIs('profile.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-transparent text-gray-300' }}">
                <i class="fas fa-user-circle text-lg"></i>
            </div>
            <span class="text-[8px] font-black uppercase tracking-widest whitespace-nowrap {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-300' }}">Profil</span>
        </a>
    </div>
</nav>
