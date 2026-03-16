@extends($isMobile ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div x-data="{
  selected: [],
  allSelected: false,
  showFilters: {{ request('search') ? 'true' : 'false' }},
  
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
}" class="space-y-6">

  {{-- Page Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pemeriksaan</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Berita Acara Pemeriksaan Barang</p>
    </div>
    <div class="flex gap-2">
      <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50' : ''">
        <i class="fas fa-filter text-xs"></i>
      </button>
      <a href="{{ route('reports.pemeriksaan.form') }}" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
        <i class="fas fa-plus text-xs"></i>
      </a>
    </div>
  </div>

  {{-- Filter Card --}}
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Cari Dokumen</h3>
      <form action="{{ route('reports.pemeriksaan.list') }}" method="GET" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor BAP atau Nota</label>
          <div class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Terapkan</button>
          <a href="{{ route('reports.pemeriksaan.list') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Reset</a>
        </div>
      </form>
    </div>
  </div>

  {{-- Summary Card --}}
  <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10">
      <div class="flex items-center justify-between">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Pemeriksaan</p>
        <i class="fas fa-check-double opacity-20"></i>
      </div>
      <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $items->total() }} Dokumen</h2>
      <p class="text-[9px] font-bold mt-2 opacity-80 uppercase tracking-widest">Update Terakhir: {{ $items->first() ? \Carbon\Carbon::parse($items->first()['tanggal'])->translatedFormat('d M Y') : '-' }}</p>
    </div>
  </div>

  {{-- List BAP --}}
  <div class="space-y-4 pb-24">
    <div class="flex items-center justify-between px-2">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Berita Acara</h3>
      <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $items->count() }} Data</span>
    </div>

    @forelse($items as $row)
      @php 
        $totalVal = (int)($row['total'] ?? 0); 
        $cardId = 'card-' . Str::slug($row['nomor']);
      @endphp
      <div id="{{ $cardId }}" class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 overflow-hidden target:ring-2 target:ring-indigo-500 target:ring-offset-2">
        <div class="flex items-start gap-4">
          {{-- Icon --}}
          <div class="w-14 h-14 rounded-[1.5rem] bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0">
            <i class="fas fa-clipboard-check text-lg"></i>
          </div>

          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
              <div class="pr-2">
                <a href="{{ route('reports.pemeriksaan.show', $row['id']) }}" class="block group/link">
                  <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-tight break-all leading-tight group-hover/link:text-indigo-600 transition-colors">{{ $row['nomor'] }}</h3>
                </a>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}</p>
              </div>
              <div class="text-right flex-shrink-0">
                <span class="text-xs font-black text-indigo-600 tracking-tight">Rp{{ number_format($totalVal, 0, ',', '.') }}</span>
                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Total</p>
              </div>
            </div>

            <div class="mt-4 space-y-2">
              <div class="flex items-center gap-2 text-slate-500">
                <i class="fas fa-link text-[9px] opacity-40"></i>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nota:</span>
                <a href="{{ route('reports.nota.list', ['search' => $row['nota_nomor']]) }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                  {{ $row['nota_nomor'] ?: '-' }}
                </a>
              </div>
              
              <div class="pt-2 border-t border-slate-50 space-y-2">
                <div class="flex items-center gap-2 min-w-0">
                  <i class="fas fa-map-marker-alt text-[9px] text-slate-300"></i>
                  <span class="text-[9px] font-bold text-slate-500 uppercase truncate max-w-[150px]">{{ $row['tempat'] ?? '-' }}</span>
                </div>
                
                {{-- Actions --}}
                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                  <a href="{{ route('reports.pemeriksaan.show', $row['id']) }}" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-eye text-[10px]"></i>
                  </a>
                  <a href="{{ route('reports.pemeriksaan.edit', $row['id']) }}" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-edit text-[10px]"></i>
                  </a>
                  <form action="{{ route('reports.pemeriksaan.delete', $row['id']) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" @click.prevent="if(confirm('Hapus dokumen ini?')) $el.form.submit()" class="w-8 h-8 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                      <i class="fas fa-trash text-[10px]"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-folder-open text-3xl text-slate-200"></i>
        </div>
        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tidak Ada Laporan</h3>
        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">Klik (+) untuk membuat laporan baru</p>
      </div>
    @endforelse

    <div class="pt-4">
      {{ $items->links() }}
    </div>
  </div>
</div>
@endsection
