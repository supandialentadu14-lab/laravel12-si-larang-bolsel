@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto pb-20">

    {{-- Page Header --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Impor Barang</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Update Inventaris via Excel / CSV</p>
        </div>
        <a href="{{ route('products.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 active:scale-90 transition-transform">
            <i class="fas fa-times text-xs"></i>
        </a>
    </div>

    {{-- Info Card --}}
    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center backdrop-blur-md">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <h3 class="text-sm font-black uppercase tracking-widest">Panduan Impor</h3>
            </div>
            <p class="text-xs font-bold text-indigo-100 leading-relaxed opacity-90 mb-6">
                Pastikan file Excel atau CSV Anda mengikuti format kolom berurutan berikut (tanpa header di baris pertama):
            </p>
            
            <div class="overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-inner">
                <table class="w-full text-left border-collapse bg-white">
                    <thead>
                        <tr class="bg-slate-100/50">
                            <th class="w-10 border border-slate-200 text-center py-2 bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-chevron-right text-[8px] text-slate-400"></i>
                            </th>
                            <th class="border border-slate-200 text-center py-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest bg-slate-100/80">A</th>
                            <th class="border border-slate-200 text-center py-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest bg-slate-100/80">B</th>
                            <th class="border border-slate-200 text-center py-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest bg-slate-100/80">C</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-slate-100 bg-slate-50 text-center text-[9px] font-bold text-slate-400 py-3">1</td>
                            <td class="border border-slate-100 px-4 py-3">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black text-slate-700 uppercase block tracking-tight">Pensil 2B</span>
                                    <span class="text-[8px] font-medium text-indigo-500 uppercase block tracking-widest opacity-60">Nama Barang</span>
                                </div>
                            </td>
                            <td class="border border-slate-100 px-4 py-3">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black text-slate-700 uppercase block tracking-tight">Alat Tulis</span>
                                    <span class="text-[8px] font-medium text-indigo-500 uppercase block tracking-widest opacity-60">Kategori</span>
                                </div>
                            </td>
                            <td class="border border-slate-100 px-4 py-3">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black text-slate-700 uppercase block tracking-tight">2500</span>
                                    <span class="text-[8px] font-medium text-indigo-500 uppercase block tracking-widest opacity-60">Harga</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-100 bg-slate-50 text-center text-[9px] font-bold text-slate-400 py-3">2</td>
                            <td class="border border-slate-100 px-4 py-3">
                                <span class="text-[10px] font-medium text-slate-400 italic">... data berikutnya</span>
                            </td>
                            <td class="border border-slate-100 px-4 py-3 text-center">
                                <span class="text-slate-200">---</span>
                            </td>
                            <td class="border border-slate-100 px-4 py-3 text-center">
                                <span class="text-slate-200">---</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-start gap-3 text-[10px] font-bold text-indigo-200 bg-indigo-700/50 p-3 rounded-xl border border-white/5">
                <i class="fas fa-lightbulb text-yellow-300 mt-0.5"></i>
                <span>Tips: Kategori baru akan otomatis dibuat jika belum ada di sistem.</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-50 shadow-sm" x-data="{ fileName: '', isSelected: false }">
        <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data" class="no-soft space-y-8">
            @csrf
            
            <div class="space-y-4">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2 block">Unggah Berkas</label>
                
                <div class="relative group">
                    <input type="file" name="file" id="fileInput" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required
                        @change="if($event.target.files.length > 0) { fileName = $event.target.files[0].name; isSelected = true; } else { fileName = ''; isSelected = false; }"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="border-2 border-dashed rounded-[2rem] p-12 text-center transition-all duration-300" 
                        :class="isSelected ? 'border-indigo-400 bg-indigo-50/30' : 'border-slate-100 bg-slate-50/50 group-hover:border-indigo-400 group-hover:bg-indigo-50/30'">
                        
                        <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mx-auto mb-6 transition-transform duration-500" 
                            :class="isSelected ? 'scale-110 ring-4 ring-indigo-100' : 'group-hover:scale-110'">
                            <i class="fas fa-file-excel text-3xl transition-colors" 
                                :class="isSelected ? 'text-indigo-500' : 'text-slate-200'"></i>
                        </div>
                        
                        <div class="space-y-2">
                            <p class="text-[11px] font-black uppercase tracking-widest transition-colors" 
                                :class="isSelected ? 'text-indigo-600' : 'text-slate-600'" 
                                x-text="isSelected ? fileName : 'Pilih Berkas Excel / CSV'"></p>
                            <p class="text-[9px] font-bold uppercase tracking-[0.15em]" 
                                :class="isSelected ? 'text-indigo-400' : 'text-slate-400'"
                                x-text="isSelected ? 'Berkas Terpilih' : 'Seret file ke sini atau klik untuk browse'"></p>
                        </div>
                        <p class="text-[8px] font-bold text-slate-300 mt-4 uppercase tracking-[0.2em]">Maksimal 2MB</p>
                    </div>
                </div>

                @error('file')
                    <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-rose-50 text-rose-500 border border-rose-100">
                        <i class="fas fa-exclamation-circle text-xs"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4">
                <a href="{{ route('products.index') }}" class="w-full py-5 bg-slate-50 text-slate-400 rounded-3xl text-[10px] font-black uppercase tracking-widest text-center transition-all hover:bg-slate-100 hover:text-slate-600">
                    Batal
                </a>
                <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all hover:bg-indigo-700 active:scale-[0.98]">
                    Mulai Impor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


