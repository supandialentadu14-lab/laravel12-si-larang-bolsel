@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div x-data="{
    showFilters: {{ request('search') ? 'true' : 'false' }}
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white transition-colors uppercase tracking-tight">Jenis Belanja</h1>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-1 transition-colors">Kategori Inventaris</p>
        </div>
        <div class="flex gap-2">
            <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50 dark:ring-indigo-900/20' : ''">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <a href="{{ route('categories.create') }}" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 dark:shadow-none flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-plus text-xs"></i>
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div x-show="showFilters" x-collapse x-cloak>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 border border-slate-50 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2 transition-colors">Cari Kategori</h3>
            <form action="{{ route('categories.index') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4 transition-colors">Nama Kategori</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 dark:text-slate-600 text-xs transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jenis belanja..." class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100 dark:shadow-none transition-all">
                        Cari
                    </button>
                    <a href="{{ route('categories.index') }}" class="w-full py-4 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="bg-emerald-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-emerald-100 dark:shadow-none overflow-hidden relative group transition-all">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Kategori</p>
            <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $categories->total() }} Jenis</h2>
            <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest">Digunakan oleh {{ \App\Models\Product::count() }} Item Barang</p>
        </div>
    </div>

    {{-- Category List --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] transition-colors">Daftar Jenis Belanja</h3>
            <span class="text-[9px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-widest transition-colors">{{ $categories->count() }} Kategori</span>
        </div>

        @forelse($categories as $category)
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-5 border border-slate-50 dark:border-slate-800 shadow-sm hover:shadow-xl dark:hover:shadow-indigo-500/5 transition-all duration-300">
            <div class="flex items-start gap-4">
                {{-- Category Icon --}}
                <div class="w-14 h-14 rounded-[1.5rem] bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0 transition-colors">
                    {{ substr($category->name, 0, 1) }}
                </div>

                {{-- Category Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight truncate leading-tight transition-colors">{{ $category->name }}</h3>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1 line-clamp-1 transition-colors">{{ $category->description ?: 'Tidak ada keterangan' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-5">
                        <div class="flex items-center gap-2">
                            <div class="px-4 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center gap-2 transition-colors">
                                <i class="fas fa-box text-[9px] opacity-40"></i>
                                <span class="text-[9px] font-black tracking-widest uppercase">{{ $category->products_count }} ITEM</span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('categories.edit', $category->id) }}" class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" @click.prevent="if(confirm('Hapus jenis belanja ini?')) $el.form.submit()" class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center hover:bg-rose-50 dark:hover:bg-rose-950/40 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-16 text-center border border-slate-50 dark:border-slate-800 shadow-sm transition-colors">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 transition-colors">
                <i class="fas fa-tags text-3xl text-slate-200 dark:text-slate-700"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest transition-colors">Tidak Ada Kategori</h3>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 font-bold uppercase tracking-widest transition-colors">Klik (+) untuk menambah kategori baru</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $categories->links() }}
    </div>
</div>
@endsection
