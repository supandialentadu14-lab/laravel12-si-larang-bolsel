@extends('layouts.mobile')

@section('content')
@php
    $editTx = $editTransaction ?? null;
    $editInit = [
        'id' => $editTx->id ?? null,
        'product_id' => old('product_id', $editTx->product_id ?? ''),
        'date' => old('date', isset($editTx->date) ? $editTx->date->format('Y-m-d') : date('Y-m-d')),
        'quantity' => old('quantity', $editTx->quantity ?? ''),
        'nosur' => old('nosur', $editTx->nosur ?? ''),
        'notes' => old('notes', $editTx->notes ?? ''),
        'type' => old('type', $editTx->type ?? 'in'),
    ];
    $editUrl = $editTx ? route('stock.update', $editTx->id) : '';
@endphp
<div class="space-y-6 animate-slide-up" x-data="{
    showCreateModal: {{ request('add') ? 'true' : 'false' }},
    showEditModal: {{ request('edit') ? 'true' : 'false' }},
    editData: {{ \Illuminate\Support\Js::from($editInit) }},
    editType: {{ \Illuminate\Support\Js::from($editInit['type']) }},
    editUrl: {{ \Illuminate\Support\Js::from($editUrl) }},
    openEdit(tx, url) {
        this.editData = { ...tx };
        this.editType = tx.type || 'in';
        this.editUrl = url;
        this.showEditModal = true;
    },
    closeEdit() {
        this.showEditModal = false;
        this.editUrl = '';
    }
}">
    @if (session('success'))
        <div class="p-4 rounded-3xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-3xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase text-glow">Daftar Stok</h2>
            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                {{ $transactions->total() }}
            </span>
        </div>
        <button type="button" @click="showCreateModal = true" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-200 active:scale-90 transition-all">
            <i class="fas fa-plus text-sm"></i>
        </button>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('stock.index') }}" method="GET" class="relative group">
        <input type="text" name="search" value="{{ request('search') }}" 
            placeholder="Cari barang atau nomor surat..." 
            class="w-full pl-12 pr-4 py-4 rounded-3xl bg-white border-none shadow-sm focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-medium placeholder:text-gray-400">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
            <i class="fas fa-search"></i>
        </div>
    </form>

    <!-- Summary Cards (Horizontal Scroll) -->
    <div class="flex gap-4 overflow-x-auto no-scrollbar -mx-5 px-5 pb-2">
        <div class="flex-shrink-0 w-40 p-4 rounded-3xl glass-card border border-gray-100 shadow-sm">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Saldo</p>
            <p class="text-lg font-black text-gray-900">{{ number_format($totalSaldoAkhir) }}</p>
            <p class="text-[9px] font-medium text-gray-400 uppercase">Unit Tersedia</p>
        </div>
        <div class="flex-shrink-0 w-56 p-4 rounded-3xl glass-card border border-gray-100 shadow-sm">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nilai Aset</p>
            <p class="text-lg font-black text-indigo-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
            <p class="text-[9px] font-medium text-gray-400 uppercase">Estimasi Valuasi</p>
        </div>
    </div>

    @if(auth()->user()->hasPermission('laporan_persediaan'))
        <div class="p-5 rounded-[2.5rem] bg-white border border-gray-50 shadow-sm">
            <div class="flex items-center justify-between mb-4 px-1">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Akses Laporan</h3>
                <i class="fas fa-file-alt text-gray-200"></i>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('reports.index') }}" class="p-4 rounded-3xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 active:scale-[0.98] transition flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/15 flex items-center justify-center">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-black uppercase tracking-widest leading-tight">Laporan</div>
                        <div class="text-[9px] font-bold opacity-80 uppercase tracking-widest mt-0.5 truncate">Persediaan</div>
                    </div>
                </a>
                <a href="{{ route('reports.kartu.tahunan') }}" class="p-4 rounded-3xl bg-white border border-indigo-100 text-indigo-700 shadow-sm active:scale-[0.98] transition flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-table-list"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-black uppercase tracking-widest leading-tight">Kartu</div>
                        <div class="text-[9px] font-bold text-indigo-400 uppercase tracking-widest mt-0.5 truncate">Persediaan</div>
                    </div>
                </a>
            </div>
        </div>
    @endif

    <!-- Transactions List -->
    <div class="space-y-6 pb-10">
        <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-widest px-1">Riwayat Transaksi</h3>
        
        @forelse($groupedTransactions as $date => $txs)
            <div class="space-y-3">
                <div class="flex items-center gap-2 px-1">
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                    <div class="h-[1px] flex-1 bg-gradient-to-r from-indigo-100 to-transparent"></div>
                </div>

                @foreach($txs as $tx)
                    <div class="p-4 rounded-3xl bg-white border border-gray-50 shadow-sm flex items-center justify-between active:scale-[0.98] transition group relative overflow-hidden">
                        {{-- Background Decoration --}}
                        <div class="absolute -right-4 -top-4 w-12 h-12 {{ $tx->type == 'in' ? 'bg-green-500/5' : 'bg-red-500/5' }} rounded-full blur-xl"></div>
                        
                        <div class="flex items-start gap-4 relative z-10 w-full">
                            <div class="w-10 h-10 rounded-2xl {{ $tx->type == 'in' ? 'bg-green-50 text-green-600 shadow-green-100' : 'bg-red-50 text-red-600 shadow-red-100' }} flex items-center justify-center text-sm shadow-sm">
                                <i class="fas {{ $tx->type == 'in' ? 'fa-arrow-down-long' : 'fa-arrow-up-long' }}"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-indigo-600 transition">{{ $tx->product->name }}</p>
                                <div class="flex items-center justify-between mt-0.5">
                                    <div class="flex items-center gap-2">
                                        @if($tx->nosur)
                                            <span class="text-[9px] text-indigo-400 font-black uppercase tracking-widest bg-indigo-50/50 px-1.5 py-0.5 rounded-md">#{{ $tx->nosur }}</span>
                                        @else
                                            <span class="text-[9px] text-gray-300 font-black uppercase tracking-widest">No Ref</span>
                                        @endif
                                        
                                        @if($tx->notes)
                                            <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                                            <span class="text-[9px] text-gray-400 font-medium truncate max-w-[100px]">{{ $tx->notes }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest opacity-60">{{ $tx->product->unit ?? 'Unit' }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <p class="text-sm font-black {{ $tx->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $tx->type == 'in' ? '+' : '-' }}{{ number_format($tx->quantity) }}
                                </p>
                                <div class="flex items-center gap-1.5">
                                            <button type="button"
                                                @click="openEdit({
                                                    id: {{ $tx->id }},
                                                    product_id: {{ $tx->product_id }},
                                                    date: {{ \Illuminate\Support\Js::from($tx->date->format('Y-m-d')) }},
                                                    type: {{ \Illuminate\Support\Js::from($tx->type) }},
                                                    quantity: {{ $tx->quantity }},
                                                    nosur: {{ \Illuminate\Support\Js::from($tx->nosur) }},
                                                    notes: {{ \Illuminate\Support\Js::from($tx->notes) }}
                                                }, {{ \Illuminate\Support\Js::from(route('stock.update', $tx->id)) }})"
                                                class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors active:scale-90">
                                        <i class="fas fa-pen text-[10px]"></i>
                                            </button>
                                    <form action="{{ route('stock.destroy', $tx->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @click.prevent="if(confirm('Hapus transaksi ini? Stok akan dikembalikan otomatis.')) $el.form.submit()" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors active:scale-90">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="p-16 text-center bg-white rounded-[3rem] border border-gray-50 shadow-sm relative overflow-hidden">
                <div class="absolute inset-0 bg-indigo-50/10 -skew-y-12 translate-y-20"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-2xl text-slate-200"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tidak ada mutasi ditemukan</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-4">
        {{ $transactions->links() }}
    </div>

    <div x-show="showCreateModal" class="fixed inset-0 z-[10000] overflow-hidden" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showCreateModal = false"></div>

        <div class="absolute inset-x-0 bottom-0 max-h-[85vh] w-full bg-white rounded-t-[2.5rem] shadow-2xl flex flex-col"
            x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-400 transform"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full">

            <div class="w-full flex justify-center py-4" @click="showCreateModal = false">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
            </div>

            <div class="px-6 pb-5 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <div class="text-sm font-black text-gray-900 uppercase tracking-tight">Tambah Mutasi</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Transaksi Masuk & Keluar</div>
                    </div>
                </div>
                <button type="button" @click="showCreateModal = false" class="w-10 h-10 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center active:scale-90 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                @if ($errors->any() && request('add'))
                    <div class="mb-5 p-4 rounded-3xl bg-rose-50 border border-rose-100 text-rose-700">
                        <div class="text-[10px] font-black uppercase tracking-widest">Gagal menyimpan</div>
                        <div class="mt-2 text-xs font-bold space-y-1">
                            @foreach ($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form action="{{ route('stock.store') }}" method="POST" class="space-y-5" x-data="{ type: '{{ request('type', 'in') }}' }">
                    @csrf
                    <input type="hidden" name="type" :value="type">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Pilih Barang</label>
                        <select name="product_id" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
                            <option value="">Pilih Barang...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->calculated_stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tipe Mutasi</label>
                        <div class="grid grid-cols-2 p-1.5 bg-gray-50 rounded-2xl">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" class="peer hidden" name="type_radio" value="in" @change="type = 'in'" :checked="type === 'in'">
                                <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">Masuk</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" class="peer hidden" name="type_radio" value="out" @change="type = 'out'" :checked="type === 'out'">
                                <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:bg-white peer-checked:text-rose-600 peer-checked:shadow-sm transition-all">Keluar</div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Jumlah</label>
                            <input type="number" name="quantity" min="1" placeholder="0" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">No. Surat</label>
                        <input type="text" name="nosur" placeholder="..." class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Keterangan</label>
                        <textarea name="notes" rows="2" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Opsional..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform">
                        Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showEditModal" class="fixed inset-0 z-[10001] overflow-hidden" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeEdit()"></div>

        <div class="absolute inset-x-0 bottom-0 max-h-[85vh] w-full bg-white rounded-t-[2.5rem] shadow-2xl flex flex-col"
            x-transition:enter="transition ease-out duration-500 transform"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-400 transform"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full">

            <div class="w-full flex justify-center py-4" @click="closeEdit()">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
            </div>

            <div class="px-6 pb-5 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <div class="text-sm font-black text-gray-900 uppercase tracking-tight">Edit Mutasi</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Perbarui transaksi</div>
                    </div>
                </div>
                <button type="button" @click="closeEdit()" class="w-10 h-10 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center active:scale-90 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                @if ($errors->any() && request('edit'))
                    <div class="mb-5 p-4 rounded-3xl bg-rose-50 border border-rose-100 text-rose-700">
                        <div class="text-[10px] font-black uppercase tracking-widest">Gagal memperbarui</div>
                        <div class="mt-2 text-xs font-bold space-y-1">
                            @foreach ($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form :action="editUrl" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" :value="editType">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Pilih Barang</label>
                        <select name="product_id" x-model="editData.product_id" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
                            <option value="">Pilih Barang...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->calculated_stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tipe Mutasi</label>
                        <div class="grid grid-cols-2 p-1.5 bg-gray-50 rounded-2xl">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" class="peer hidden" name="type_radio" value="in" @change="editType = 'in'" :checked="editType === 'in'">
                                <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all">Masuk</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" class="peer hidden" name="type_radio" value="out" @change="editType = 'out'" :checked="editType === 'out'">
                                <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:bg-white peer-checked:text-rose-600 peer-checked:shadow-sm transition-all">Keluar</div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Jumlah</label>
                            <input type="number" name="quantity" min="1" placeholder="0" x-model="editData.quantity" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Tanggal</label>
                            <input type="date" name="date" x-model="editData.date" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">No. Surat</label>
                        <input type="text" name="nosur" placeholder="..." x-model="editData.nosur" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-4">Keterangan</label>
                        <textarea name="notes" rows="2" x-model="editData.notes" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Opsional..."></textarea>
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
