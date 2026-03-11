@extends('layouts.admin')

@section('header', 'Transaksi Masuk/Keluar')
@section('content')

<div x-data="{
    selected: [],
    allSelected: false,
    showCreateModal: false,
    showEditModal: false,
    editData: {},
    editUrl: '',
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
}" class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" @click="showCreateModal = true" class="inline-flex justify-center w-full md:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-exchange-alt"></i> <span class="whitespace-nowrap">Tambah Transaksi</span>
            </button>
        </div>

        <div class="w-full md:max-w-md">
            <form action="{{ route('stock.index') }}" method="GET" x-ref="searchForm">
                <div x-data="{ search: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full text-slate-700">
                        <input type="text" name="search" x-model="search" 
                            @input.debounce.750ms="$refs.searchForm.requestSubmit()"
                            placeholder="Cari nama barang atau no. surat..."
                            class="w-full py-2.5 px-3 text-sm outline-none bg-transparent font-medium placeholder:text-slate-400">
                    </div>
                    <button type="button" x-show="search" x-cloak
                        @click="search = ''; $nextTick(() => $refs.searchForm.requestSubmit())"
                        class="px-2 text-slate-300 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    <button type="submit" class="bg-indigo-600 h-full px-6 text-white text-sm font-bold hover:bg-indigo-700 transition-colors flex items-center whitespace-nowrap">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.5);" @click="showCreateModal = false"></div>
            
            <div x-show="showCreateModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all w-full max-w-2xl sm:my-8 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-exchange-alt"></i> Uraian Transaksi
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('stock.store') }}" method="POST" class="p-0" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
                    @csrf
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-exchange-alt text-indigo-500"></i> Informasi Transaksi
                            </h4>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Barang <span class="text-rose-500">*</span></label>
                                    <select name="product_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (Stok Saat Ini: {{ $product->calculated_stock ?? 0 }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Transaksi <span class="text-rose-500">*</span></label>
                                        <div class="flex space-x-2">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="in" class="peer hidden" required>
                                                <div class="text-center py-2 rounded-lg border border-slate-200 bg-white peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition font-bold text-xs uppercase tracking-tight">
                                                    <i class="fas fa-arrow-down mr-1"></i> Masuk
                                                </div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="out" class="peer hidden" required>
                                                <div class="text-center py-2 rounded-lg border border-slate-200 bg-white peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-600 transition font-bold text-xs uppercase tracking-tight">
                                                    <i class="fas fa-arrow-up mr-1"></i> Keluar
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jumlah Barang <span class="text-rose-500">*</span></label>
                                        <input type="number" name="quantity" min="1" placeholder="0" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Surat (Optional)</label>
                                        <input type="text" name="nosur" placeholder="Ketik angka untuk format otomatis..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" @blur="formatNosur($el, $el.form.querySelector('[name=date]').value)" @keydown.enter.prevent="formatNosur($el, $el.form.querySelector('[name=date]').value)">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan / Keterangan</label>
                                    <textarea name="notes" rows="2" placeholder="Catatan tambahan jika diperlukan..." class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.5);" @click="showEditModal = false"></div>
            
            <div x-show="showEditModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all w-full max-w-2xl sm:my-8 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Uraian Transaksi
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form :action="editUrl" method="POST" class="p-0" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-edit text-amber-500"></i> Perbarui Transaksi
                            </h4>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Barang <span class="text-rose-500">*</span></label>
                                    <select name="product_id" x-model="editData.product_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Transaksi <span class="text-rose-500">*</span></label>
                                        <div class="flex space-x-2">
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="in" x-model="editData.type" class="peer hidden" required>
                                                <div class="text-center py-2 rounded-lg border border-slate-200 bg-white peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition font-bold text-xs uppercase tracking-tight">
                                                    <i class="fas fa-arrow-down mr-1"></i> Masuk
                                                </div>
                                            </label>
                                            <label class="flex-1 cursor-pointer">
                                                <input type="radio" name="type" value="out" x-model="editData.type" class="peer hidden" required>
                                                <div class="text-center py-2 rounded-lg border border-slate-200 bg-white peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-600 transition font-bold text-xs uppercase tracking-tight">
                                                    <i class="fas fa-arrow-up mr-1"></i> Keluar
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jumlah Barang <span class="text-rose-500">*</span></label>
                                        <input type="number" name="quantity" x-model="editData.quantity" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                                        <input type="date" name="date" x-model="editData.date" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Surat</label>
                                        <input type="text" name="nosur" x-model="editData.nosur" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" @blur="formatNosur($el, $el.form.querySelector('[name=date]').value)">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan / Keterangan</label>
                                    <textarea name="notes" x-model="editData.notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Perbarui Transaksi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
        <table class="w-full text-xs text-left text-slate-700">
            <thead class="bg-indigo-50/50 uppercase font-black text-indigo-600 tracking-wider">
                <tr>
                    <th class="px-5 py-4 border-b border-indigo-100 italic w-10">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all h-4 w-4">
                    </th>
                    <th class="px-5 py-4 border-b border-indigo-100 italic">No.</th>
                    <th class="px-5 py-4 border-b border-indigo-100">Tanggal</th>
                    <th class="px-5 py-4 border-b border-indigo-100">Detail Barang</th>
                    <th class="px-5 py-4 border-b border-indigo-100">Referensi Surat</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-center">Tipe</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Qty</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Saldo Saat Itu</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Nilai Saldo</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-center">Admin</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @php $runningStock = []; $iteration = 1; @endphp
                @forelse($transactions->sortBy('date') as $transaction)
                    @php
                        $productId = $transaction->product->id;
                        if (!isset($runningStock[$productId])) { $runningStock[$productId] = 0; }
                        if ($transaction->type === 'in') { $runningStock[$productId] += $transaction->quantity; } 
                        else { $runningStock[$productId] -= $transaction->quantity; }
                        $saldoAkhir = $runningStock[$productId];
                        $nilaiSaldo = $saldoAkhir * $transaction->product->price;
                    @endphp

                    <tr class="transition-all duration-200" :class="{ 'bg-indigo-50/50': selected.includes('{{ $transaction->id }}') }">
                        <td class="px-5 py-4">
                            <input type="checkbox" value="{{ $transaction->id }}" x-model="selected" @click="updateSelectAll()" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                        </td>
                        <td class="px-5 py-4 text-slate-400 font-mono italic">{{ $iteration++ }}</td>
                        <td class="px-5 py-4 font-bold text-slate-600">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-800 uppercase tracking-tight">{{ $transaction->product->name }}</span>
                                <span class="text-[9px] font-mono text-slate-400">SKU: {{ $transaction->product->sku ?: '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[10px] font-mono bg-slate-100 px-2 py-1 rounded text-slate-500 block truncate max-w-[120px]" title="{{ $transaction->nosur }}">
                                {{ $transaction->nosur ?: '-' }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($transaction->type === 'in')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-arrow-down scale-75"></i> MASUK
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-100 text-rose-700 border border-rose-200">
                                    <i class="fas fa-arrow-up scale-75"></i> KELUAR
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-black {{ $transaction->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $transaction->type === 'in' ? '+' : '-' }} {{ $transaction->quantity }}
                        </td>
                        <td class="px-5 py-4 text-right font-bold text-slate-700">
                            {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="font-mono text-[10px] font-bold text-indigo-600">
                                Rp{{ number_format($nilaiSaldo, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <span class="text-[10px] font-bold text-slate-500 opacity-60 uppercase">{{ Str::words($transaction->user->name ?? 'System', 1, '') }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
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
                                " class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Edit">
                                    <i class="far fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('stock.destroy', $transaction->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.prevent="if(confirm('Hapus transaksi ini?')) $el.form.submit()" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-5 py-12 text-center text-slate-400 italic">
                            <i class="fas fa-exchange-alt text-4xl mb-3 block opacity-20"></i>
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
        <form x-show="selected.length > 0" method="POST" action="{{ route('stock.bulk_delete') }}" class="inline-block" @submit.prevent="if(confirm('Hapus transaksi terpilih?')) $el.submit()">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-800 rounded-lg text-slate-800 font-bold text-[10px] hover:bg-slate-50 transition-all shadow-sm group">
                <i class="fas fa-trash text-slate-800 group-hover:text-rose-600 transition-colors"></i>
                <span>HAPUS <span x-text="selected.length"></span> ITEM TERPILIH</span>
            </button>
        </form>
        <div class="flex-1 w-full md:w-auto">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
