@extends('layouts.admin')

@section('header', 'Activity Log')
@section('subheader', 'Audit Trail & Rekaman Aktivitas Sistem')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-700">Audit Trail</h3>
        <div class="relative w-72" x-data="{ search: '{{ request('search') }}' }">
            <form method="GET" action="{{ route('activity_log.index') }}" x-ref="searchForm">
                <input 
                    type="text" 
                    name="search" 
                    x-model="search"
                    x-init="$el.focus(); $el.setSelectionRange($el.value.length, $el.value.length)"
                    @input.debounce.750ms="$refs.searchForm.requestSubmit()"
                    placeholder="Cari log aktivitas..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-300 focus:outline-none transition-all text-sm bg-white"
                >
                <div class="absolute left-3 top-2.5 text-gray-400" x-show="!search">
                    <i class="fas fa-search"></i>
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Aksi</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/50 transition-colors">
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
                    <td class="px-6 py-4">
                        <div class="text-gray-800 font-medium mb-1">{{ $log->description }}</div>
                        @if($log->formatted_properties)
                            <div class="space-y-1">
                                @foreach($log->formatted_properties as $change)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="text-gray-400 font-semibold w-24">{{ $change['label'] }}:</span>
                                        <span class="text-red-400 line-through">{{ $change['old'] }}</span>
                                        <i class="fas fa-arrow-right text-[10px] text-gray-300"></i>
                                        <span class="text-green-600 font-bold bg-green-50 px-1 rounded">{{ $change['new'] }}</span>
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
