<x-guest-layout title="REGISTER">

    <form method="POST" action="{{ route('register') }}" class="space-y-2 sm:space-y-3" x-data="{ show: false }">
        @csrf

        {{-- Name --}}
        <div class="space-y-0.5 sm:space-y-1">
            <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Username</label>
            <div class="relative group"> 
                <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="far fa-user text-[10px] sm:text-xs"></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
                    placeholder="Nama Lengkap">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-0.5 ml-4" />
        </div>

        {{-- Email --}}
        <div class="space-y-0.5 sm:space-y-1">
            <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Email</label>
            <div class="relative group"> 
                <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                    <i class="far fa-envelope text-[10px] sm:text-xs"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-0.5 ml-4" />
        </div>

        {{-- Passwords --}}
        <div class="space-y-2 sm:space-y-3">
            <div class="space-y-0.5 sm:space-y-1">
                <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Password</label>
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
            </div>
            <div class="space-y-0.5 sm:space-y-1">
                <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Confirm Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-shield-alt text-[10px] sm:text-xs"></i>
                    </div>
                    <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                        class="w-full pl-10 sm:pl-12 pr-10 sm:pr-12 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
                        placeholder="••••••••">
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-0.5 ml-4" />
        </div>

        {{-- Action --}}
        <button type="submit" class="w-full py-3.5 sm:py-4 bg-indigo-600 text-white rounded-[1.5rem] sm:rounded-[1.8rem] text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 hover:bg-slate-800 transition-all mt-2 sm:mt-4">
            Register <i class="fas fa-user-plus ml-1 sm:ml-2"></i>
        </button>
    </form>

    <div class="mt-6 text-center pt-4 border-t border-slate-50">
        <p class="text-[9px] font-bold text-slate-400 uppercase">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-black text-indigo-600 hover:text-slate-800 ml-1">Login disini</a>
        </p>
    </div>

</x-guest-layout>
