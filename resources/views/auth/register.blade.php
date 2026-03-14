<x-guest-layout>

    {{-- Header Form --}}
    <div class="mb-8 text-center px-4">
        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Daftar Akun</h1>
        <p class="text-slate-400 text-[10px] sm:text-xs mt-2 font-bold uppercase tracking-[0.2em]">Mulai kelola persediaan anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div class="space-y-2">
            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">
                Full Name
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="far fa-user text-sm"></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-600/10 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="Nama Lengkap">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 ml-4" />
        </div>

        {{-- Email --}}
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">
                Email Address
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="far fa-envelope text-sm"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full pl-14 pr-6 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-600/10 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 ml-4" />
        </div>

        {{-- Password --}}
        <div class="space-y-2" x-data="{ show: false }">
            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">
                New Password
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                    class="w-full pl-14 pr-14 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-600/10 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-300 hover:text-slate-600 transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 ml-4" />
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-2" x-data="{ show: false }">
            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">
                Confirm Security
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="fas fa-check-circle text-sm"></i>
                </div>
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                    class="w-full pl-14 pr-14 py-4 bg-slate-50 border-2 border-transparent focus:border-indigo-600/10 focus:bg-white rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-300 transition-all outline-none"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-6 flex items-center text-slate-300 hover:text-slate-600 transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit"
                class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all flex items-center justify-center gap-3">
                Daftar Sekarang
                <i class="fas fa-user-plus text-[10px]"></i>
            </button>
        </div>
    </form>

    <div class="mt-10 text-center border-t border-slate-50 pt-8">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-black text-indigo-600 hover:text-indigo-700 ml-1">
                Masuk Disini
            </a>
        </p>
    </div>

    {{-- Contact --}}
    <div class="mt-8 pt-6">
        <div class="flex items-center justify-center gap-4">
            <a href="https://wa.me/6285824268216" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-green-50 hover:text-green-500 border border-transparent hover:border-green-100">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.instagram.com/emonn_65" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-rose-50 hover:text-rose-500 border border-transparent hover:border-rose-100">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/share/18J61xd2XQ" target="_blank"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 transition-all hover:bg-blue-50 hover:text-blue-500 border border-transparent hover:border-blue-100">
                <i class="fab fa-facebook-f text-xs"></i>
            </a>
        </div>
    </div>

</x-guest-layout>

