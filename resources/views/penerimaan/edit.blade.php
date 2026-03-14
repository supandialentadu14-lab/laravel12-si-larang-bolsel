@extends('layouts.mobile')

@section('content')
  <div class="space-y-6 pb-24">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Serah Terima</h1>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Perbarui BASTB</p>
      </div>
      <a href="{{ route('reports.penerimaan.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
        <i class="fas fa-times text-xs"></i>
      </a>
    </div>

    <form action="{{ route('reports.penerimaan.save') }}" method="POST" class="space-y-6">
      @csrf
      <input type="hidden" name="id" value="{{ $id }}">
      
      {{-- Hubungkan Dokumen --}}
      <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-edit text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Hubungkan Dokumen</h3>
        </div>

        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">BAP Pemeriksaan Referensi</label>
            <select name="pemeriksaan_nomor" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
              <option value="">-- Pilih BAP Pemeriksaan --</option>
              @foreach ($docs as $n)
                <option value="{{ $n['nomor'] }}" {{ (old('pemeriksaan_nomor', ($data['pemeriksaan_nomor'] ?? '') ?: (request('pemeriksaan_nomor') ?? ''))) == $n['nomor'] ? 'selected' : '' }}>
                  {{ $n['nomor'] }} • {{ \Carbon\Carbon::parse($n['tanggal'] ?? now())->translatedFormat('d F Y') }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- Detail Penyerahan --}}
      <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i class="fas fa-handshake text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Detail Penyerahan</h3>
        </div>

        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor BASTB (Angka)</label>
            <input type="text" name="nomor" value="{{ old('nomor', preg_replace('/\D+/', '', $data['nomor'] ?? '')) }}" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/\D/g,'')" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="001" required>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
              <input type="date" name="tanggal" value="{{ old('tanggal', $data['tanggal'] ?? now()->toDateString()) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tempat</label>
              <input type="text" name="tempat" value="{{ old('tempat', $data['tempat'] ?? 'Bolaang Uki') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Contoh: Boroko" required>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Catatan (Opsional)</label>
            <textarea name="catatan" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed" placeholder="Masukkan catatan jika diperlukan...">{{ old('catatan', $data['catatan'] ?? '') }}</textarea>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex gap-3 px-2">
        <a href="{{ route('reports.penerimaan.list') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
        <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Perbarui BASTB</button>
      </div>
    </form>
  </div>
@endsection
