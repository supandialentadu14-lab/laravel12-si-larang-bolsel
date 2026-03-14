<x-guest-layout title="LOGIN">

  @if (session('success_message'))
    <div class="mb-4 animate__animated animate__fadeIn">
      <div class="bg-emerald-50 border border-emerald-100/50 rounded-2xl p-3 flex items-center gap-3">
        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
        <p class="text-emerald-700 font-bold text-[9px] leading-tight">{{ session('success_message') }}</p>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-4">
    @csrf

    {{-- Email --}}
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Email</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="far fa-envelope text-[10px] sm:text-xs"></i>
        </div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
          class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="nama@email.com">
      </div>
      <x-input-error :messages="$errors->get('email')" class="mt-0.5 ml-4" />
    </div>

    {{-- Password --}}
    <div class="space-y-0.5 sm:space-y-1" x-data="{ show: false }">
      <div class="flex items-center justify-between px-4">
        <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">Password</label>
      </div>
      <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-lock text-[10px] sm:text-xs"></i>
        </div>
        <input id="password" :type="show ? 'text' : 'password'" name="password" required
          class="w-full pl-10 sm:pl-12 pr-10 sm:pr-12 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="••••••••">
        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 sm:pr-5 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
          <i class="fas text-[9px] sm:text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
        </button>
      </div>
      <x-input-error :messages="$errors->get('password')" class="mt-0.5 ml-4" />
    </div>

    <div class="flex items-center justify-between px-4">
      <label class="flex items-center cursor-pointer group">
        <input id="remember_me" name="remember" type="checkbox" class="sr-only peer">
        <div class="w-8 h-4 bg-slate-100 rounded-full peer peer-checked:bg-slate-800 transition-all relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
        <span class="ml-2.5 text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">Ingat</span>
      </label>
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-[8px] sm:text-[9px] font-black text-indigo-600 uppercase">Lupa?</a>
      @endif
    </div>

    <button type="submit" class="w-full py-3.5 sm:py-4 bg-indigo-600 text-white rounded-[1.5rem] sm:rounded-[1.8rem] text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-slate-800 transition-all">
      Masuk <i class="fas fa-arrow-right ml-1 sm:ml-2 text-[9px] sm:text-[10px]"></i>
    </button>
  </form>

  <div class="mt-6 text-center pt-4 border-t border-slate-50">
    <p class="text-[9px] font-bold text-slate-400 uppercase">
      Belum punya akun?
      <a href="{{ route('register') }}" class="font-black text-indigo-600 hover:text-slate-800 ml-1">Daftar disini</a>
    </p>
  </div>

</x-guest-layout>
