@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')
@section('content')
<div x-data="{ showFilters: {{ (request('search') || request('date')) ? 'true' : 'false' }} }" class="space-y-6">
  {{-- Page Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 transition-colors uppercase tracking-tight">Mutasi Stok</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Transaksi Masuk & Keluar</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('stock.sync') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-emerald-500 transition-all hover:bg-emerald-50 hover:border-emerald-100" title="Sinkronisasi dari Kwitansi/BASTB">
        <i class="fas fa-sync-alt text-xs"></i>
      </a>
      <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50 ' : ''">
        <i class="fas fa-filter text-xs"></i>
      </button>
      <a href="{{ route('stock.create') }}" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
        <i class="fas fa-plus text-xs"></i>
      </a>
    </div>
  </div>

  {{-- Filter Card --}}
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Cari Transaksi</h3>
      <form action="{{ route('stock.index') }}" method="GET" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Barang atau No. Surat</label>
          <div class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Filter Tanggal</label>
          <div class="relative">
            <i class="fas fa-calendar-day absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100 ">
            Terapkan
          </button>
          <a href="{{ route('stock.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- Summary Card --}}
  <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Transaksi</p>
        <i class="fas fa-exchange-alt opacity-20"></i>
      </div>
      <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $transactions->count() }} Record</h2>
      <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest">
        @if($datePaginator->count() > 0)
          Pada {{ \Carbon\Carbon::parse($datePaginator->items()[0]->date)->translatedFormat('d M Y') }}
        @else
          Data Kosong
        @endif
      </p>
      <div class="mt-5 grid grid-cols-2 gap-3">
        <a href="{{ route('reports.index') }}" class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/15 text-white shadow-sm active:scale-[0.98] transition flex items-center gap-3">
          <div class="w-9 h-9 rounded-2xl bg-white/10 flex items-center justify-center">
            <i class="fas fa-chart-pie text-sm"></i>
          </div>
          <div class="min-w-0">
            <div class="text-[10px] font-black uppercase tracking-widest leading-tight">Laporan</div>
            <div class="text-[9px] font-bold opacity-80 uppercase tracking-widest mt-0.5 truncate">Persediaan</div>
          </div>
        </a>
        <a href="{{ route('reports.kartu.tahunan') }}" class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/15 text-white shadow-sm active:scale-[0.98] transition flex items-center gap-3">
          <div class="w-9 h-9 rounded-2xl bg-white/10 flex items-center justify-center">
            <i class="fas fa-table-list text-sm"></i>
          </div>
          <div class="min-w-0">
            <div class="text-[10px] font-black uppercase tracking-widest leading-tight">Kartu</div>
            <div class="text-[9px] font-bold opacity-80 uppercase tracking-widest mt-0.5 truncate">Persediaan</div>
          </div>
        </a>
      </div>
    </div>
  </div>

  {{-- Transaction List --}}
  <div class="space-y-6 pb-24">
    <div class="flex items-center justify-between px-2">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Riwayat Transaksi</h3>
      <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $transactions->count() }} Record Hari Ini</span>
    </div>

    @php 
      $runningStock = []; 
      $grouped = $groupedTransactions ?? collect($transactions->items())->groupBy(function($item) {
        return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
      });
    @endphp

    @forelse($grouped as $date => $txs)
      <div class="space-y-4">
        <div class="flex items-center gap-3 px-4">
          <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100 uppercase tracking-widest transition-colors">
            {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
          </span>
          <div class="h-[1px] flex-1 bg-gradient-to-r from-indigo-100 to-transparent"></div>
        </div>

        @foreach($txs as $transaction)
          @php
            $productName = $transaction->product?->name ?? '(Produk dihapus)';
            $productUnit = $transaction->product?->unit ?? 'Unit';
            $saldoAkhir = $transaction->running_balance ?? 0;
          @endphp
          <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 relative overflow-hidden group">
            {{-- Decoration --}}
            <div class="absolute -right-6 -top-6 w-16 h-16 {{ $transaction->type === 'in' ? 'bg-emerald-500/5' : 'bg-rose-500/5' }} rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>

            <div class="flex items-start gap-4 relative z-10">
              {{-- Type Icon --}}
              <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $transaction->type === 'in' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} shadow-sm">
                <i class="fas {{ $transaction->type === 'in' ? 'fa-arrow-down-long' : 'fa-arrow-up-long' }} text-sm"></i>
              </div>

              {{-- Transaction Info --}}
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight leading-tight group-hover:text-indigo-600 transition-colors">{{ $productName }}</h3>
                    <div class="flex items-center flex-wrap gap-2 mt-2">
                      <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {{ $transaction->type === 'in' ? 'bg-emerald-100 text-emerald-700 ' : 'bg-rose-100 text-rose-700 ' }} uppercase tracking-widest transition-colors">
                        {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                      </span>
                      @if($transaction->nosur)
                        <a href="{{ route('reports.penerimaan.list', ['search' => $transaction->nosur]) }}" class="text-[9px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-widest transition-all hover:bg-indigo-100 flex items-center gap-1.5 border border-indigo-100/50">
                          <i class="fas fa-link text-[7px] opacity-40"></i>
                          #{{ $transaction->nosur }}
                        </a>
                      @endif
                    </div>
                  </div>
                  <div class="text-right flex-shrink-0 ml-auto pt-0.5">
                    <span class="text-lg font-black {{ $transaction->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }} block leading-none">
                      {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }}
                    </span>
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.1em] mt-1">{{ $productUnit }}</p>
                  </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    @if($transaction->notes)
                      <div class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 flex items-center gap-2 transition-colors">
                        <i class="far fa-sticky-note text-[8px] opacity-40"></i>
                        <span class="text-[8px] font-black tracking-widest">{{ $transaction->notes }}</span>
                      </div>
                    @endif
                    <div class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 flex items-center gap-2 transition-colors">
                      <i class="fas fa-box text-[8px] opacity-40"></i>
                      <span class="text-[8px] font-black tracking-widest uppercase">{{ $saldoAkhir ?? '-' }} Stok</span>
                    </div>
                  </div>

                  {{-- Quick Actions --}}
                  @php $isAutomatic = $transaction->notes === 'Otomatis dari Kwitansi'; @endphp
                  <div class="flex items-center gap-1.5">
                    <a href="{{ route('stock.edit', $transaction->id) }}" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors" title="{{ $isAutomatic ? 'Lihat Detail' : 'Edit' }}">
                      <i class="{{ $isAutomatic ? 'fas fa-eye' : 'far fa-edit' }} text-[10px]"></i>
                    </a>
                    
                    @if(!$isAutomatic)
                      <form action="{{ route('stock.destroy', $transaction->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" @click.prevent="if(confirm('Hapus transaksi ini?')) $el.form.submit()" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors" title="Hapus">
                          <i class="fas fa-trash text-[10px]"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @empty
      <div class="bg-white rounded-[2.5rem] p-16 border border-slate-50 shadow-sm text-center relative overflow-hidden transition-colors">
        <div class="absolute inset-0 bg-slate-50/50 -skew-y-12 translate-y-20"></div>
        <div class="relative z-10">
          <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200 ">
            <i class="fas fa-exchange-alt text-3xl"></i>
          </div>
          <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Tidak Ada Transaksi</h3>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Mulai dengan menambah mutasi baru</p>
        </div>
      </div>
    @endforelse

    <div class="pt-4 pb-12">
      {{ $datePaginator->links() }}
    </div>
  </div>
</div>
@endsection
