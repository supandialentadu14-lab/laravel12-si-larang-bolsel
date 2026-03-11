@extends('layouts.admin')

@section('header', 'Daftar Barang')
@section('content')

<div x-data="{
    selected: [],
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = [
                @foreach ($products as $product)
                    '{{ $product->id }}',
                @endforeach
            ];
        } else {
            this.selected = [];
        }
    },
    updateSelectAll() {
        this.allSelected = this.selected.length === {{ count($products) }};
    },
    showCreateModal: false,
    showEditModal: false,
    showImportModal: false,
    editData: {},
    editUrl: ''
}" class="bg-white rounded-lg shadow p-6 mb-6">

    {{-- Modal Tambah --}}
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.3); backdrop-filter: blur(2px);" @click="showCreateModal = false"></div>
            
            <div x-show="showCreateModal" x-transition.scale.95 class="relative inline-block bg-white rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 transform transition-all sm:max-w-2xl sm:w-full my-8">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-plus"></i> Tambah Barang Baru
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('products.store') }}" method="POST" class="p-0">
                    @csrf
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-box text-indigo-500"></i> Detail Barang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Kertas HVS A4 80gr" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Belanja <span class="text-rose-500">*</span></label>
                                    <select name="category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                                    </select>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Harga Satuan <span class="text-rose-500">*</span></label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-100 text-slate-500 text-xs font-bold">
                                                Rp
                                            </span>
                                            <input type="number" step="0.01" name="price" class="w-full px-3 py-2 rounded-r-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono text-right" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Satuan <span class="text-rose-500">*</span></label>
                                        <select name="unit" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="pcs">Pcs</option><option value="buah">Buah</option><option value="box">Box</option><option value="pak">Pak</option><option value="rim">Rim</option><option value="kg">Kg</option><option value="galon">Galon</option><option value="paket">Paket</option><option value="liter">Liter</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan</label>
                                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Warna Putih, Isi 500 Lembar"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end gap-3 px-2">
                            <button type="submit" class="px- py-2.5 bg-indigo-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Data Barang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 font-sans">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-edit text-amber-500"></i> Perbarui Barang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Barang <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" x-model="editData.name" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Belanja <span class="text-rose-500">*</span></label>
                                    <select name="category_id" x-model="editData.category_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kode / SKU</label>
                                    <input type="text" name="sku" x-model="editData.sku" class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-100 text-slate-500 outline-none text-sm font-mono cursor-not-allowed" readonly>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Harga Satuan <span class="text-rose-500">*</span></label>
                                        <div class="flex">
                                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-100 text-slate-500 text-xs font-bold">
                                                Rp
                                            </span>
                                            <input type="number" step="0.01" name="price" x-model="editData.price" class="w-full px-3 py-2 rounded-r-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono text-right" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Satuan <span class="text-rose-500">*</span></label>
                                        <select name="unit" x-model="editData.unit" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                            <option value="pcs">Pcs</option><option value="buah">Buah</option><option value="box">Box</option><option value="pak">Pak</option><option value="rim">Rim</option><option value="kg">Kg</option><option value="galon">Galon</option><option value="paket">Paket</option><option value="liter">Liter</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Keterangan</label>
                                    <textarea name="description" x-model="editData.description" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm"></textarea>
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

    {{-- Modal Import --}}
    <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center sm:px-0 sm:pb-0">
            <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.3); backdrop-filter: blur(2px);" @click="showImportModal = false"></div>
            
            <div x-show="showImportModal" x-transition.scale.95 class="relative inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full sm:my-8 border border-gray-200">
                
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Impor Data Barang
                    </h3>
                    <button type="button" @click="showImportModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-[#f8fafc] p-4 rounded-lg border border-[#94a3b8] mb-6">
                        <h4 class="font-bold text-[#1e40af] mb-3 text-sm">Panduan Format File:</h4>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 bg-white p-3 rounded-md border border-[#3b82f6]">
                                <span class="block text-[10px] font-bold text-[#3b82f6] mb-1">KOLOM A:</span>
                                <span class="text-xs text-[#2563eb] font-mono whitespace-nowrap">Nama Barang</span>
                            </div>
                            
                            <div class="flex-1 bg-white p-3 rounded-md border border-[#3b82f6]">
                                <span class="block text-[10px] font-bold text-[#3b82f6] mb-1">KOLOM B:</span>
                                <span class="text-xs text-[#2563eb] font-mono whitespace-nowrap">Kategori</span>
                            </div>
                            
                            <div class="flex-1 bg-white p-3 rounded-md border border-[#3b82f6]">
                                <span class="block text-[10px] font-bold text-[#3b82f6] mb-1">KOLOM C:</span>
                                <span class="text-xs text-[#2563eb] font-mono whitespace-nowrap">Harga Satuan</span>
                            </div>

                            <div class="flex-1 bg-white p-3 rounded-md border border-[#3b82f6]">
                                <span class="block text-[10px] font-bold text-[#3b82f6] mb-1">KOLOM D:</span>
                                <span class="text-xs text-[#2563eb] font-mono whitespace-nowrap">Satuan</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File (Excel / CSV)</label>
                            
                            <div class="relative group" x-data="{ 
                                fileName: 'Klik atau tarik file Excel/CSV ke sini', 
                                isSelected: false,
                                fileChanged(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.fileName = file.name;
                                        this.isSelected = true;
                                    } else {
                                        this.fileName = 'Klik atau tarik file Excel/CSV ke sini';
                                        this.isSelected = false;
                                    }
                                }
                            }">
                                <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required
                                    @change="fileChanged"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center transition-colors hover:border-indigo-400 bg-white" 
                                    :class="isSelected ? 'border-indigo-300 bg-indigo-50' : ''">
                                    <i class="fas fa-file-excel text-4xl mb-3 transition text-gray-300 group-hover:text-indigo-400" 
                                        :class="isSelected ? 'text-indigo-500' : ''"></i>
                                    <p class="text-sm transition-colors text-gray-500" :class="isSelected ? 'text-indigo-600 font-semibold' : ''" x-text="fileName"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-show="!isSelected">Maksimal 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-[#4f46e5] rounded-lg text-sm font-bold text-white shadow-sm hover:bg-[#4338ca] transition flex items-center gap-2">
                                <i class="fas fa-upload"></i> Mulai Impor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end gap-6 mb-8">
        {{-- Tombol Aksi Kiri --}}
        <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
            <button @click="showCreateModal = true" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all duration-200 flex-1 sm:flex-none">
                <i class="fas fa-plus"></i> <span class="whitespace-nowrap">Tambah Barang</span>
            </button>
            <button @click="showImportModal = true" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 text-white font-bold text-sm hover:bg-green-700 hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-green-200 transition-all duration-200 flex-1 sm:flex-none border-none">
                <i class="fas fa-file-import"></i> Impor
            </button>
            <a href="{{ route('products.export') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 text-white font-bold text-sm hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all duration-200 flex-1 sm:flex-none border-none">
                <i class="fas fa-file-excel"></i> Ekspor
            </a>
        </div>

        {{-- Filter & Search Kanan --}}
        <div class="w-full xl:max-w-4xl">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4 w-full">

                {{-- Filter Kategori --}}
                <div class="w-full sm:w-64 group">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1 group-focus-within:text-indigo-600 transition-colors">Filter Kategori</label>
                    <div class="relative">
                        <select name="category_id" onchange="this.form.requestSubmit()" 
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 block p-2.5 outline-none transition-all duration-200 font-bold appearance-none pr-12">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Input Pencarian --}}
                <div class="flex-1 group w-full">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1 group-focus-within:text-indigo-600 transition-colors">Pencarian Barang</label>
                    <div x-data="{ query: '{{ request('search') }}' }" class="relative">
                        <input type="search" name="search" x-model="query" @keydown.enter.prevent="$el.form.requestSubmit()" placeholder="Cari nama barang atau SKU..."
                            class="w-full pl-4 pr-28 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all duration-200 text-sm font-medium">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                             <span x-show="query" @click="query = ''; $el.closest('form').requestSubmit()" class="p-2 text-slate-300 cursor-pointer hover:text-rose-500 transition-colors">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <button type="submit" class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-100 transition ml-1 hidden sm:block">
                                CARI
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Reset Filter Button --}}
                @if(request('category_id') || request('search') || request('low_stock'))
                    <div class="flex items-end flex-none pb-0.5">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-100 transition-all shadow-sm border border-rose-100" title="Reset Filter">
                            <i class="fas fa-undo-alt text-sm"></i>
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/50 text-[10px] uppercase font-bold text-indigo-600 tracking-widest">
                <tr>
                    <th class="px-5 py-4 border-b border-indigo-100 w-10 text-center">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                    </th>
                    <th class="px-5 py-4 border-b border-indigo-100">Informasi Barang</th>
                    <th class="px-5 py-4 border-b border-indigo-100">Jenis Belanja</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Harga Satuan</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-center">Stok Gudang</th>
                    <th class="px-5 py-4 border-b border-indigo-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr class="hover:bg-indigo-50/30 transition-all duration-200" :class="{ 'bg-indigo-50/50': selected.includes('{{ $product->id }}') }">
                        <td class="px-5 py-4 text-center">
                            <input type="checkbox" value="{{ $product->id }}" x-model="selected" @click="updateSelectAll()" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 leading-tight mb-1">{{ $product->name }}</span>
                                <span class="text-[10px] font-mono text-slate-400">SKU: {{ $product->sku ?: '-' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @if($product->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-tighter">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-slate-300 italic text-xs">Kosong</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-bold text-slate-700">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @php
                                $stock = $product->stock;
                                $min = $product->min_stock ?: 5;
                                $statusClass = $stock <= $min ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200';
                            @endphp
                            <span class="inline-flex items-center justify-center min-w-[3rem] px-2 py-1 rounded-lg border text-xs font-black {{ $statusClass }}">
                                {{ $stock }} <span class="ml-1 text-[8px] font-bold opacity-70">{{ $product->unit }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('products.barcode', $product->id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Barcode">
                                    <i class="fas fa-barcode text-xs"></i>
                                </a>
                                <button @click="
                                    showEditModal = true;
                                    editData = {
                                        id: '{{ $product->id }}',
                                        name: '{{ addslashes($product->name) }}',
                                        category_id: '{{ $product->category_id }}',
                                        sku: '{{ $product->sku }}',
                                        price: '{{ $product->price }}',
                                        unit: '{{ $product->unit }}',
                                        description: '{{ addslashes($product->description) }}'
                                    };
                                    editUrl = '{{ route('products.update', $product->id) }}';
                                " class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Edit">
                                    <i class="far fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.prevent="if(confirm('Hapus barang ini?')) $el.form.submit()" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400 italic">
                            <i class="fas fa-search text-4xl mb-3 block opacity-20"></i>
                            Barang tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('products.bulk_delete') }}" class="inline-block">
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
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
