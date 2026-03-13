@extends('layouts.mobile')

@section('content')
<div x-data="{
    selected: [],
    allSelected: false,
    showCreateModal: false,
    showEditModal: false,
    editData: {},
    editUrl: '',
    showFilters: {{ request('search') ? 'true' : 'false' }},
    
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = [
                @foreach ($transactions as $transaction)
                    '{{ $transaction->id }}',
                @endforeach
            ];
        } else {
            this.selected = [];
        }
    },
    updateSelectAll() {
        this.allSelected = this.selected.length === {{ count($transactions) }};
    },
    formatNosur(el, dateStr) {
        if (!el || !dateStr) return;
        let val = el.value.trim();
        if (/^\d+$/.test(val) && !val.includes('/')) {
            const romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            const dateVal = new Date(dateStr);
            if (!isNaN(dateVal.getTime())) {
                const month = dateVal.getMonth() + 1;
                const year = dateVal.getFullYear();
                const formatted = `${val}/BAPB/{{ $singkatanOpd ?? 'DISKOMINFO' }}/${romans[month]}/${year}`;
                el.value = formatted;
                el.dispatchEvent(new Event('input'));
            }
        }
    }
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Mutasi Stok</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Transaksi Masuk & Keluar</p>
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
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Cari Transaksi</h3>
            <form action="{{ route('stock.index') }}" method="GET" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Barang atau No. Surat</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">
                        Terapkan
                    </button>
                    <a href="{{ route('stock.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Transaksi</p>
                <i class="fas fa-exchange-alt opacity-20"></i>
            </div>
            <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $transactions->total() }} Record</h2>
            <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest">Periode Berjalan</p>
        </div>
    </div>

    {{-- Transaction List --}}
    <div class="space-y-6 pb-24">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Riwayat Transaksi</h3>
            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $transactions->total() }} Record</span>
        </div>

        @php 
            $runningStock = []; 
            $grouped = $groupedTransactions ?? collect($transactions->items())->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });
        @endphp

        @forelse($grouped as $date => $txs)
            <div class="space-y-4">
                <div class="flex items-center gap-3 px-4">
                    <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                    <div class="h-[1px] flex-1 bg-gradient-to-r from-indigo-100 to-transparent"></div>
                </div>

                @foreach($txs as $transaction)
                    @php
                        $productId = $transaction->product->id;
                        if (!isset($runningStock[$productId])) { $runningStock[$productId] = 0; }
                        if ($transaction->type === 'in') { $runningStock[$productId] += $transaction->quantity; } 
                        else { $runningStock[$productId] -= $transaction->quantity; }
                        $saldoAkhir = $runningStock[$productId];
                    @endphp
                    <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 relative overflow-hidden group">
                        {{-- Decoration --}}
                        <div class="absolute -right-6 -top-6 w-16 h-16 {{ $transaction->type === 'in' ? 'bg-emerald-500/5' : 'bg-rose-500/5' }} rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

                        <div class="flex items-start gap-4 relative z-10">
                            {{-- Type Icon --}}
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $transaction->type === 'in' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} shadow-sm">
                                <i class="fas {{ $transaction->type === 'in' ? 'fa-arrow-down-long' : 'fa-arrow-up-long' }} text-sm"></i>
                            </div>

                            {{-- Transaction Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate leading-tight group-hover:text-indigo-600 transition-colors">{{ $transaction->product->name }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $transaction->type === 'in' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} uppercase tracking-widest">
                                                {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                            </span>
                                            @if($transaction->nosur)
                                                <span class="text-[9px] font-black text-indigo-400 bg-indigo-50 px-1.5 py-0.5 rounded-md uppercase tracking-widest">#{{ $transaction->nosur }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-black {{ $transaction->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}
                                        </span>
                                        <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">{{ $transaction->product->unit ?? 'Unit' }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($transaction->notes)
                                            <div class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 flex items-center gap-2">
                                                <i class="far fa-sticky-note text-[8px] opacity-40"></i>
                                                <span class="text-[8px] font-black tracking-widest truncate max-w-[120px]">{{ $transaction->notes }}</span>
                                            </div>
                                        @endif
                                        <div class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 flex items-center gap-2">
                                            <i class="fas fa-box text-[8px] opacity-40"></i>
                                            <span class="text-[8px] font-black tracking-widest uppercase">{{ $transaction->product->calculated_stock ?? $transaction->product->stock }} Stok</span>
                                        </div>
                                    </div>

                                    {{-- Quick Actions --}}
                                    <div class="flex items-center gap-1.5">
                                        <button @click="
                                            showEditModal = true;
                                            editData = {
                                                id: '{{ $transaction->id }}',
                                                product_id: '{{ $transaction->product_id }}',
                                                date: '{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}',
                                                type: '{{ $transaction->type }}',
                                                quantity: '{{ $transaction->quantity }}',
                                                nosur: '{{ addslashes($transaction->nosur) }}',
                                                notes: '{{ addslashes($transaction->notes) }}'
                                            };
                                            editUrl = '{{ route('stock.update', $transaction->id) }}';
                                        " class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                            <i class="far fa-edit text-[10px]"></i>
                                        </button>
                                        <form action="{{ route('stock.destroy', $transaction->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" @click.prevent="if(confirm('Hapus transaksi ini?')) $el.form.submit()" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="bg-white rounded-[2.5rem] p-16 border border-slate-50 shadow-sm text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-slate-50/50 -skew-y-12 translate-y-20"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i class="fas fa-exchange-alt text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Tidak Ada Transaksi</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Mulai dengan menambah mutasi baru</p>
                </div>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    {{-- Modal Tambah (App-like Bottom Sheet) --}}
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
        <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>
            
            <div x-show="showCreateModal" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="translate-y-0 sm:scale-100"
                class="relative w-full sm:max-w-xl bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
                
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Transaksi Baru</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Input Mutasi Stok Barang</p>
                    </div>
                    <button @click="showCreateModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('stock.store') }}" method="POST" class="space-y-6" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Pilih Barang</label>
                            <select name="product_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                                <option value="">Pilih Barang...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Jenis</label>
                                <div class="flex p-1.5 bg-slate-50 rounded-2xl">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="in" class="peer hidden" checked>
                                        <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">Masuk</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="out" class="peer hidden">
                                        <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-rose-600 peer-checked:shadow-sm transition-all">Keluar</div>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Jumlah</label>
                                <input type="number" name="quantity" min="1" placeholder="0" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">No. Surat</label>
                                <input type="text" name="nosur" placeholder="..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Keterangan</label>
                            <textarea name="notes" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Opsional..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform">
                        Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit (App-like Bottom Sheet) --}}
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
        <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <div x-show="showEditModal" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="translate-y-0 sm:scale-100"
                class="relative w-full sm:max-w-xl bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
                
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Edit Transaksi</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Perbarui Data Mutasi</p>
                    </div>
                    <button @click="showEditModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form :action="editUrl" method="POST" class="space-y-6" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Barang</label>
                            <select name="product_id" x-model="editData.product_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Jenis</label>
                                <div class="flex p-1.5 bg-slate-50 rounded-2xl">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="in" x-model="editData.type" class="peer hidden">
                                        <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">Masuk</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="out" x-model="editData.type" class="peer hidden">
                                        <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-rose-600 peer-checked:shadow-sm transition-all">Keluar</div>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Jumlah</label>
                                <input type="number" name="quantity" x-model="editData.quantity" min="1" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Tanggal</label>
                                <input type="date" name="date" x-model="editData.date" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">No. Surat</label>
                                <input type="text" name="nosur" x-model="editData.nosur" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Keterangan</label>
                            <textarea name="notes" x-model="editData.notes" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
