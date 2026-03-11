@extends('layouts.admin') {{-- Menggunakan layout utama admin --}}

@section('header', 'Transaksi') {{-- Judul halaman transaksi --}}

@section('content')

@php
    $opdSetting = \App\Models\OpdSetting::where('user_id', auth()->id())->first();
    $singkatanOpd = $opdSetting->singkatan_opd ?? 'DISKOMINFO';
@endphp

    {{-- Container utama dengan lebar maksimal --}}
    <div class=" mx-auto">

        {{-- Card pembungkus form --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">

            {{-- Header card --}}
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-exchange-alt"></i> Form Transaksi Stok Barang
                </h6>
            </div>

            {{-- Form untuk menyimpan transaksi stok --}}
            <form action="{{ route('stock.store') }}" method="POST" class="p-6 space-y-6">

                @csrf {{-- Token keamanan untuk mencegah CSRF --}}

                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-box-open text-indigo-500"></i> Detail Barang & Jenis Transaksi
                    </h4>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fas fa-cube"></i> Pilih Produk / Barang <span class="text-rose-500">*</span>
                        </label>
                        <select name="product_id" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white shadow-sm" required>
                            <option value="">-- Pilih Barang di Gudang --</option>
                            @foreach ($products as $product)
                                @php $currentStock = $product->calculated_stock ?? 0; @endphp
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ( Stok Saat Ini: {{ $currentStock }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Transaksi <span class="text-rose-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="type" value="in" class="peer hidden" {{ old('type') == 'in' ? 'checked' : '' }} required>
                                    <div class="text-center py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition-all duration-300 font-bold text-sm shadow-sm group-hover:border-emerald-200">
                                        <i class="fas fa-arrow-down mr-1"></i> Stok Masuk
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer group">
                                    <input type="radio" name="type" value="out" class="peer hidden" {{ old('type') == 'out' ? 'checked' : '' }}>
                                    <div class="text-center py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-600 transition-all duration-300 font-bold text-sm shadow-sm group-hover:border-rose-200">
                                        <i class="fas fa-arrow-up mr-1"></i> Stok Keluar
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Barang <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" class="w-full pl-4 pr-12 py-3 rounded-xl border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-lg font-black shadow-sm" placeholder="0" required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs uppercase">Unit</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100 mb-6 group">
                    <h4 class="text-indigo-900 font-bold mb-5 flex items-center gap-2 border-b border-indigo-100 pb-2">
                        <i class="fas fa-file-alt text-indigo-500"></i> Referensi & Keterangan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar-day"></i> Tanggal Transaksi <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-hashtag"></i> Nomor Surat Terkait
                            </label>
                            <input type="text" name="nosur" value="{{ old('nosur') }}" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm shadow-sm" placeholder="Contoh: 001/SPM/XII/2024">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fas fa-comment-dots"></i> Catatan Transaksi / Keperluan
                        </label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm leading-relaxed" placeholder="Contoh: Pembelian rutin ATK bulan Desember...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                {{-- KETERANGAN --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">
                        Belanja
                    </label>

                    {{-- Textarea untuk catatan transaksi --}}
                    <textarea name="notes" rows="3" placeholder="Keterangan pemasukan atau pengeluaran untuk apa"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 
                               focus:border-orange-500 focus:ring-2 focus:ring-orange-200 
                               outline-none transition">{{ old('notes') }}</textarea>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('stock.index'),
                    'saveText' => 'Simpan',
                ])
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nosurInput = document.querySelector('[name="nosur"]');
            const dateInput = document.querySelector('[name="date"]');
            
            function toRoman(num) {
                const romans = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
                return romans[num] || "";
            }
            
            function formatNomorSurat() {
                if (!nosurInput || !dateInput) return;
                
                let val = nosurInput.value.trim();
                // Check if value is just numbers (allows leading zeros)
                // Also ignore if it already contains '/' to prevent double formatting
                if (/^\d+$/.test(val) && !val.includes('/')) {
                    const dateVal = new Date(dateInput.value);
                    if (!isNaN(dateVal.getTime())) {
                        const month = dateVal.getMonth() + 1; // 0-11 to 1-12
                        const year = dateVal.getFullYear();
                        const romanMonth = toRoman(month);
                        
                        // Default format requested: 001/BAPB/{singkatan_opd}/III/2026
                        // We use the input number as is (e.g. 001)
                        const formatted = `${val}/BAPB/{{ $singkatanOpd }}/${romanMonth}/${year}`;
                        nosurInput.value = formatted;
                    }
                }
            }
            
            if (nosurInput && dateInput) {
                nosurInput.addEventListener('blur', formatNomorSurat);
                nosurInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Prevent newline in textarea
                        formatNomorSurat();
                    }
                });
                
                // Also format on form submit to ensure it's saved correctly
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        formatNomorSurat();
                    });
                }
            }
        });
    </script>
@endsection
