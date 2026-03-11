@extends('layouts.admin')

@section('header', 'Daftar Berita Acara Stock Opname')
@section('content')
    <div x-data="{
        selected: [],
    allSelected: false,
    toggleAll() {
            this.allSelected = !this.allSelected;
            if (this.allSelected) {
                this.selected = [
                    @foreach ($items as $item)
                        '{{ $item['id'] }}',
                    @endforeach
                ];
            } else {
                this.selected = [];
            }
        },
        updateSelectAll() {
            this.allSelected = this.selected.length === {{ count($items) }};
        }
    }" class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <a href="{{ route('reports.opname.form') }}" class="inline-flex justify-center w-full lg:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-plus"></i> <span class="whitespace-nowrap">Buat BA Stock Opname</span>
            </a>
        </div>

        <div class="w-full lg:max-w-xl">
            <form action="{{ route('reports.opname.list') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            @input.debounce.750ms="$el.closest('form').requestSubmit()"
                            placeholder="Cari nomor dokumen opname..."
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

    <div class="overflow-x-auto border border-slate-200 rounded-2xl bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/50 text-[10px] uppercase font-bold text-indigo-600 tracking-widest border-b border-indigo-100 transition-all">
                <tr>
                    <th class="px-6 py-4 w-12 text-center text-slate-500">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 h-4 w-4 transition-all">
                    </th>
                    <th class="px-6 py-4">Nomor Berita Acara</th>
                    <th class="px-6 py-4">Tanggal Opname</th>
                    <th class="px-6 py-4">Lokasi / Tempat</th>
                    <th class="px-6 py-4">Pelaksana / Pihak Kedua</th>
                    <th class="px-6 py-4 text-center">Update</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
                <tbody>
                @forelse($items as $item)
                    <tr class="border-t hover:bg-gray-50 transition" :class="{ 'bg-indigo-50': selected.includes('{{ $item['id'] }}') }">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" value="{{ $item['id'] }}" x-model="selected" @click="updateSelectAll()" class="rounded-md border-slate-300 text-indigo-600 h-4 w-4 focus:ring-indigo-500 transition-all">
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $item['nomor'] }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Illuminate\Support\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item['tempat'] }}</td>
                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $item['pihak_kedua'] }}</td>
                        <td class="px-6 py-4 text-gray-500 text-[11px] font-medium tracking-tight">
                            <div class="flex flex-col">
                                <span>{{ \Carbon\Carbon::createFromTimestamp($item['updated'])->translatedFormat('d F Y') }}</span>
                                <span class="text-[10px] opacity-60 uppercase">{{ \Carbon\Carbon::createFromTimestamp($item['updated'])->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @include('partials.action_buttons', [
                                'show' => route('reports.opname.show', $item['id']),
                                'edit' => route('reports.opname.edit', $item['id']),
                                'delete' => route('reports.opname.delete', $item['id']),
                            ])
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-gray-500">Belum ada BA Stock Opname disimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('reports.opname.bulk_delete') }}" class="inline-block">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button" @click="if(confirm('Hapus ' + selected.length + ' item terpilih?')) $el.closest('form').submit()" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-800 rounded-lg text-slate-800 font-bold text-[10px] hover:bg-slate-50 transition-all shadow-sm group">
                <i class="fas fa-trash text-slate-800 group-hover:text-rose-600 transition-colors"></i>
                <span>HAPUS <span x-text="selected.length"></span> ITEM TERPILIH</span>
            </button>
        </form>
        <div class="flex-1">
            {{ $items->links() }}
        </div>
    </div>
    </div>
@endsection

