<x-guest-layout>

    {{-- Header Form --}}
    <div class="mb-8 text-center px-4">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Login Akun</h1>
        <p class="text-slate-400 text-[10px] sm:text-xs mt-2 font-bold uppercase tracking-[0.2em]">Silakan masuk untuk melanjutkan</p>
    </div>

    {{-- Session Status Success --}}
    @if (session('success_message'))
        <div class="mb-8 animate__animated animate__fadeInUp">
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div>
                    <p class="text-emerald-800 font-black text-[10px] uppercase tracking-widest">Berhasil!</p>
                    <p class="text-emerald-600 font-bold text-xs mt-0.5 leading-tight">{{ session('success_message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email --}}
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">
                Email Address
            </label>
            <div class="relative group"> 
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="far fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 focus:border-indigo-600 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 ml-4" />
        </div>

        {{-- Password --}}
        <div class="space-y-2" x-data="{ show: false }">
            <div class="flex items-center justify-between px-4">
                <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Security Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">
                        Lupa?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                    class="w-full pl-14 pr-14 py-4 bg-white border-2 border-slate-100 focus:border-indigo-600 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-300 hover:text-slate-600 transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 ml-4" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center px-4">
            <label class="flex items-center cursor-pointer group">
                <input id="remember_me" name="remember" type="checkbox" class="sr-only peer">
                <div class="w-10 h-6 bg-slate-100 rounded-full peer peer-checked:bg-indigo-600 transition-all relative after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                <span class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Ingat Saya</span>
            </label>
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit"
                class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all flex items-center justify-center gap-3">
                Masuk Sekarang
                <i class="fas fa-arrow-right text-[10px]"></i>
            </button>
        </div>
    </form>

    {{-- Link Register --}}
    <div class="mt-10 text-center border-t border-slate-50 pt-8">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-black text-indigo-600 hover:text-indigo-700 ml-1">
                Daftar Baru
            </a>
        </p>
    </div>

    {{-- Developer Contact --}}
    <div class="mt-8 pt-6">
        <div class="flex items-center justify-center gap-4">
            {{-- WhatsApp --}}
            <a href="https://wa.me/6285824268216" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-green-50 hover:text-green-500 border border-transparent hover:border-green-100">
                <i class="fab fa-whatsapp"></i>
            </a>
            {{-- Instagram --}}
            <a href="https://www.instagram.com/emonn_65" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-rose-50 hover:text-rose-500 border border-transparent hover:border-rose-100">
                <i class="fab fa-instagram"></i>
            </a>
            {{-- Facebook --}}
            <a href="https://www.facebook.com/share/18J61xd2XQ" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-blue-50 hover:text-blue-500 border border-transparent hover:border-blue-100">
                <i class="fab fa-facebook-f text-xs"></i>
            </a>
        </div>
    </div>

</x-guest-layout>

