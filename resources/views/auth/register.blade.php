{{-- Menggunakan layout untuk user yang belum login (guest) --}}
<x-guest-layout>

    {{-- Header Form --}}
    <div class="mb-2 sm:mb-8 text-center px-4">
        <p class="text-[8px] sm:text-2xl font-black text-brand-500 uppercase tracking-[0.2em] mb-1 sm:mb-3">Register</p>
        <p class="text-white/40 text-[9px] sm:text-sm mt-0.5 sm:mt-2 font-medium">Daftar untuk mulai mengelola persediaan</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3 sm:space-y-5">
        @csrf

        {{-- Input Nama --}}
        <div class="group">
            <label for="name" class="block text-[9px] sm:text-xs font-bold text-gray-400 uppercase mb-0.5 ml-1 tracking-wide">
                Nama Lengkap
            </label>
            <div class="relative transition-all duration-300 transform group-hover:-translate-y-0.5">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-brand-500 transition-colors">
                    <i class="far fa-user text-base sm:text-lg"></i>
                </div>
                <input 
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="form-input pl-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="Nama Lengkap">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- Input Email --}}
        <div class="group">
            <label for="email" class="block text-[9px] sm:text-xs font-bold text-gray-400 uppercase mb-0.5 ml-1 tracking-wide">
                Alamat Email
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
                    class="form-input pl-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Input Password --}}
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
                    autocomplete="new-password"
                    class="form-input pl-10 pr-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-white transition-colors">
                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Input Confirm Password --}}
        <div class="group" x-data="{ show: false }">
            <label for="password_confirmation" class="block text-[9px] sm:text-xs font-bold text-gray-400 uppercase mb-0.5 ml-1 tracking-widest">
                Konfirmasi Password
            </label>
            <div class="relative transition-all duration-300 transform group-hover:-translate-y-0.5">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500 group-focus-within:text-brand-500 transition-colors">
                    <i class="fas fa-check-circle text-base sm:text-lg"></i>
                </div>
                <input 
                    id="password_confirmation"
                    :type="show ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    class="form-input pl-10 pr-10 block w-full rounded-lg border-white/10 bg-white/5 focus:bg-white/10 py-2.5 sm:py-3.5 px-3.5 text-white placeholder-white/20 focus:border-brand-500 focus:ring-0 transition-all duration-200 shadow-sm text-xs sm:text-sm"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-white transition-colors">
                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-1.5 sm:pt-4">
            <button 
                type="submit"
                class="w-full flex justify-center py-3 sm:py-4 px-4 border border-transparent rounded-lg shadow-lg shadow-brand-500/40 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-700 hover:to-brand-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl active:scale-95 tracking-wide uppercase">
                Daftar Sekarang <i class="fas fa-user-plus ml-2"></i>
            </button>
        </div>
    </form>

    <div class="mt-4 sm:mt-8 text-center">
        <p class="text-[10px] sm:text-sm text-gray-500 font-medium">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-brand-500 hover:text-brand-600 transition-all">
                Masuk disini
            </a>
        </p>
    </div>

    {{-- ================= SOCIAL MEDIA LINKS ================= --}}
    <div class="mt-8 pt-6 border-t border-white/5">
        <p class="text-[8px] sm:text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-4 text-center">Hubungi Developer</p>
        <div class="flex items-center justify-center gap-4">
            {{-- WhatsApp --}}
            <a href="https://wa.me/6285824268216" target="_blank"
                onmouseover="this.style.transform='translateY(-4px)'; this.querySelector('i').style.color='#25D366'; this.querySelector('i').style.filter='drop-shadow(0 0 8px rgba(37,211,102,0.8))'; this.querySelector('i').style.transform='scale(1.2)'"
                onmouseout="this.style.transform=''; this.querySelector('i').style.color='rgba(255,255,255,0.2)'; this.querySelector('i').style.filter=''; this.querySelector('i').style.transform=''"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 transition-all duration-300 cursor-pointer"
                title="WhatsApp">
                <i class="fab fa-whatsapp text-lg" style="color: rgba(255,255,255,0.2); transition: all 0.3s"></i>
            </a>

            {{-- Email --}}
            <a href="mailto:supandialentadu14@gmail.com" target="_blank"
                onmouseover="this.style.transform='translateY(-4px)'; this.querySelector('i').style.color='#EA4335'; this.querySelector('i').style.filter='drop-shadow(0 0 8px rgba(234,67,53,0.8))'; this.querySelector('i').style.transform='scale(1.2)'"
                onmouseout="this.style.transform=''; this.querySelector('i').style.color='rgba(255,255,255,0.2)'; this.querySelector('i').style.filter=''; this.querySelector('i').style.transform=''"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 transition-all duration-300 cursor-pointer"
                title="Email Developer">
                <i class="far fa-envelope text-lg" style="color: rgba(255,255,255,0.2); transition: all 0.3s"></i>
            </a>

            {{-- Instagram --}}
            <a href="https://www.instagram.com/emonn_65?igsh=MWM4c2JzdjNvZG4xMQ%3D%3D&utm_source=qr" target="_blank"
                onmouseover="this.style.transform='translateY(-4px)'; this.querySelector('i').style.color='#E1306C'; this.querySelector('i').style.filter='drop-shadow(0 0 8px rgba(225,48,108,0.8))'; this.querySelector('i').style.transform='scale(1.2)'"
                onmouseout="this.style.transform=''; this.querySelector('i').style.color='rgba(255,255,255,0.2)'; this.querySelector('i').style.filter=''; this.querySelector('i').style.transform=''"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 transition-all duration-300 cursor-pointer"
                title="Instagram">
                <i class="fab fa-instagram text-lg" style="color: rgba(255,255,255,0.2); transition: all 0.3s"></i>
            </a>

            {{-- Facebook --}}
            <a href="https://www.facebook.com/share/18J61xd2XQ/?mibextid=wwXIfr" target="_blank"
                onmouseover="this.style.transform='translateY(-4px)'; this.querySelector('i').style.color='#1877F2'; this.querySelector('i').style.filter='drop-shadow(0 0 8px rgba(24,119,242,0.8))'; this.querySelector('i').style.transform='scale(1.2)'"
                onmouseout="this.style.transform=''; this.querySelector('i').style.color='rgba(255,255,255,0.2)'; this.querySelector('i').style.filter=''; this.querySelector('i').style.transform=''"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 transition-all duration-300 cursor-pointer"
                title="Facebook">
                <i class="fab fa-facebook-f text-lg" style="color: rgba(255,255,255,0.2); transition: all 0.3s"></i>
            </a>
        </div>
    </div>
</x-guest-layout>
