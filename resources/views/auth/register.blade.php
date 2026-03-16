<x-guest-layout title="DAFTAR" subtitle="Buat akun baru untuk mulai mengelola aset">

  <form method="POST" action="{{ route('register') }}" class="space-y-2 sm:space-y-3" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    {{-- Name --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="far fa-user text-[10px] sm:text-xs"></i>
        </div>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
          class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-2.5 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="Nama Lengkap">
      </div>
      <x-input-error :messages="$errors->get('name')" class="mt-0.5 ml-4" />
    </div>

    {{-- Tanggal Lahir --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal Lahir</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="far fa-calendar-alt text-[10px] sm:text-xs"></i>
        </div>
        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
          class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-2.5 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none">
      </div>
      <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-0.5 ml-4" />
    </div>

    {{-- Jenis Kelamin --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Jenis Kelamin</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-venus-mars text-[10px] sm:text-xs"></i>
        </div>
        <select id="jenis_kelamin" name="jenis_kelamin" required
          class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-2.5 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none appearance-none">
          <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
          <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
          <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
      </div>
      <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-0.5 ml-4" />
    </div>

    {{-- Nama OPD --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama OPD</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-building text-[10px] sm:text-xs"></i>
        </div>
        <input id="nama_opd" type="text" name="nama_opd" value="{{ old('nama_opd') }}" required
          class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-2.5 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="Contoh: Dinas Kesehatan">
      </div>
      <x-input-error :messages="$errors->get('nama_opd')" class="mt-0.5 ml-4" />
    </div>

    {{-- Email --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Aktif</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="far fa-envelope text-[10px] sm:text-xs"></i>
        </div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required
          class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-2.5 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="nama@email.com">
      </div>
      <p class="text-[7px] sm:text-[8px] text-slate-400 ml-4 italic mt-1 font-medium">
        *Kredensial login (Username & Password) akan dikirimkan ke email ini.
      </p>
      <x-input-error :messages="$errors->get('email')" class="mt-0.5 ml-4" />
    </div>

    {{-- Action --}}
    <button type="submit" 
      :disabled="loading"
      class="w-full py-3 sm:py-3.5 bg-indigo-600 text-white rounded-[1.5rem] sm:rounded-[1.8rem] text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 hover:bg-slate-800 transition-all mt-1 sm:mt-2 disabled:opacity-70 disabled:cursor-not-allowed">
      <span x-show="!loading">Register <i class="fas fa-user-plus ml-1 sm:ml-2"></i></span>
      <span x-show="loading" x-cloak><i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses...</span>
    </button>
  </form>

</x-guest-layout>
