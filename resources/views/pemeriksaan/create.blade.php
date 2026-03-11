@extends('layouts.admin')

@section('title', 'Buat Berita Acara Pemeriksaan')
@section('header', 'Berita Acara Pemeriksaan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-signature"></i> Form Berita Acara Pemeriksaan
                </h6>
            </div>

            <form action="{{ route('reports.pemeriksaan.save') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Dokumen Pemeriksaan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Referensi Nota -->
                        <div class="p-5 bg-indigo-50 border border-indigo-100 rounded-xl relative overflow-hidden">
                            <i class="fas fa-file-invoice absolute -right-4 -bottom-4 text-indigo-200/40 text-6xl"></i>
                            <label class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-link"></i> Hubungkan dengan Nota Pesanan <span class="text-rose-500">*</span>
                            </label>
                            <select name="nota_nomor" class="w-full px-3 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white relative z-10 shadow-sm" required>
                                <option value="">-- Cari & Pilih Nomor Nota Pesanan --</option>
                                @foreach ($notaDocs as $n)
                                    <option value="{{ $n['nomor'] }}" {{ ($data['nota_nomor'] ?? '') === ($n['nomor'] ?? '') ? 'selected' : '' }}>
                                        {{ $n['nomor'] }} • {{ \Carbon\Carbon::parse($n['tanggal'] ?? now())->translatedFormat('d F Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex items-start gap-2 mt-3 relative z-10">
                                <i class="fas fa-lightbulb text-amber-500 text-xs mt-0.5"></i>
                                <p class="text-[10px] text-indigo-600 font-medium italic leading-relaxed">
                                    Data barang dan penyedia akan ditarik otomatis dari Nota Pesanan yang dipilih.
                                </p>
                            </div>
                        </div>

                        <!-- Detail Pemeriksaan -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor BAP Pemeriksaan <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="nomor" value="{{ old('nomor', preg_replace('/\D+/', '', $data['nomor'] ?? '')) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/\D/g,'')" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" placeholder="Contoh: 001" required>
                                    <i class="fas fa-hashtag absolute left-3 top-2.5 text-slate-400"></i>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tempat Surat <span class="text-rose-500">*</span></label>
                                    <input type="text" name="tempat" value="{{ old('tempat', $data['tempat'] ?? 'Bolaang Uki') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Boroko" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal" value="{{ old('tanggal', $data['tanggal'] ?? now()->toDateString()) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('reports.pemeriksaan.list'),
                    'saveRoute' => route('reports.pemeriksaan.save'),
                ])
            </form>
        </div>
    </div>
@endsection
