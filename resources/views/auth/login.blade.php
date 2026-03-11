{{-- Menggunakan layout khusus untuk halaman guest (belum login) --}}
<x-guest-layout>

    {{-- Header Form --}}
    <div class="mb-2 sm:mb-2 text-center px-4">
        <p class="text-[8px] sm:text-2xl font-black text-brand-500 uppercase tracking-[0.2em] mb-1 sm:mb-3">Login</p>
        <p class="text-white/40 text-[9px] sm:text-sm mt-0.5 sm:mt-2 font-medium">Silakan masuk ke akun Anda</p>
    </div>

    {{-- Form login, method POST, dikirim ke route bernama 'login' --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-3 sm:space-y-5">

        {{-- Token keamanan CSRF (wajib di Laravel) --}}
        @csrf

        {{-- ================= INPUT EMAIL ================= --}}
        <div class="group">
            <label for="email" class="block text-[9px] sm:text-xs font-bold text-gray-400 uppercase mb-0.5 ml-1 tracking-wide">
                Email
            </label>
            <div class="relative transition-all duration-300 transform group-hover:-translate-y-0.5"> 
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-brand-500 transition-colors">
                    <i class="far fa-envelope text-base sm:text-lg"></i>
                </div>
                <input 
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="form-input pl-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- ================= INPUT PASSWORD ================= --}}
        <div class="group" x-data="{ show: false }">
            <label for="password" class="block text-[9px] sm:text-xs font-bold text-gray-400 uppercase mb-0.5 ml-1 tracking-widest">
                Password
            </label>
            <div class="relative transition-all duration-300 transform group-hover:-translate-y-0.5">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-brand-500 transition-colors">
                    <i class="fas fa-lock text-base sm:text-lg"></i>
                </div>
                <input 
                    id="password"
                    :type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="form-input pl-10 pr-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-white transition-colors">
                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- ================= REMEMBER ME & FORGOT PASSWORD ================= --}}
        <div class="flex items-center justify-between pt-2">
            <div class="flex items-center">
                <input 
                    id="remember_me"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer transition-colors">
                <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer select-none font-medium">
                    Ingat Saya
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                    Lupa Password?
                </a>
            @endif
        </div>

        {{-- ================= TOMBOL SUBMIT ================= --}}
        <div class="pt-1.5 sm:pt-4">
            <button 
                type="submit"
                class="w-full flex justify-center py-3 sm:py-4 px-4 border border-transparent rounded-lg shadow-lg shadow-brand-500/40 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl active:scale-95 tracking-wide uppercase">
                Masuk Sekarang <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>

    </form>

    {{-- ================= LINK REGISTER ================= --}}
    <div class="mt-4 sm:mt-8 text-center">
        <p class="text-[10px] sm:text-sm text-gray-500 font-medium">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-brand-500 hover:text-brand-600 transition-all">
                Daftar Akun Baru
            </a>
        </p>
    </div>

</x-guest-layout>
