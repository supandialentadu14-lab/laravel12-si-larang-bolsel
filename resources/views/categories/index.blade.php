{{-- Menggunakan layout admin --}}
@extends('layouts.admin')

@section('header', 'Jenis Belanja')
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
                @foreach ($categories as $category)
                    '{{ $category->id }}',
                @endforeach
            ];
        } else {
            this.selected = [];
        }
    },
    updateSelectAll() {
        this.allSelected = this.selected.length === {{ count($categories) }};
    }
}" class="bg-white rounded-lg shadow p-6 mb-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" @click="showCreateModal = true" class="inline-flex justify-center w-full md:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-plus"></i> <span class="whitespace-nowrap">Tambah Jenis Belanja</span>
            </button>
        </div>

        <div class="w-full md:max-w-md">
            <form action="{{ route('categories.index') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            @input.debounce.750ms="$el.closest('form').requestSubmit()"
                            placeholder="Cari jenis belanja..."
                            class="w-full py-2.5 px-3 text-sm outline-none bg-transparent font-medium placeholder:text-slate-400 text-slate-700">
                    </div>
                    <button type="button" x-show="query" x-cloak
                        @click="query = ''; $nextTick(() => $el.closest('form').requestSubmit())"
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
            
            <div x-show="showCreateModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all w-full max-w-md sm:my-8 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Jenis Belanja
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('categories.store') }}" method="POST" class="p-0 no-soft">
                    @csrf
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-tags text-indigo-500"></i> Detail Jenis Belanja
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Jenis Belanja <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required placeholder="Contoh: Alat Tulis Kantor">
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <i class="fas fa-info-circle text-indigo-400"></i> Masukkan nama kategori belanja secara spesifik.
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan (Opsional)</label>
                                    <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Penjelasan singkat mengenai kategori ini..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Data
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
            
            <div x-show="showEditModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all w-full max-w-md sm:my-8 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit&nbsp;<span x-text="editData.name"></span>
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form :action="editUrl" method="POST" class="p-0 no-soft">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-edit text-amber-500"></i> Perbarui Jenis Belanja
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Jenis Belanja <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan (Opsional)</label>
                                    <textarea name="description" x-model="editData.description" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/50 text-[10px] uppercase font-bold text-indigo-600 tracking-widest">
                <tr>
                    <th class="px-5 py-4 border-b border-indigo-100 w-10">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                    </th>
                    <th class="px-5 py-4 border-b border-indigo-100">Nama Jenis Belanja</th>
                    <th class="px-5 py-4 border-b border-indigo-100">Keterangan</th>
                    <th class="px-3 py-4 border-b border-indigo-100 text-center">Data Barang</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-indigo-50/30 transition-all duration-200" :class="{ 'bg-indigo-50/50': selected.includes('{{ $category->id }}') }">
                        <td class="px-5 py-3">
                            <input type="checkbox" value="{{ $category->id }}" x-model="selected" @click="updateSelectAll()" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                        </td>
                        <td class="px-5 py-3">
                            <div class="font-bold text-slate-800">{{ $category->name }}</div>
                        </td>
                        <td class="px-5 py-3 text-slate-500 italic text-xs">{{ Str::limit($category->description, 60) ?: '-' }}</td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                <i class="fas fa-box text-[8px] mr-1.5 opacity-50"></i>{{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <button @click="
                                    showEditModal = true;
                                    editData = {
                                        id: '{{ $category->id }}',
                                        name: '{{ addslashes($category->name) }}',
                                        description: '{{ addslashes($category->description) }}'
                                    };
                                    editUrl = '{{ route('categories.update', $category->id) }}';
                                " class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Edit">
                                    <i class="far fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.prevent="if(confirm('Hapus jenis belanja ini?')) $el.form.submit()" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-400 italic">
                            <i class="fas fa-folder-open text-4xl mb-3 block opacity-20"></i>
                            Belum ada data jenis belanja.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('categories.bulk_delete') }}" class="inline-block">
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
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
