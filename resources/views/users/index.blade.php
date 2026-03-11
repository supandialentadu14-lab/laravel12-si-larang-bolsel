@extends('layouts.admin')

@section('header', 'Pengaturan Hak Akses')
@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

    {{-- ── Header + Tombol Tambah ─────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna</h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola hak akses dan status pengguna sistem</p>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-100 transition-all duration-200 hover:-translate-y-0.5 text-sm">
            <i class="fas fa-user-plus"></i> Tambah Pengguna Baru
        </a>
        @endif
    </div>

    {{-- ── Flash Messages ──────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            <i class="fas fa-check-circle text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Tabel ────────────────────────────────────────────────────── --}}
    <div class="overflow-x-auto border border-slate-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/60 text-[10px] uppercase font-bold text-indigo-600 tracking-widest border-b border-indigo-100">
                <tr>
                    <th class="px-5 py-4 text-center">Profil</th>
                    <th class="px-5 py-4">Nama Lengkap</th>
                    <th class="px-5 py-4">Alamat Email</th>
                    <th class="px-5 py-4">Hak Akses</th>
                    <th class="px-5 py-4 text-center">Status Online</th>
                    <th class="px-5 py-4 text-center">Status Akun</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                @php $isOnline = $user->isOnline(); $isActive = $user->is_active; @endphp
                <tr class="transition-all duration-200 group {{ !$isActive ? 'opacity-60' : '' }}">

                    {{-- Avatar + Online dot --}}
                    <td class="px-5 py-3.5 text-center">
                        <div class="relative inline-block">
                            <img class="w-10 h-10 rounded-xl object-cover ring-2 {{ $isOnline ? 'ring-emerald-400' : 'ring-slate-200' }} shadow-sm"
                                 src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4F46E5&color=ffffff' }}"
                                 alt="{{ $user->name }}">
                            {{-- Online indicator dot --}}
                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full border-2 border-white shadow-sm
                                         {{ $isOnline ? 'bg-emerald-400' : 'bg-slate-300' }}"
                                  title="{{ $isOnline ? 'Online sekarang' : ($user->last_seen_at ? 'Terakhir aktif '.($user->last_seen_at->diffForHumans()) : 'Belum pernah login') }}">
                            </span>
                        </div>
                    </td>

                    {{-- Nama --}}
                    <td class="px-5 py-3.5">
                        <span class="font-bold text-slate-800">{{ $user->name }}</span>
                        @if($user->id === auth()->id())
                            <span class="ml-1.5 text-[10px] bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-full font-bold">Anda</span>
                        @endif
                    </td>

                    {{-- Email --}}
                    <td class="px-5 py-3.5 text-slate-500">{{ $user->email }}</td>

                    {{-- Role --}}
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                     {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-shield-alt' : 'fa-user' }}"></i>
                            {{ $user->role }}
                        </span>
                    </td>

                    {{-- Status Online --}}
                    <td class="px-5 py-3.5 text-center">
                        @if($isOnline)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Online
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                @if($user->last_seen_at)
                                    {{ $user->last_seen_at->diffForHumans() }}
                                @else
                                    Offline
                                @endif
                            </span>
                        @endif
                    </td>

                    {{-- Status Akun (Aktif / Nonaktif) --}}
                    <td class="px-5 py-3.5 text-center">
                        @if($user->role === 'admin' || $user->id === auth()->id())
                            {{-- Admin tidak bisa di-toggle --}}
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-500 border border-indigo-100">
                                <i class="fas fa-lock text-[8px]"></i> Selalu Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold
                                         {{ $isActive ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                <i class="fas {{ $isActive ? 'fa-check-circle' : 'fa-ban' }} text-[10px]"></i>
                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-5 py-3.5 text-right">
                        <div class="flex items-center justify-end gap-2">

                            {{-- Edit --}}
                            <a href="{{ route('users.edit', $user) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 hover:bg-amber-200 transition-colors"
                               title="Edit pengguna">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            @if(auth()->user()->isAdmin() && $user->id !== auth()->id())

                                {{-- Toggle Aktif / Nonaktif (hanya untuk staff) --}}
                                @if($user->role !== 'admin')
                                    <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors
                                                       {{ $isActive
                                                            ? 'bg-orange-100 text-orange-700 hover:bg-orange-200'
                                                            : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}"
                                                title="{{ $isActive ? 'Nonaktifkan pengguna' : 'Aktifkan pengguna' }}"
                                                onclick="return confirm('{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')">
                                            <i class="fas {{ $isActive ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif

                                {{-- Hapus --}}
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-700 hover:bg-red-200 transition-colors"
                                            title="Hapus pengguna">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                        <i class="fas fa-users text-3xl mb-2 block opacity-30"></i>
                        Belum ada data pengguna.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="mt-4">
        {{ $users->links() }}
    </div>
    @endif

    {{-- Legend --}}
    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-slate-500">
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Online (aktif dalam 3 menit terakhir)</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
            <span>Offline</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-4 h-4 rounded bg-orange-100 border border-orange-200 inline-flex items-center justify-center">
                <i class="fas fa-ban text-orange-600 text-[8px]"></i>
            </span>
            <span>Nonaktifkan = staf tidak bisa login / input data</span>
        </div>
    </div>
</div>

{{-- Auto-refresh status online setiap 30 detik (hanya jika masih di halaman ini) --}}
<script>
    (function() {
        const indexUrl = '{{ route("users.index") }}';
        let timer = null;

        function scheduleReload() {
            timer = setTimeout(function() {
                // Hanya reload jika URL saat ini masih halaman users index
                const currentPath = window.location.pathname;
                const indexPath = new URL(indexUrl).pathname;
                if (currentPath === indexPath) {
                    window.location.href = indexUrl;
                }
            }, 30000);
        }

        // Batalkan timer jika user submit form (navigasi away)
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                clearTimeout(timer);
            });
        });

        scheduleReload();
    })();
</script>

@endsection
