@extends('layouts.admin')

@section('header', 'Agenda Surat')
@section('subheader', 'Daftar seluruh surat & dokumen yang telah dibuat')

@section('content')
<div x-data="{
  showFilters: {{ (request('search') || request('type')) ? 'true' : 'false' }},
}" class="space-y-6">

  {{-- ===== HEADER ACTIONS ===== --}}
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Register Dokumen</h2>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Seluruh surat terurut berdasarkan tanggal & nomor</p>
    </div>
    <button @click="showFilters = !showFilters"
            class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-400 transition-all"
            :class="showFilters ? 'text-indigo-600 border-indigo-200 ring-4 ring-indigo-50' : ''">
      <i class="fas fa-sliders-h text-xs"></i>
    </button>
  </div>

  {{-- ===== FILTER PANEL ===== --}}
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Filter & Pencarian</h3>
      <form action="{{ route('agenda.surat.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Search --}}
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor / Uraian</label>
            <div class="relative">
              <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
              <input type="text" name="search" value="{{ $search }}"
                     placeholder="Cari nomor atau uraian surat..."
                     class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none dark:text-white dark:placeholder-slate-500">
            </div>
          </div>
          {{-- Type filter --}}
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Jenis Surat</label>
            <select name="type"
                    class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none dark:text-white">
              <option value="">Semua Jenis</option>
              @foreach($sources as $key => $src)
                <option value="{{ $key }}" {{ $filter === $key ? 'selected' : '' }}>{{ $src['label'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit"
                  class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">
            Terapkan
          </button>
          <a href="{{ route('agenda.surat.index') }}"
             class="w-full py-4 bg-slate-50 dark:bg-slate-700 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- ===== STAT CARDS ===== --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    {{-- Total Dokumen --}}
    <div class="col-span-2 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-200 overflow-hidden relative group">
      <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-60">Total Dokumen</p>
          <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
            <i class="fas fa-book-open text-xs opacity-70"></i>
          </div>
        </div>
        <h2 class="text-4xl font-black tracking-tight">{{ number_format($totalDokumen) }}</h2>
        <p class="text-[9px] font-bold mt-2 opacity-70 uppercase tracking-widest">Surat Tercatat</p>
      </div>
    </div>

    {{-- Total Nominal --}}
    <div class="col-span-2 bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden relative group">
      <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Nominal</p>
          <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
            <i class="fas fa-coins text-xs text-emerald-500"></i>
          </div>
        </div>
        <h2 class="text-2xl font-black tracking-tight text-slate-800 dark:text-white">
          Rp{{ number_format($totalNominal, 0, ',', '.') }}
        </h2>
        <p class="text-[9px] font-bold mt-2 text-slate-400 uppercase tracking-widest">Dari semua dokumen</p>
      </div>
    </div>

    {{-- Per-jenis cards --}}
    @php
      $typeColors = [
        'nota'        => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'icon_bg' => 'bg-blue-100'],
        'pemeriksaan' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'icon_bg' => 'bg-amber-100'],
        'penerimaan'  => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'icon_bg' => 'bg-indigo-100'],
        'kwitansi'    => ['bg' => 'bg-emerald-50','text' => 'text-emerald-600','icon_bg' => 'bg-emerald-100'],
      ];
    @endphp
    @foreach($sources as $key => $src)
      @php
        $c = $typeColors[$key];
        $count = $items->getCollection()->where('type', $key)->count();
      @endphp
      <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="w-10 h-10 rounded-xl {{ $c['icon_bg'] }} flex items-center justify-center mb-3">
          <i class="fas {{ $src['icon'] }} text-sm {{ $c['text'] }}"></i>
        </div>
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $src['label'] }}</p>
        <p class="text-2xl font-black {{ $c['text'] }} mt-1">{{ number_format($items->total() > 0 ? $count : 0) }}</p>
      </div>
    @endforeach
  </div>

  {{-- ===== TABLE ===== --}}
  <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
    {{-- Table Header --}}
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-50 dark:border-slate-700">
      <div>
        <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Daftar Agenda</h3>
        <p class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">
          Menampilkan {{ $items->count() }} dari {{ $items->total() }} surat
          @if($filter && isset($sources[$filter]))
            — <span class="text-indigo-500">{{ $sources[$filter]['label'] }}</span>
          @endif
        </p>
      </div>
      @if($search || $filter)
        <a href="{{ route('agenda.surat.index') }}"
           class="text-[9px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">
          <i class="fas fa-times mr-1"></i>Hapus Filter
        </a>
      @endif
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-50 dark:border-slate-700">
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">No</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Jenis</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nomor Surat</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Uraian</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
            <th class="px-6 py-3 text-right text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nominal</th>
            <th class="px-6 py-3 text-center text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
          @php $noUrut = ($items->currentPage() - 1) * $items->perPage() + 1; @endphp
          @forelse($items as $row)
            @php $c = $typeColors[$row['type']] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'icon_bg' => 'bg-slate-100']; @endphp
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
              {{-- No --}}
              <td class="px-6 py-4">
                <span class="text-[10px] font-black text-slate-300">{{ $noUrut++ }}</span>
              </td>
              {{-- Jenis badge --}}
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $c['icon_bg'] }} {{ $c['text'] }} text-[9px] font-black uppercase tracking-wide whitespace-nowrap">
                  <i class="fas {{ $row['icon'] }} text-[9px]"></i>
                  {{ $row['label'] }}
                </span>
              </td>
              {{-- Nomor --}}
              <td class="px-6 py-4">
                <a href="{{ $row['route_show'] }}"
                   class="text-[11px] font-black text-slate-800 dark:text-white hover:text-indigo-600 transition-colors block leading-tight">
                  {{ $row['nomor'] }}
                </a>
              </td>
              {{-- Uraian --}}
              <td class="px-6 py-4 max-w-xs">
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 leading-relaxed truncate" title="{{ $row['uraian'] }}">
                  {{ $row['uraian'] ?: '-' }}
                </p>
              </td>
              {{-- Tanggal --}}
              <td class="px-6 py-4 whitespace-nowrap">
                @if($row['tanggal'])
                  <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">
                    {{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}
                  </p>
                  <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">
                    {{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('l') }}
                  </p>
                @else
                  <span class="text-slate-300 text-[10px]">-</span>
                @endif
              </td>
              {{-- Nominal --}}
              <td class="px-6 py-4 text-right whitespace-nowrap">
                @if($row['total'] > 0)
                  <span class="text-[11px] font-black {{ $c['text'] }}">
                    Rp{{ number_format($row['total'], 0, ',', '.') }}
                  </span>
                @else
                  <span class="text-[10px] text-slate-300">-</span>
                @endif
              </td>
              {{-- Aksi --}}
              <td class="px-6 py-4 text-center">
                <a href="{{ $row['route_show'] }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/30 transition-all text-[9px] font-black uppercase tracking-widest">
                  <i class="fas fa-eye text-[9px]"></i> Lihat
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-24 text-center">
                <div class="flex flex-col items-center gap-4">
                  <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                    <i class="fas fa-book-open text-3xl text-slate-200 dark:text-slate-600"></i>
                  </div>
                  <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Belum Ada Surat</h3>
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">
                      Buat dokumen (Nota Pesanan, BAP, Kwitansi) agar muncul di sini
                    </p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile Card List --}}
    <div class="md:hidden divide-y divide-slate-50 dark:divide-slate-700">
      @php $noUrut = ($items->currentPage() - 1) * $items->perPage() + 1; @endphp
      @forelse($items as $row)
        @php $c = $typeColors[$row['type']] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'icon_bg' => 'bg-slate-100']; @endphp
        <div class="p-5 hover:bg-slate-50/50 transition-colors">
          <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-2xl {{ $c['icon_bg'] }} flex items-center justify-center flex-shrink-0">
              <i class="fas {{ $row['icon'] }} {{ $c['text'] }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <span class="inline-block px-2 py-0.5 rounded-lg {{ $c['icon_bg'] }} {{ $c['text'] }} text-[8px] font-black uppercase tracking-wide mb-1">
                    {{ $row['label'] }}
                  </span>
                  <a href="{{ $row['route_show'] }}"
                     class="block text-[11px] font-black text-slate-800 dark:text-white leading-tight break-all hover:text-indigo-600 transition-colors">
                    {{ $row['nomor'] }}
                  </a>
                  <p class="text-[9px] text-slate-400 mt-0.5 leading-relaxed">{{ Str::limit($row['uraian'], 60) ?: '-' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                  @if($row['total'] > 0)
                    <p class="text-[10px] font-black {{ $c['text'] }}">Rp{{ number_format($row['total'], 0, ',', '.') }}</p>
                  @endif
                  @if($row['tanggal'])
                    <p class="text-[8px] font-bold text-slate-300 mt-0.5">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d/m/Y') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="p-16 text-center">
          <i class="fas fa-book-open text-3xl text-slate-200 mb-4 block"></i>
          <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada surat tercatat</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
      <div class="px-6 py-5 border-t border-slate-50 dark:border-slate-700">
        {{ $items->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
