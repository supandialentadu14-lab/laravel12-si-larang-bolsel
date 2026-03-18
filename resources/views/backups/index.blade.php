@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2 md:px-0">
        <div>
            <h1 class="text-2xl font-black text-app-main tracking-tight uppercase">Pusat Cadangan Data</h1>
            <p class="text-[10px] md:text-sm font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Database & Pemulihan Sistem</p>
        </div>
        <form action="{{ route('backups.create') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full md:w-auto px-6 py-4 md:py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-lg shadow-indigo-600/20 active:scale-[0.97] transition-all flex items-center justify-center group">
                <i class="fas fa-database transition-transform group-hover:scale-110 mr-2.5"></i>
                <span>Buat Cadangan Baru</span>
            </button>
        </form>
    </div>

    @if(!($isMobile ?? false))
    <!-- Stats Overview Desktop -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-app-surface p-6 rounded-[32px] border border-app-main shadow-sm relative overflow-hidden group transition-colors">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-app-muted uppercase tracking-widest">Total File</p>
                <h3 class="text-3xl font-black text-app-main mt-1">{{ count($backups) }}</h3>
                <p class="text-xs font-bold text-app-muted mt-1 italic">File SQL tersimpan</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.06] transition-opacity dark:text-white">
                <i class="fas fa-folder-open text-9xl"></i>
            </div>
        </div>
        
        <div class="bg-app-surface p-6 rounded-[32px] border border-app-main shadow-sm relative overflow-hidden group transition-colors">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-app-muted uppercase tracking-widest">Terakhir Dibuat</p>
                <h3 class="text-xl font-black text-app-main mt-1">{{ count($backups) > 0 ? $backups[0]['at'] : 'Belum ada' }}</h3>
                <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mt-1 uppercase tracking-widest font-mono">Timestamp System</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.06] transition-opacity dark:text-white">
                <i class="fas fa-clock text-9xl"></i>
            </div>
        </div>

        <div class="bg-indigo-600 p-6 rounded-[32px] shadow-lg shadow-indigo-600/20 relative overflow-hidden group">
            <div class="relative z-10 text-white">
                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Keamanan</p>
                <h3 class="text-xl font-black mt-1">Sistem Terproteksi</h3>
                <p class="text-xs font-bold text-indigo-100 mt-1 italic">Hanya Admin yang memiliki akses</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:opacity-20 transition-opacity text-white">
                <i class="fas fa-shield-alt text-9xl"></i>
            </div>
        </div>
    </div>
    @else
    <!-- Stats Overview Mobile -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-app-surface p-5 rounded-[2.5rem] border border-app-main shadow-sm transition-colors">
            <p class="text-[9px] font-black text-app-muted uppercase tracking-widest leading-none">Total File</p>
            <h3 class="text-2xl font-black text-app-main mt-2 leading-none">{{ count($backups) }}</h3>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-[8px] font-bold text-app-muted uppercase tracking-tighter">SQL Files</span>
                <i class="fas fa-database text-[10px] text-app-muted opacity-30"></i>
            </div>
        </div>
        <div class="bg-indigo-600 p-5 rounded-[2.5rem] shadow-lg shadow-indigo-600/20">
            <p class="text-[9px] font-black text-indigo-200 uppercase tracking-widest leading-none">Terakhir</p>
            <h3 class="text-xs font-black text-white mt-2 leading-tight">{{ count($backups) > 0 ? date('d M Y', strtotime($backups[0]['at'])) : 'N/A' }}</h3>
            <div class="mt-3 flex items-center justify-between">
                <span class="text-[8px] font-bold text-indigo-200 uppercase tracking-tighter">{{ count($backups) > 0 ? date('H:i', strtotime($backups[0]['at'])) : '' }}</span>
                <i class="fas fa-clock text-[10px] text-white opacity-40"></i>
            </div>
        </div>
    </div>
    @endif

    @if($isMobile ?? false)
    <!-- Backup List Mobile (Cards) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h2 class="text-xs font-black text-app-main uppercase tracking-widest">Riwayat Lima Terakhir</h2>
            <span class="text-[9px] font-bold text-app-muted uppercase tracking-widest">Format: SQL</span>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($backups as $file)
            <div class="bg-app-surface p-5 rounded-[2.5rem] border border-app-main shadow-sm flex flex-col gap-4 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-app-bg border border-app-main flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm flex-shrink-0">
                            <i class="fas fa-file-code text-lg"></i>
                        </div>
                        <div class="min-w-0 pr-2">
                            <h4 class="text-xs font-black text-app-main tracking-tight break-all uppercase leading-snug">{{ $file['name'] }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-md text-[9px] font-black uppercase">{{ $file['size'] }}</span>
                                <span class="text-[9px] font-bold text-app-muted uppercase tracking-tighter">{{ date('d/m/Y H:i', strtotime($file['at'])) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-app-main/10">
                    <a href="{{ route('backups.download', $file['name']) }}" class="flex items-center justify-center gap-2 py-3 bg-slate-100 dark:bg-white/5 text-app-main rounded-2xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all">
                        <i class="fas fa-download text-[8px] text-indigo-600"></i>
                        Unduh
                    </a>
                    <form action="{{ route('backups.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Hapus file cadangan ini?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-rose-50 dark:bg-rose-500/10 text-rose-600 rounded-2xl text-[9px] font-black uppercase tracking-widest active:scale-95 transition-all border border-rose-100 dark:border-rose-500/20">
                            <i class="fas fa-trash-alt text-[8px]"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-app-surface p-10 rounded-[2.5rem] border border-app-main shadow-sm text-center">
                <div class="w-16 h-16 bg-app-bg border border-app-main rounded-full flex items-center justify-center mx-auto mb-4 text-app-muted opacity-30">
                    <i class="fas fa-database text-2xl"></i>
                </div>
                <p class="text-[10px] font-black text-app-muted uppercase tracking-widest">Belum ada file cadangan</p>
            </div>
            @endforelse
        </div>
    </div>
    @else
    <!-- Backup List Desktop (Table) -->
    <div class="bg-app-surface rounded-[32px] border border-app-main shadow-sm overflow-hidden transition-colors">
        <div class="px-8 py-6 border-b border-app-main flex items-center justify-between bg-app-bg/50">
            <h2 class="font-black text-app-main uppercase tracking-widest text-xs">Riwayat Cadangan</h2>
            <span class="px-3 py-1 bg-app-surface border border-app-main rounded-full text-[10px] font-bold text-app-muted">Limit: 10 File Terakhir</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-app-muted uppercase tracking-widest">
                        <th class="px-8 py-4">Nama File</th>
                        <th class="px-8 py-4 text-center">Ukuran</th>
                        <th class="px-8 py-4">Tanggal Dibuat</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-app-main">
                    @forelse($backups as $file)
                    <tr class="hover:bg-app-bg/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-app-bg flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:bg-app-surface group-hover:shadow-sm transition-all border border-transparent group-hover:border-app-main">
                                    <i class="fas fa-file-code"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-app-main tracking-tight uppercase leading-tight">{{ $file['name'] }}</p>
                                    <p class="text-[10px] font-bold text-app-muted uppercase tracking-widest">Format: SQL</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-full text-[11px] font-black">
                                {{ $file['size'] }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-app-muted">
                            {{ $file['at'] }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('backups.download', $file['name']) }}" class="btn-icon-mini text-app-muted hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-app-surface hover:shadow-sm transition-all" title="Unduh">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('backups.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file cadangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-mini text-app-muted hover:text-red-600 hover:bg-app-surface hover:shadow-sm transition-all" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-app-bg rounded-full flex items-center justify-center mx-auto mb-4 text-app-muted opacity-30">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <p class="text-sm font-bold text-app-muted uppercase tracking-widest">Belum ada file cadangan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Information Alert -->
    <div class="p-6 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/20 rounded-[32px] flex items-start gap-4 transition-colors">
        <div class="w-10 h-10 bg-app-surface rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm flex-shrink-0 transition-colors">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <h4 class="text-[10px] md:text-xs font-black text-blue-900 dark:text-blue-300 uppercase tracking-widest uppercase tracking-widest">Informasi Penting</h4>
            <p class="text-[10px] md:text-xs font-bold text-blue-800/70 dark:text-blue-400/70 mt-1 leading-relaxed leading-relaxed">
                Database adalah data paling krusial. Selalu lakukan backup sebelum melakukan update sistem besar atau penghapusan data masal. File backup disimpan secara lokal di server. Pastikan Anda juga mengunduh file backup secara berkala untuk disimpan di penyimpanan cadangan eksternal Anda.
            </p>
        </div>
    </div>
</div>
@endsection
