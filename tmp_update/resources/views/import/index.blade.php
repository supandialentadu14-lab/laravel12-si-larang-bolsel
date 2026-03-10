@extends('layouts.admin')

@section('header', 'Impor Data Barang')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Panduan Impor Excel / CSV</h3>
            <p class="text-sm text-gray-500 mb-4">Pastikan file Excel atau CSV Anda mengikuti format kolom berurutan (tanpa header baris pertama):</p>
            <div class="bg-gray-50 p-4 rounded-lg font-mono text-[10px] text-gray-600 border border-gray-100">
                Kolom A: Nama Barang<br>
                Kolom B: Kategori Belanja<br>
                Kolom C: Harga Satuan
            </div>
            <p class="text-[10px] text-orange-600 mt-2 italic font-semibold">
                * Kategori baru akan otomatis dibuat jika belum ada di sistem.
            </p>
        </div>

        <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data" class="no-soft">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File (Excel / CSV)</label>
                <div class="relative group">
                    <input type="file" name="file" id="fileInput" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center group-hover:border-indigo-400 transition-colors" id="dropzoneArea">
                        <i class="fas fa-file-excel text-4xl text-gray-300 mb-3 group-hover:text-indigo-400" id="fileIcon"></i>
                        <p class="text-sm text-gray-500" id="fileNameDisplay">Klik atau seret file Excel (.xls, .xlsx) / CSV ke sini</p>
                        <p class="text-xs text-gray-400 mt-1" id="fileSizeDisplay">Maksimal 2MB</p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('products.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
                    Mulai Impor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const display = document.getElementById('fileNameDisplay');
        const icon = document.getElementById('fileIcon');
        const dropzone = document.getElementById('dropzoneArea');
        
        if (file) {
            display.textContent = file.name;
            display.classList.add('text-indigo-600', 'font-semibold');
            display.classList.remove('text-gray-500');
            
            icon.classList.remove('text-gray-300');
            icon.classList.add('text-indigo-500');
            
            dropzone.classList.remove('border-gray-200');
            dropzone.classList.add('border-indigo-300', 'bg-indigo-50');
        } else {
            display.textContent = 'Klik atau seret file Excel (.xls, .xlsx) / CSV ke sini';
            display.classList.remove('text-indigo-600', 'font-semibold');
            display.classList.add('text-gray-500');
            
            icon.classList.add('text-gray-300');
            icon.classList.remove('text-indigo-500');
            
            dropzone.classList.add('border-gray-200');
            dropzone.classList.remove('border-indigo-300', 'bg-indigo-50');
        }
    });
</script>
@endsection
