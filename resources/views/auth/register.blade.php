<x-guest-layout title="DAFTAR" subtitle="Buat akun untuk manajemen persediaan & pantau jaringan telekomunikasi">

  <form method="POST" action="{{ route('register') }}" class="space-y-1.5 sm:space-y-2" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    {{-- Name --}}
    <div class="space-y-0.5">
      <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-user text-[10px]"></i>
        </div>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
          class="w-full pl-10 pr-4 py-1.5 sm:py-2 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="Nama Lengkap">
      </div>
      <x-input-error :messages="$errors->get('name')" class="mt-0.5 ml-4" />
    </div>

    {{-- Desktop Grid Row: Tgl Lahir & Gender --}}
    <div class="grid grid-cols-2 gap-2">
      {{-- Tanggal Lahir --}}
      <div class="space-y-0.5">
        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Tgl Lahir</label>
        <div class="relative group"> 
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
            <i class="fas fa-calendar-day text-[10px]"></i>
          </div>
          <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
            class="w-full pl-10 pr-4 py-1.5 sm:py-2 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none">
        </div>
      </div>

      {{-- Jenis Kelamin --}}
      <div class="space-y-0.5">
        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Gender</label>
        <div class="relative group"> 
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
            <i class="fas fa-venus-mars text-[10px]"></i>
          </div>
          <select id="jenis_kelamin" name="jenis_kelamin" required
            class="w-full pl-10 pr-4 py-1.5 sm:py-2 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none appearance-none">
            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih</option>
            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>L</option>
            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>P</option>
          </select>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-2 gap-2 -mt-1">
        <x-input-error :messages="$errors->get('tanggal_lahir')" class="ml-4" />
        <x-input-error :messages="$errors->get('jenis_kelamin')" class="ml-4" />
    </div>

    {{-- Nama OPD --}}
    <div class="space-y-0.5">
      <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama OPD</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-building text-[10px]"></i>
        </div>
        <input id="nama_opd" type="text" name="nama_opd" value="{{ old('nama_opd') }}" required
          class="w-full pl-10 pr-4 py-1.5 sm:py-2 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="Dinas / OPD">
      </div>
      <x-input-error :messages="$errors->get('nama_opd')" class="mt-0.5 ml-4" />
    </div>

    {{-- Email --}}
    <div class="space-y-0.5">
      <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest ml-4">Email Aktif</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-envelope text-[10px]"></i>
        </div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required
          class="w-full pl-10 pr-4 py-1.5 sm:py-2 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="nama@email.com">
      </div>
      <p class="text-[7px] text-slate-400 ml-4 italic mt-0.5 font-medium">
        *Password akan dikirim ke email ini.
      </p>
      <x-input-error :messages="$errors->get('email')" class="mt-0.5 ml-4" />
    </div>

    {{-- Action --}}
    <button type="submit" 
      :disabled="loading"
      class="w-full py-2.5 sm:py-3 bg-indigo-600 text-white rounded-[1.5rem] text-[9px] font-black uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 hover:bg-slate-800 transition-all mt-2 disabled:opacity-70 disabled:cursor-not-allowed">
      <span x-show="!loading">Register <i class="fas fa-user-plus ml-1"></i></span>
      <span x-show="loading" x-cloak><i class="fas fa-circle-notch fa-spin mr-1"></i>...</span>
    </button>
  </form>

</x-guest-layout>
