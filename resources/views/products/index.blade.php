@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div x-data="{ showFilters: {{ request('search') || request('category_id') ? 'true' : 'false' }} }" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white transition-colors uppercase tracking-tight">Daftar Barang</h1>
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-1 transition-colors">Kelola Stok & Inventaris</p>
            </div>
            <div class="flex gap-2">
                <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50 dark:ring-indigo-900/20' : ''">
                    <i class="fas fa-filter text-xs"></i>
                </button>
                <a href="{{ route('products.create') }}" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 dark:shadow-indigo-900/40 flex items-center justify-center active:scale-90 transition-transform">
                    <i class="fas fa-plus text-xs"></i>
                </a>
            </div>
        </div>

        {{-- Import Action Button --}}
        <a href="{{ route('import.index') }}" class="w-full flex items-center justify-center gap-3 py-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm text-slate-600 dark:text-slate-400 active:scale-[0.98] transition-all group">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:bg-indigo-600 dark:group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                <i class="fas fa-file-import text-[10px]"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Impor Data Barang</span>
        </a>
    </div>

    {{-- Filter Card (App-like style) --}}
    <div x-show="showFilters" x-collapse x-cloak>
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 border border-slate-50 dark:border-slate-800 shadow-sm space-y-4">
            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2">Filter Pencarian</h3>
            <form action="{{ route('products.index') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4">Nama Barang</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 dark:text-slate-600 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4">Kategori</label>
                    <select name="category_id" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100 dark:shadow-none">
                        Terapkan
                    </button>
                    <a href="{{ route('products.index') }}" class="w-full py-4 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Card (App-like style) --}}
    <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 dark:shadow-none overflow-hidden relative group transition-all">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Nilai Persediaan</p>
            <h2 class="text-3xl font-black mt-2 tracking-tight">Rp{{ number_format($products->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') }}</h2>
            
            <div class="flex items-center gap-4 mt-6">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Item</span>
                    <span class="text-sm font-black">{{ $products->total() }} Jenis</span>
                </div>
                <div class="w-px h-8 bg-white/20"></div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Stok Rendah</span>
                    <span class="text-sm font-black text-rose-300">{{ $lowStockCount ?? 0 }} Item</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Product List --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Stok Barang</h3>
            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $products->count() }} Ditampilkan</span>
        </div>

        @forelse($products as $product)
        @php
            $stock = $product->stock;
            $min = $product->min_stock ?: 5;
            $isLow = $stock <= $min;
        @endphp
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-5 border border-slate-50 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
            <div class="flex items-start gap-4">
                {{-- Product Icon --}}
                <div class="w-14 h-14 rounded-[1.5rem] {{ $isLow ? 'bg-rose-50 dark:bg-rose-950/20 text-rose-500' : 'bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600' }} flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0 transition-colors">
                    {{ substr($product->name, 0, 1) }}
                </div>

                {{-- Product Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-tight truncate leading-tight transition-colors">{{ $product->name }}</h3>
                            <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-black text-slate-900 dark:text-white transition-colors">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-tighter">/ {{ $product->unit }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-5">
                        <div class="flex items-center gap-2">
                            <div class="px-4 py-1.5 rounded-full {{ $isLow ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white' }} flex items-center gap-2 shadow-sm">
                                <i class="fas {{ $isLow ? 'fa-exclamation-triangle' : 'fa-check-circle' }} text-[9px]"></i>
                                <span class="text-[9px] font-black tracking-widest">{{ $product->stock }} {{ strtoupper($product->unit) }}</span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('products.edit', $product->id) }}" class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" @click.prevent="if(confirm('Hapus barang ini?')) $el.form.submit()" class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center hover:bg-rose-50 dark:hover:bg-rose-950/40 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
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
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box-open text-3xl text-slate-200 dark:text-slate-700"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 uppercase tracking-widest">Tidak Ada Barang</h3>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 font-bold uppercase tracking-widest">Gunakan tombol (+) untuk menambah</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
@endsection
