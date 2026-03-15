@extends('layouts.mobile')

@section('content')
<div class="space-y-6 pb-24">
  {{-- Page Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah User</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Daftarkan Pengguna Baru</p>
    </div>
    <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Informasi Akun --}}
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
      <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
          <i class="fas fa-user-circle text-xs"></i>
        </div>
        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Informasi Akun</h3>
      </div>

      <div class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: John Doe" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        </div>

        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Alamat Email</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        </div>
      </div>
    </div>

    {{-- Hak Akses (Permission) --}}
    <div x-data="{ role: '{{ old('role', 'staff') }}' }" class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
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
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Izin Akses Menu & Fitur</label>
          <div class="grid grid-cols-1 gap-2">
            {{-- Fitur Chat --}}
            <label class="flex items-center justify-between p-4 bg-indigo-50/30 rounded-2xl border border-indigo-100/50 hover:border-indigo-200 transition-all cursor-pointer group">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                  <i class="fas fa-comment-dots text-[10px]"></i>
                </div>
                <span class="text-[10px] font-black text-slate-700 uppercase tracking-tight group-hover:text-indigo-600">Akses Fitur Chat (Internal)</span>
              </div>
              <div class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="chat_enabled" value="1" {{ old('chat_enabled') ? 'checked' : '' }} class="sr-only peer">
                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
              </div>
            </label>

            @foreach(config('permissions', []) as $key => $label)
              @php $userPermissions = old('permissions', array_keys(config('permissions'))); @endphp
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

    {{-- Keamanan --}}
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
      <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
          <i class="fas fa-lock text-xs"></i>
        </div>
        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Keamanan</h3>
      </div>

      <div class="grid grid-cols-1 gap-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Password</label>
          <input type="password" name="password" placeholder="••••••••" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        </div>
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 px-2">
      <a href="{{ route('users.index') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
      <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Simpan User</button>
    </div>
  </form>
</div>
@endsection
