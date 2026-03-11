@extends('layouts.admin')

@section('header', 'Penyedia')
@section('content')

<div x-data="{
    showCreateModal: false,
    showEditModal: false,
    editData: {},
    editUrl: '',
    selected: [],
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = [
                @foreach ($suppliers as $supplier)
                    '{{ $supplier->id }}',
                @endforeach
            ];
        } else {
            this.selected = [];
        }
    },
    updateSelectAll() {
        this.allSelected = this.selected.length === {{ count($suppliers) }};
    }
}" class="bg-white rounded-lg shadow p-6 mb-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" @click="showCreateModal = true" class="inline-flex justify-center w-full md:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-plus"></i> <span class="whitespace-nowrap">Tambah Penyedia Baru</span>
            </button>
        </div>

        <div class="w-full md:max-w-md">
            <form action="{{ route('suppliers.index') }}" method="GET" class="relative group">
                <div x-data="{ query: '{{ request('search') }}' }" class="relative">
                    <input type="text" name="search" x-model="query" 
                        @input.debounce.750ms="$el.closest('form').requestSubmit()"
                        x-init="$el.focus(); $el.setSelectionRange($el.value.length, $el.value.length)"
                        placeholder="Cari nama penyedia atau NPWP..."
                        class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-200 text-sm font-medium">
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.3); backdrop-filter: blur(2px);" @click="showCreateModal = false"></div>
            
            <div x-show="showCreateModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all sm:max-w-2xl sm:w-full">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Penyedia Baru
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('suppliers.store') }}" method="POST" class="p-0">
                    @csrf
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-store text-indigo-500"></i> Detail Penyedia
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Toko/CV/PT <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: PT. Maju Jaya" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Direktur / Pemilik <span class="text-rose-500">*</span></label>
                                    <input type="text" name="dir" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Nama lengkap pemimpin" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">NPWP <span class="text-rose-500">*</span></label>
                                    <input type="text" name="npwp" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="Nomor NPWP usaha" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea name="address" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Alamat detail penyedia..." required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-indigo-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Data Penyedia
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
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.3); backdrop-filter: blur(2px);" @click="showEditModal = false"></div>
            
            <div x-show="showEditModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all sm:max-w-2xl sm:w-full">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit&nbsp;<span x-text="editData.name"></span>
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form :action="editUrl" method="POST" class="p-0">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-edit text-amber-500"></i> Perbarui Penyedia
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Toko/CV/PT <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Direktur / Pemilik <span class="text-rose-500">*</span></label>
                                    <input type="text" name="dir" x-model="editData.dir" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">NPWP <span class="text-rose-500">*</span></label>
                                    <input type="text" name="npwp" x-model="editData.npwp" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                    <textarea name="address" x-model="editData.address" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-7 py-2.5 bg-amber-500 rounded-lg text-sm font-bold text-white shadow-lg shadow-amber-100 hover:bg-amber-600 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Perbarui Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="border border-slate-200 rounded-2xl bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left text-slate-700 table-fixed">
            <thead class="bg-indigo-50/50 text-[9px] uppercase font-extrabold text-indigo-600 tracking-tighter border-b border-indigo-100">
                <tr>
                    <th class="px-3 py-4 w-[5%] text-center">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-3 w-3 transition-all">
                    </th>
                    <th class="px-3 py-4 w-[20%]">Informasi Penyedia</th>
                    <th class="px-3 py-4 w-[15%] uppercase tracking-tighter">Pimpinan / Pemilik</th>
                    <th class="px-3 py-4 w-[35%]">Alamat Kantor / Usaha</th>
                    <th class="px-3 py-4 w-[15%] uppercase tracking-tighter">NPWP</th>
                    <th class="px-3 py-4 w-[10%] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-indigo-50/30 transition-all duration-200" :class="{ 'bg-indigo-50/50': selected.includes('{{ $supplier->id }}') }">
                        <td class="px-3 py-4 text-center">
                            <input type="checkbox" value="{{ $supplier->id }}" x-model="selected" @click="updateSelectAll()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-3 w-3 transition-all">
                        </td>
                        <td class="px-3 py-4 font-extrabold text-slate-800 whitespace-normal leading-tight break-all text-[10px] uppercase tracking-tighter">{{ $supplier->name }}</td>
                        <td class="px-3 py-4 text-slate-600 italic font-bold text-[9px] uppercase tracking-tighter break-words leading-tight">{{ $supplier->dir ?: '-' }}</td>
                        <td class="px-3 py-4 text-slate-500 text-[10px] whitespace-normal leading-relaxed break-all">{{ $supplier->address ?: '-' }}</td>
                        <td class="px-3 py-4">
                            <span class="font-mono text-[9px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 tracking-tighter font-bold border border-slate-200 block w-fit">
                                {{ $supplier->npwp ?: '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
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
                                " class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Edit">
                                    <i class="far fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.prevent="if(confirm('Hapus penyedia ini?')) $el.form.submit()" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400 italic">
                            <i class="fas fa-store-slash text-4xl mb-3 block opacity-20"></i>
                            Penyedia tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('suppliers.bulk_delete') }}" class="inline-block">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button" @click="if(confirm('Hapus ' + selected.length + ' item terpilih?')) $el.closest('form').submit()" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-800 rounded-lg text-slate-800 font-bold text-[10px] hover:bg-slate-50 transition-all shadow-sm group">
                <i class="fas fa-trash text-slate-800 group-hover:text-rose-600 transition-colors"></i>
                <span>HAPUS <span x-text="selected.length"></span> ITEM TERPILIH</span>
            </button>
        </form>
        <div class="flex-1">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection
