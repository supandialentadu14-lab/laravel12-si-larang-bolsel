@extends($isMobile ? 'layouts.mobile' : 'layouts.admin')

@section('content')
  <div class="space-y-6 pb-24">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-app-main uppercase tracking-tight">Serah Terima</h1>
        <p class="text-[10px] font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Perbarui BASTB</p>
      </div>
      <a href="{{ route('reports.penerimaan.list') }}" class="w-10 h-10 rounded-2xl bg-app-surface border border-app-main shadow-sm flex items-center justify-center text-app-muted">
        <i class="fas fa-times text-xs"></i>
      </a>
    </div>

    <form action="{{ route('reports.penerimaan.save') }}" method="POST" class="space-y-6">
      @csrf
      <input type="hidden" name="id" value="{{ $id }}">
      
      {{-- Hubungkan Dokumen --}}
      <div class="bg-app-surface rounded-[2.5rem] p-6 border border-app-main shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-app-main pb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-edit text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-app-main uppercase tracking-widest">Hubungkan Dokumen</h3>
        </div>
 
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">BAP Pemeriksaan Referensi</label>
            <select name="pemeriksaan_nomor" 
              oninvalid="this.setCustomValidity('BAP Pemeriksaan harus dipilih')" 
              oninput="this.setCustomValidity('')"
              class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
              <option value="">-- Pilih BAP Pemeriksaan --</option>
              @foreach ($docs as $n)
                <option value="{{ $n['nomor'] }}" {{ (old('pemeriksaan_nomor', ($data['pemeriksaan_nomor'] ?? '') ?: (request('pemeriksaan_nomor') ?? ''))) == $n['nomor'] ? 'selected' : '' }}>
                  {{ $n['nomor'] }} • {{ \Carbon\Carbon::parse($n['tanggal'] ?? now())->translatedFormat('d F Y') }}
                </option>
              @endforeach
            </select>
            @error('pemeriksaan_nomor')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      {{-- Detail Penyerahan --}}
      <div class="bg-app-surface rounded-[2.5rem] p-6 border border-app-main shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-app-main pb-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i class="fas fa-handshake text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-app-main uppercase tracking-widest">Detail Penyerahan</h3>
        </div>
 
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Nomor BASTB (Angka)</label>
            <input type="text" name="nomor" value="{{ old('nomor', preg_replace('/\D+/', '', $data['nomor'] ?? '')) }}" inputmode="numeric" pattern="[0-9]*" 
              oninvalid="this.setCustomValidity('Nomor BASTB harus diisi')" 
              oninput="this.value=this.value.replace(/\D/g,''); this.setCustomValidity('');"
              class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-mono font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="001" required>
            @error('nomor')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
          </div>

          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Tanggal</label>
              <input type="date" name="tanggal" value="{{ old('tanggal', $data['tanggal'] ?? now()->toDateString()) }}" 
                oninvalid="this.setCustomValidity('Tanggal harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
              @error('tanggal')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Tempat</label>
              <input type="text" name="tempat" value="{{ old('tempat', $data['tempat'] ?? 'Bolaang Uki') }}" 
                oninvalid="this.setCustomValidity('Tempat harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Contoh: Boroko" required>
              @error('tempat')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Catatan (Opsional)</label>
            <textarea name="catatan" rows="2" class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed" placeholder="Masukkan catatan jika diperlukan...">{{ old('catatan', $data['catatan'] ?? '') }}</textarea>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex gap-3 px-2">
        <a href="{{ route('reports.penerimaan.list') }}" class="flex-1 py-5 bg-app-surface text-app-muted border border-app-main rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
        <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Perbarui BASTB</button>
      </div>
    </form>
  </div>
@endsection
