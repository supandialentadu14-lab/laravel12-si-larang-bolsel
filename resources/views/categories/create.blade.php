@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah Jenis Belanja</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Input Kategori Inventaris</p>
    </div>
    <a href="{{ route('categories.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
      @csrf

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name') }}" 
          oninvalid="this.setCustomValidity('Kolom Nama Kategori harus diisi')" 
          oninput="this.setCustomValidity('')"
          class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        @error('name')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
      </div>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
        <textarea name="description" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">{{ old('description') }}</textarea>
        @error('description')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
      </div>

      <div class="grid grid-cols-2 gap-3 pt-2">
        <a href="{{ route('categories.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
