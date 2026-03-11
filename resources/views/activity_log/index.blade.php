@extends('layouts.admin')

@section('header', 'Activity Log')
@section('subheader', 'Audit Trail & Rekaman Aktivitas Sistem')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 p-6">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-history text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800">Log Aktivitas Sistem</h3>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Audit Trail & Riwayat Perubahan</p>
            </div>
        </div>

        <div class="w-full lg:max-w-md">
            <form action="{{ route('activity_log.index') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            onsearch="this.form.requestSubmit()"
                            placeholder="Cari logs..."
                            class="w-full py-2.5 px-3 text-sm outline-none bg-transparent font-medium placeholder:text-slate-400 text-slate-700">
                    </div>
                    <button type="button" x-show="query" x-cloak
                        @click="query = ''; $nextTick(() => $el.closest('form').requestSubmit())"
                        class="px-2 text-slate-300 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    <button type="submit" class="bg-indigo-600 h-full px-6 text-white text-sm font-bold hover:bg-indigo-700 transition-colors flex items-center whitespace-nowrap">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto border-t border-slate-100">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/50 text-[10px] uppercase font-bold text-indigo-600 tracking-widest border-b border-indigo-100 transition-all">
                <tr>
                    <th class="px-6 py-4">Waktu Kejadian</th>
                    <th class="px-6 py-4">Nama Pengguna</th>
                    <th class="px-6 py-4">Tipe Aksi</th>
                    <th class="px-6 py-4 w-1/2">Detail Perubahan / Audit Trail</th>
                    <th class="px-6 py-4">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="transition-all duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                        {{ $log->created_at->format('d M Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        {{ $log->user->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $log->action === 'CREATED' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $log->action === 'UPDATED' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $log->action === 'DELETED' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-normal">
                        <div class="text-slate-800 font-bold text-xs mb-2 leading-tight uppercase tracking-tight">{{ $log->description }}</div>
                        @if($log->formatted_properties)
                            <div class="space-y-1.5 bg-slate-50/50 p-2 rounded-lg border border-slate-100">
                                @foreach($log->formatted_properties as $change)
                                    <div class="flex flex-wrap items-center gap-x-2 text-[10px]">
                                        <span class="text-slate-400 font-bold uppercase tracking-tighter w-20">{{ $change['label'] }}:</span>
                                        <span class="text-rose-400 line-through">{{ $change['old'] }}</span>
                                        <i class="fas fa-arrow-right text-[8px] text-slate-300"></i>
                                        <span class="text-emerald-600 font-bold bg-emerald-50 px-1 rounded">{{ $change['new'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-xs">
                        {{ $log->ip_address }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-history text-4xl mb-4 block"></i>
                        Belum ada aktivitas tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
