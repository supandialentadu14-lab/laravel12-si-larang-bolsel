@extends('layouts.mobile')

@section('content')
<div class="space-y-6 pb-24">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ request()->routeIs('profile.edit') ? 'Profil' : 'Edit User' }}</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">{{ request()->routeIs('profile.edit') ? 'Pengaturan Akun Anda' : 'Perbarui Data Pengguna' }}</p>
        </div>
        <a href="{{ request()->routeIs('profile.edit') ? route('dashboard') : route('users.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
            <i class="fas fa-times text-xs"></i>
        </a>
    </div>

    <form action="{{ request()->routeIs('profile.edit') ? route('profile.update') : route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Foto Profil --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 flex flex-col items-center text-center space-y-4">
            <div class="relative">
                <img class="w-24 h-24 rounded-[2rem] object-cover ring-4 ring-white/20 shadow-2xl"
                     src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=ffffff&color=4F46E5' }}"
                     alt="Avatar">
                <label class="absolute -bottom-2 -right-2 w-10 h-10 bg-white text-indigo-600 rounded-2xl flex items-center justify-center shadow-lg cursor-pointer active:scale-90 transition-transform">
                    <i class="fas fa-camera text-sm"></i>
                    <input type="file" name="avatar" accept="image/*" class="hidden">
                </label>
            </div>
            <div>
                <h3 class="text-lg font-black uppercase tracking-tight">{{ $user->name }}</h3>
                <p class="text-[10px] font-bold opacity-60 tracking-[0.2em]">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Informasi Akun --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user-circle text-xs"></i>
                </div>
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Informasi Dasar</h3>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                </div>
            </div>
        </div>

        {{-- Hak Akses (Hanya untuk Admin) --}}
        @if(auth()->user()->isAdmin() && !request()->routeIs('profile.edit'))
        <div x-data="{ role: '{{ old('role', $user->role) }}' }" class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-xs"></i>
                </div>
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Hak Akses & Izin</h3>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Peran Pengguna</label>
                    <div class="flex p-1.5 bg-slate-50 rounded-2xl">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="role" value="staff" x-model="role" class="peer hidden">
                            <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all">Staff</div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="role" value="admin" x-model="role" class="peer hidden">
                            <div class="py-3 rounded-xl text-center text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-purple-600 peer-checked:shadow-sm transition-all">Admin</div>
                        </label>
                    </div>
                </div>

                {{-- Permission Checkboxes --}}
                <div x-show="role === 'staff'" x-transition class="space-y-3 pt-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Izin Akses Menu</label>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach(config('permissions', []) as $key => $label)
                            @php
                                $userPermissions = old('permissions', $user->permissions ?? []);
                                if (!is_array($userPermissions)) $userPermissions = [];
                            @endphp
                            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-indigo-100 transition-all cursor-pointer group">
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-tight group-hover:text-indigo-600">{{ $label }}</span>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" {{ in_array($key, $userPermissions) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Keamanan --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-lock text-xs"></i>
                </div>
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Ganti Password</h3>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest px-2 leading-relaxed">
                    <i class="fas fa-info-circle text-indigo-400 mr-1"></i> Kosongkan jika tidak ingin mengubah password
                </p>
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Password Baru</label>
                    <div class="relative flex items-center">
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        <button type="button" @click="show = !show" class="absolute right-5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Konfirmasi Password</label>
                    <div class="relative flex items-center">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        <button type="button" @click="show = !show" class="absolute right-5 text-slate-400 hover:text-indigo-600 transition-colors">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 px-2">
            <a href="{{ request()->routeIs('profile.edit') ? route('dashboard') : route('users.index') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
            <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">{{ request()->routeIs('profile.edit') ? 'Simpan Profil' : 'Perbarui User' }}</button>
        </div>

    </form>
    
    @if(request()->routeIs('profile.edit'))
        {{-- Logout Section khusus di halaman Profil --}}
        <div class="px-2 pt-4">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
                @csrf
                <button type="submit" class="w-full py-5 bg-rose-50 text-rose-600 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 border border-rose-100 active:scale-95 transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
