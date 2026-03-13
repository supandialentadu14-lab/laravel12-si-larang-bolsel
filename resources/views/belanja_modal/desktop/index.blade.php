@extends('layouts.admin')

@section('header', 'Daftar Kontrak Belanja Modal')
@section('content')

<script>
    function belanjaModalIndex() {
        return {
            selected: [],
            allSelected: false,
            showModal: false,
            isEdit: false,
            editId: '',
            tahun: '{{ now()->year }}',
            opd: '{{ addslashes($master["opd"]["nama"] ?? ($opd->nama_opd ?? "")) }}',
            items: [],

            toggleAll() {
                this.allSelected = !this.allSelected;
                if (this.allSelected) {
                    this.selected = [
                        @foreach ($items as $row)
                            '{{ $row['id'] }}',
                        @endforeach
                    ];
                } else {
                    this.selected = [];
                }
            },
            updateSelectAll() {
                this.allSelected = this.selected.length === {{ count($items) }};
            },

            initModal(editMode, id = '', tahun = '', rawItems = null) {
                this.isEdit = editMode;
                this.editId = id;
                this.tahun = tahun || '{{ now()->year }}';

                if (rawItems && (Array.isArray(rawItems) ? rawItems.length > 0 : Object.keys(rawItems).length > 0)) {
                    let itemsArray = Array.isArray(rawItems) ? rawItems : Object.values(rawItems);
                    this.items = itemsArray.map(it => ({
                        nama_kegiatan: it.nm || it.nama_kegiatan || '',
                        pekerjaan: it.pk || it.pekerjaan || '',
                        nilai_kontrak: it.nk || it.nilai_kontrak || 0,
                        tanggal_mulai: it.tm || it.tanggal_mulai || '',
                        tanggal_akhir: it.ta || it.tanggal_akhir || '',
                        uang_muka: it.um || it.uang_muka || 0,
                        termin1: it.t1 || it.termin1 || 0,
                        termin2: it.t2 || it.termin2 || 0,
                        termin3: it.t3 || it.termin3 || 0,
                        termin4: it.t4 || it.termin4 || 0,
                        total: it.ttl || it.total || 0,
                        status: it.st || it.status || ''
                    }));
                } else {
                    this.items = [];
                    this.addItem();
                }

                this.showModal = true;
            },
            addItem() {
                this.items.push({
                    nama_kegiatan: '',
                    pekerjaan: '',
                    nilai_kontrak: 0,
                    tanggal_mulai: '',
                    tanggal_akhir: '',
                    uang_muka: 0,
                    termin1: 0,
                    termin2: 0,
                    termin3: 0,
                    termin4: 0,
                    total: 0,
                    status: ''
                });
            },
            removeItem(i) { this.items.splice(i, 1); },
            recalc(i) {
                const it = this.items[i] || {};
                const toInt = v => parseInt(v || 0, 10);
                it.total = toInt(it.uang_muka) + toInt(it.termin1) + toInt(it.termin2) + toInt(it.termin3) + toInt(it.termin4);
                this.items[i] = it;
            }
        }
    }
</script>

<div x-data="belanjaModalIndex()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Belanja Modal</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Laporan Kontrak & Pekerjaan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.belanja.modal.preview_all') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all hover:text-indigo-600">
                <i class="fas fa-print text-xs"></i>
            </a>
            <button type="button" @click="initModal(false)" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-plus text-xs"></i>
            </button>
        </div>
    </div>

    <div class="bg-slate-800 rounded-[2.5rem] p-6 text-white shadow-xl shadow-slate-100 overflow-hidden relative group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Anggaran Berjalan</p>
            <h2 class="text-3xl font-black mt-2 tracking-tight">Rp{{ number_format($items->sum('nilai_total'), 0, ',', '.') }}</h2>

            <div class="flex items-center gap-4 mt-6">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Laporan</span>
                    <span class="text-sm font-black">{{ $items->total() }} File</span>
                </div>
                <div class="w-px h-8 bg-white/10"></div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Pekerjaan</span>
                    <span class="text-sm font-black text-indigo-400">{{ $items->sum('kontrak_count') }} Item</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm">
        <form action="{{ route('reports.belanja.modal.list') }}" method="GET" class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan belanja modal..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
        </form>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Kontrak Modal</h3>
            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{{ $items->count() }} File</span>
        </div>

        @forelse($items as $row)
        <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-[1.5rem] bg-slate-50 text-slate-400 flex flex-col items-center justify-center shadow-inner flex-shrink-0">
                    <span class="text-[8px] font-black uppercase tracking-tighter opacity-60">TAHUN</span>
                    <span class="text-sm font-black text-slate-800 leading-none">{{ $row['tahun'] }}</span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate leading-tight">Laporan Modal {{ $row['tahun'] }}</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Diperbarui: {{ \Carbon\Carbon::createFromTimestamp($row['updated'])->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-black text-slate-900">Rp{{ number_format($row['nilai_total'], 0, ',', '.') }}</span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">{{ $row['kontrak_count'] }} PEKERJAAN</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reports.belanja.modal.show', $row['id']) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-eye"></i> DETAIL
                            </a>
                            <a href="{{ route('reports.belanja.modal.export_excel', $row['id']) }}" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-file-excel"></i> EXCEL
                            </a>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="initModal(true, '{{ $row['id'] }}', '{{ $row['tahun'] }}', {{ json_encode($row['raw_items']) }})" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fas fa-edit text-[10px]"></i>
                            </button>
                            <form action="{{ route('reports.belanja.modal.delete', $row['id']) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" @click.prevent="if(confirm('Hapus laporan ini?')) $el.form.submit()" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                    <i class="fas fa-trash text-[10px]"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-invoice-dollar text-3xl text-slate-200"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tidak Ada Laporan</h3>
            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">Klik (+) untuk membuat laporan baru</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $items->links() }}
    </div>

    @include('belanja_modal.partials.modals_desktop')
</div>

@endsection

