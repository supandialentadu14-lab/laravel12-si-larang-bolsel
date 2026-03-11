@extends('layouts.admin')

@section('title', 'Buat Berita Acara Penerimaan')
@section('header', 'Berita Acara Penerimaan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-download"></i> Form Berita Acara Penerimaan
                </h6>
            </div>

            <form method="POST" action="{{ route('reports.penerimaan.save') }}" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="id" value="{{ session('penerimaan_current_id') }}">
                
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Dokumen Penerimaan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor BAP Penerimaan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor" value="{{ old('nomor', $data['nomor'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" placeholder="Contoh: 001/BAP-PNM/2024" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Dokumen <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $data['tanggal'] ?? now()->toDateString()) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tempat Pelaksanaan</label>
                            <input type="text" name="tempat" value="{{ old('tempat', $data['tempat'] ?? ($opd->nama_opd ?? '')) }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Boroko">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-5 bg-indigo-50 border border-indigo-100 rounded-xl relative overflow-hidden">
                            <i class="fas fa-file-signature absolute -right-4 -bottom-4 text-indigo-200/50 text-6xl"></i>
                            <label class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-link"></i> Referensi BAP Pemeriksaan <span class="text-rose-500">*</span>
                            </label>
                            <select name="pemeriksaan_nomor" class="w-full px-3 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white relative z-10" required>
                                <option value="">-- Pilih Nomor BAP Pemeriksaan --</option>
                                @foreach($docs as $doc)
                                    <option value="{{ $doc['nomor'] }}" {{ (old('pemeriksaan_nomor', ($data['pemeriksaan_nomor'] ?? '') ?: (request('pemeriksaan_nomor') ?? ''))) == $doc['nomor'] ? 'selected' : '' }}>
                                        {{ $doc['nomor'] }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-indigo-600 mt-2 italic font-medium">Data barang akan diambil otomatis dari dokumen pemeriksaan yang dipilih.</p>
                        </div>
                        <div class="p-5 bg-slate-100 border border-slate-200 rounded-xl">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-comment-dots"></i> Catatan / Keterangan Tambahan
                            </label>
                            <textarea name="catatan" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm leading-relaxed" placeholder="Masukkan catatan jika diperlukan...">{{ old('catatan', $data['catatan'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('reports.penerimaan.list'),
                    'saveRoute' => route('reports.penerimaan.save'),
                ])
            </form>
        </div>
    </div>
@endsection
