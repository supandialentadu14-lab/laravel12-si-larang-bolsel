@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div x-data="{
  showFilters: {{ request('search') ? 'true' : 'false' }},
  showStatusLegend: false
}" class="space-y-6">

  {{-- Page Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight transition-colors">Pengguna</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 transition-colors">Kelola Akses Sistem</p>
    </div>
    <div class="flex gap-2">
      <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50 ' : ''">
        <i class="fas fa-filter text-xs"></i>
      </button>
      @if(auth()->user()->isAdmin())
      <a href="{{ route('users.create') }}" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
        <i class="fas fa-user-plus text-xs"></i>
      </a>
      @endif
    </div>
  </div>

  {{-- Filter Card --}}
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4 transition-colors">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2 transition-colors">Cari Pengguna</h3>
      <form action="{{ route('users.index') }}" method="GET" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Nama atau Email</label>
          <div class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs transition-colors"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100 transition-all">Terapkan</button>
          <a href="{{ route('users.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-colors">Reset</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Summary Card --}}
  <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group transition-all">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10 transition-colors">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Akun</p>
        <i class="fas fa-users opacity-20"></i>
      </div>
      <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $users->total() }} User</h2>
      <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest transition-colors">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 mr-1"></span> 
        {{ $users->filter(fn($u) => $u->isOnline())->count() }} Online Sekarang
      </p>
    </div>
  </div>

  {{-- User List --}}
  <div class="space-y-4 pb-24">
    @forelse($users as $user)
      @php $isOnline = $user->isOnline(); $isActive = $user->is_active; @endphp
      <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl transition-all duration-300 {{ !$isActive ? 'opacity-60' : '' }}">
        <div class="flex items-start gap-4">
          {{-- Avatar Section --}}
          <div class="relative flex-shrink-0">
            <img class="w-14 h-14 rounded-2xl object-cover ring-4 {{ $isOnline ? 'ring-emerald-50 ' : 'ring-slate-50 ' }} transition-all"
               src="{{ $user->avatar ? asset('media/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4F46E5&color=ffffff' }}"
               alt="{{ $user->name }}">
            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white shadow-sm {{ $isOnline ? 'bg-emerald-400 animate-pulse' : 'bg-slate-300 ' }} transition-colors"></span>
          </div>

          {{-- Info Section --}}
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
              <div class="pr-2">
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-tight truncate leading-tight transition-colors">
                  {{ $user->name }}
                  @if($user->id === auth()->id())
                    <span class="ml-1 text-[8px] bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded-full transition-colors uppercase">ANDA</span>
                  @endif
                </h3>
                <p class="text-[9px] font-bold text-slate-400 truncate mt-1 transition-colors">{{ $user->email }}</p>
              </div>
              <div class="text-right flex-shrink-0">
                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest transition-colors
                  {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 ' : 'bg-slate-100 text-slate-600 ' }}">
                  {{ $user->role }}
                </span>
              </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-50">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-1.5">
                  <div class="w-1.5 h-1.5 rounded-full {{ $isOnline ? 'bg-emerald-400 animate-pulse' : 'bg-slate-300' }}"></div>
                  <span class="text-[8px] font-black {{ $isOnline ? 'text-emerald-600' : 'text-slate-400' }} uppercase tracking-widest">
                    {{ $isOnline ? 'ACTIVE NOW' : ($user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'OFFLINE') }}
                  </span>
                </div>
              </div>

              {{-- Actions --}}
              <div class="flex items-center gap-1.5 flex-wrap">
                @if(auth()->user()->isAdmin())
                  {{-- Backup --}}
                  <a href="{{ route('users.backup', $user) }}" class="w-8 h-8 rounded-xl bg-blue-50/50 text-blue-500 flex items-center justify-center hover:bg-blue-50 transition-colors" title="Backup" onclick="return confirm('Download backup data milik {{ $user->name }}?')">
                    <i class="fas fa-download text-[10px]"></i>
                  </a>

                  {{-- Restore --}}
                  <form action="{{ route('users.restore', $user) }}" method="POST" enctype="multipart/form-data" class="inline" id="form-restore-{{ $user->id }}">
                    @csrf
                    <input type="file" name="backup_file" id="backup-file-{{ $user->id }}" class="hidden" accept=".sql" onchange="if(confirm('Pulihkan data {{ $user->name }}? Data lama akan tertimpa.')) document.getElementById('form-restore-{{ $user->id }}').submit();">
                    <button type="button" class="w-8 h-8 rounded-xl bg-emerald-50/50 text-emerald-500 flex items-center justify-center hover:bg-emerald-50 transition-colors" title="Restore" onclick="document.getElementById('backup-file-{{ $user->id }}').click();">
                      <i class="fas fa-upload text-[10px]"></i>
                    </button>
                  </form>
                @endif

                @if($user->id !== auth()->id() && (auth()->user()->chat_enabled || auth()->user()->isAdmin()))
                  <a href="{{ route('chat.show', $user) }}" class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-colors" title="Chat">
                    <i class="fas fa-comment-dots text-[10px]"></i>
                  </a>
                @endif

                <a href="{{ route('users.edit', $user) }}" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                  <i class="fas fa-edit text-[10px]"></i>
                </a>
                
                @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
                  {{-- Toggle Chat Permission moved to Edit/Create --}}

                  @if($user->role !== 'admin')
                    <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="inline">
                      @csrf
                      <button type="submit" class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors {{ $isActive ? 'bg-orange-50/50 text-orange-400 hover:bg-orange-50' : 'bg-emerald-50 text-emerald-600' }}"
                          onclick="return confirm('{{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')">
                        <i class="fas {{ $isActive ? 'fa-ban' : 'fa-check-circle' }} text-[10px]"></i>
                      </button>
                    </form>
                  @endif

                  <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-xl bg-rose-50/50 text-rose-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                      <i class="fas fa-trash text-[10px]"></i>
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm transition-colors">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-users-slash text-3xl text-slate-200 "></i>
        </div>
        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tidak Ada Data</h3>
      </div>
    @endforelse

    <div class="pt-4">
      {{ $users->links() }}
    </div>
  </div>
</div>

{{-- Auto-refresh script --}}
<script>
  (function() {
    const indexUrl = '{{ route("users.index") }}';
    let timer = setTimeout(function() {
      if (window.location.pathname === new URL(indexUrl).pathname) {
        window.location.reload();
      }
    }, 30000);
  })();
</script>
@endsection
