@extends('layouts.mobile')

@section('content')
    <div class="space-y-6 pb-24">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Kwitansi</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Buat Kwitansi Pembayaran</p>
            </div>
            <a href="{{ route('reports.kwitansi.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-times text-xs"></i>
            </a>
        </div>

        <form action="{{ route('reports.kwitansi.save') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- Hubungkan Dokumen --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-link text-xs"></i>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Hubungkan Dokumen</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">BAP Penerimaan Referensi</label>
                        <select name="penerimaan_nomor" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                            <option value="">-- Pilih BAP Penerimaan --</option>
                            @foreach ($docs as $n)
                                <option value="{{ $n['nomor'] }}" {{ (old('penerimaan_nomor', $data['penerimaan_nomor'] ?? '') === ($n['nomor'] ?? '')) ? 'selected' : '' }}>
                                    {{ $n['nomor'] }} • Rp {{ number_format($n['total'] ?? 0, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="flex items-start gap-2 mt-3 px-2">
                            <i class="fas fa-lightbulb text-amber-500 text-[10px] mt-0.5"></i>
                            <p class="text-[10px] text-indigo-600 font-bold italic leading-relaxed uppercase tracking-tighter">
                                Jumlah nominal dan uraian belanja akan ditarik otomatis dari BAP Penerimaan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Kwitansi --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-receipt text-xs"></i>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Detail Kwitansi</h3>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tahun Anggaran</label>
                            <input type="number" name="tahun" value="{{ old('tahun', $data['tahun'] ?? now()->year) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $data['tanggal'] ?? now()->toDateString()) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor Kwitansi (Angka)</label>
                        <input type="text" name="nomor_kwt" value="{{ old('nomor_kwt', preg_replace('/\D+/', '', $data['nomor_kwt'] ?? '')) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/\D/g,'')" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="001" required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Kode Rekening</label>
                        <input type="text" name="rekening" value="{{ old('rekening', $data['rekening'] ?? '') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="5.1.02.01...">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 px-2">
                <a href="{{ route('reports.kwitansi.list') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
                <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Simpan Kwitansi</button>
            </div>
        </form>
    </div>
@endsection
