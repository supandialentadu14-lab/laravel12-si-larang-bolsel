@extends('layouts.admin')

@section('header', 'Buat Kwitansi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-receipt"></i> Form Kwitansi Pembayaran
                </h6>
            </div>
            
            <form action="{{ route('reports.kwitansi.save') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6">
                    <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Detail Dokumen Kwitansi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun" value="{{ $data['tahun'] ?? now()->year }}" min="2000" max="2100" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kode Rekening Belanja</label>
                            <input type="text" name="rekening" value="{{ $data['rekening'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="5.1.02.01.01.0024">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Kwitansi (KWT) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor_kwt" value="{{ $data['nomor_kwt'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" placeholder="Contoh: 001/KWT/2024" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Kwitansi <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ $data['tanggal'] ?? now()->toDateString() }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" required>
                        </div>
                        
                        <div class="md:col-span-2 p-5 bg-indigo-50 border border-indigo-100 rounded-xl">
                            <label class="block text-xs font-bold text-indigo-700 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-file-alt"></i> Hubungkan dengan BAP Penerimaan <span class="text-rose-500">*</span>
                            </label>
                            <select name="penerimaan_nomor" class="w-full px-3 py-2 rounded-lg border border-indigo-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white" required>
                                <option value="">-- Cari & Pilih Nomor BAP Penerimaan --</option>
                                @foreach ($docs as $n)
                                    <option value="{{ $n['nomor'] }}" {{ ($data['penerimaan_nomor'] ?? '') === ($n['nomor'] ?? '') ? 'selected' : '' }}>
                                        {{ $n['nomor'] }} • {{ \Carbon\Carbon::parse($n['tanggal'] ?? now())->translatedFormat('d F Y') }} • Rp {{ number_format($n['total'] ?? 0, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex items-start gap-2 mt-3">
                                <i class="fas fa-lightbulb text-amber-500 text-xs mt-0.5"></i>
                                <p class="text-[10px] text-indigo-600 font-medium italic leading-relaxed">
                                    Jumlah nominal uang dan rincian uraian belanja akan ditarik secara otomatis dari data **Penerimaan Barang** yang Anda pilih di atas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @include('partials.form-actions', [
                    'backRoute' => route('reports.kwitansi.list'),
                    'saveRoute' => route('reports.kwitansi.save'),
                ])
            </form>
        </div>
    </div>
@endsection
