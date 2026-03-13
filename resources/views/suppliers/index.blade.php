@extends('layouts.mobile')

@section('content')
<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    editData: {},
    editUrl: '',
    showFilters: {{ request('search') ? 'true' : 'false' }}
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Penyedia</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Vendor & Supplier</p>
        </div>
        <div class="flex gap-2">
            <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50' : ''">
                <i class="fas fa-filter text-xs"></i>
            </button>
            <button @click="showCreateModal = true" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-plus text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Filter Card --}}
    <div x-show="showFilters" x-collapse x-cloak>
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Cari Penyedia</h3>
            <form action="{{ route('suppliers.index') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama atau NPWP</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penyedia..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">
                        Cari
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="bg-blue-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-blue-100 overflow-hidden relative group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Rekanan</p>
            <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $suppliers->total() }} Penyedia</h2>
            <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest">Telah Bekerja Sama</p>
        </div>
    </div>

    {{-- Supplier List --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Rekanan Penyedia</h3>
            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $suppliers->count() }} Ditampilkan</span>
        </div>

        @forelse($suppliers as $supplier)
        <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
            <div class="flex items-start gap-4">
                {{-- Supplier Icon --}}
                <div class="w-14 h-14 rounded-[1.5rem] bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">
                    {{ substr($supplier->name, 0, 1) }}
                </div>

                {{-- Supplier Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate leading-tight">{{ $supplier->name }}</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Dir: {{ $supplier->dir ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex items-center gap-2 text-slate-500">
                            <i class="fas fa-map-marker-alt text-[9px] opacity-40"></i>
                            <span class="text-[10px] font-medium truncate">{{ $supplier->address }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-mono bg-slate-50 px-2 py-1 rounded-lg text-slate-400 border border-slate-100 uppercase">NPWP: {{ $supplier->npwp }}</span>
                            
                            {{-- Quick Actions --}}
                            <div class="flex items-center gap-1.5">
                                <button @click="
                                    showEditModal = true;
                                    editData = {
                                        id: '{{ $supplier->id }}',
                                        name: '{{ addslashes($supplier->name) }}',
                                        dir: '{{ addslashes($supplier->dir) }}',
                                        address: '{{ addslashes($supplier->address) }}',
                                        npwp: '{{ $supplier->npwp }}'
                                    };
                                    editUrl = '{{ route('suppliers.update', $supplier->id) }}';
                                " class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </button>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" @click.prevent="if(confirm('Hapus penyedia ini?')) $el.form.submit()" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-store-slash text-3xl text-slate-200"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tidak Ada Penyedia</h3>
            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">Klik (+) untuk menambah penyedia baru</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $suppliers->links() }}
    </div>

    @include('suppliers.partials.modals')

</div>
@endsection
