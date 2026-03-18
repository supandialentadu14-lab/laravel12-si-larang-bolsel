@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pusat Cadangan Data</h1>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1">Database & Pemulihan Sistem</p>
        </div>
        <form action="{{ route('backups.create') }}" method="POST">
            @csrf
            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-indigo-200 transition-all active:scale-95 group">
                <i class="fas fa-database transition-transform group-hover:scale-110"></i>
                <span>Buat Cadangan Baru</span>
            </button>
        </form>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total File</p>
                <h3 class="text-3xl font-black text-gray-900 mt-1">{{ count($backups) }}</h3>
                <p class="text-xs font-bold text-gray-500 mt-1 italic">File SQL tersimpan</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.06] transition-opacity">
                <i class="fas fa-folder-open text-9xl"></i>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terakhir Dibuat</p>
                <h3 class="text-xl font-black text-gray-900 mt-1">{{ count($backups) > 0 ? $backups[0]['at'] : 'Belum ada' }}</h3>
                <p class="text-xs font-bold text-indigo-600 mt-1 uppercase tracking-widest font-mono">Timestamp System</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.06] transition-opacity">
                <i class="fas fa-clock text-9xl"></i>
            </div>
        </div>

        <div class="bg-indigo-600 p-6 rounded-[32px] shadow-lg shadow-indigo-100 relative overflow-hidden group">
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

    <!-- Backup List Table -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h2 class="font-black text-gray-900 uppercase tracking-widest text-xs">Riwayat Cadangan</h2>
            <span class="px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-bold text-gray-400">Limit: 10 File Terakhir</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Nama File</th>
                        <th class="px-8 py-4 text-center">Ukuran</th>
                        <th class="px-8 py-4">Tanggal Dibuat</th>
                        <th class="px-8 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($backups as $file)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-indigo-600 group-hover:bg-white group-hover:shadow-sm transition-all border border-transparent group-hover:border-gray-100">
                                    <i class="fas fa-file-code"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 tracking-tight">{{ $file['name'] }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Format: SQL</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[11px] font-black">
                                {{ $file['size'] }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-gray-500">
                            {{ $file['at'] }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('backups.download', $file['name']) }}" class="btn-icon-mini text-gray-400 hover:text-indigo-600 hover:bg-white hover:shadow-sm transition-all" title="Unduh">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('backups.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file cadangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon-mini text-gray-400 hover:text-red-600 hover:bg-white hover:shadow-sm transition-all" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Belum ada file cadangan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-[32px] flex items-start gap-4">
        <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <h4 class="text-xs font-black text-blue-900 uppercase tracking-widest">Informasi Penting</h4>
            <p class="text-xs font-bold text-blue-800/70 mt-1 leading-relaxed">
                Database adalah data paling krusial. Selalu lakukan backup sebelum melakukan update sistem besar atau penghapusan data masal. File backup disimpan secara lokal di server. Pastikan Anda juga mengunduh file backup secara berkala untuk disimpan di penyimpanan cadangan eksternal Anda.
            </p>
        </div>
    </div>
</div>
@endsection
