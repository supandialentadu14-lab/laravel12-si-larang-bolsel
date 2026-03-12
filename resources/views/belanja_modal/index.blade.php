@extends('layouts.admin')

@section('header', 'Daftar Kontrak Belanja Modal')
@section('content')

<script>
    function belanjaModalIndex() {
        return {
            selected: [],
            allSelected: false,
            
            // Modal state
            showModal: false,
            isEdit: false,
            
            // Form state
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
                
                if (rawItems && rawItems.length > 0) {
                    this.items = JSON.parse(JSON.stringify(rawItems)).map(it => ({
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
                if (!this.isEdit) {
                    this.focusRow(0);
                }
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
            focusRow(i) {
                this.$nextTick(() => {
                    const el = this.$refs[`row_${i}_kegiatan`];
                    if (el) el.focus();
                });
            },
            recalc(i) {
                const it = this.items[i] || {};
                const toInt = v => parseInt(v || 0, 10);
                it.total = toInt(it.uang_muka) + toInt(it.termin1) + toInt(it.termin2) + toInt(it.termin3) + toInt(it.termin4);
                this.items[i] = it;
            }
        }
    }
</script>

<div x-data="belanjaModalIndex()" class="bg-white dark:bg-slate-800 rounded-lg shadow border border-transparent dark:border-slate-700/50 p-6 mb-6 transition-all duration-300">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 sm:gap-6 mb-8">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <button @click="initModal(false)" class="inline-flex justify-center w-full sm:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-plus"></i> <span class="whitespace-nowrap">Tambah Kontrak Modal</span>
            </button>
            <a href="{{ route('reports.belanja.modal.preview_all') }}" class="inline-flex justify-center w-full sm:w-auto items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-slate-100 transition-all duration-200">
                <i class="fas fa-print"></i> <span class="whitespace-nowrap">Cetak Laporan</span>
            </a>
        </div>

        <div class="w-full lg:max-w-xl">
            <form action="{{ route('reports.belanja.modal.list') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 dark:border-slate-800 flex items-center justify-center text-slate-400 bg-slate-50/50 dark:bg-slate-800/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            @input.debounce.750ms="$el.closest('form').requestSubmit()"
                            placeholder="Cari data belanja modal..."
                            class="w-full py-2.5 px-3 text-sm outline-none bg-transparent font-medium placeholder:text-slate-400 text-slate-700 dark:text-slate-200">
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

    {{-- Modal Form Spreadsheet --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-10 px-4 pb-10 text-center">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity" style="background-color: rgba(15, 23, 42, 0.5);" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative inline-block bg-white dark:bg-slate-800 rounded-xl text-left overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 dark:border-slate-700 transform transition-all w-full max-w-6xl sm:my-8 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                {{-- Modal Header --}}
                <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center text-white">
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <i class="fas" :class="isEdit ? 'fa-edit' : 'fa-plus'"></i> 
                        <span x-text="isEdit ? 'Edit Kontrak Belanja Modal' : 'Tambah Kontrak Belanja Modal'"></span>
                    </h3>
                    <button type="button" @click="showModal = false" class="text-white hover:text-gray-300 font-bold focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('reports.belanja.modal.save') }}" class="p-6 pt-5 min-h-[500px]">
                    @csrf
                    <input type="hidden" name="id" x-model="editId">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                            <input type="number" min="2000" max="2100" name="tahun" x-model="tahun" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">OPD</label>
                            <input type="text" name="opd" x-model="opd" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" disabled>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="addItem()" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-bold shadow hover:bg-indigo-700 transition w-full sm:w-auto">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-lg bg-white mb-6">
                        <table class="w-full text-sm text-left text-gray-700 whitespace-nowrap">
                            <thead class="bg-gray-100 text-xs uppercase font-bold text-gray-600">
                                <tr>
                                    <th class="px-3 py-2 min-w-[200px]">Nama Kegiatan</th>
                                    <th class="px-3 py-2 min-w-[200px]">Pekerjaan</th>
                                    <th class="px-3 py-2 w-32">Nilai Kontrak</th>
                                    <th class="px-3 py-2 w-36">Mulai</th>
                                    <th class="px-3 py-2 w-36">Akhir</th>
                                    <th class="px-3 py-2 w-32">Uang Muka</th>
                                    <th class="px-3 py-2 w-32">Termin 1</th>
                                    <th class="px-3 py-2 w-32">Termin 2</th>
                                    <th class="px-3 py-2 w-32">Termin 3</th>
                                    <th class="px-3 py-2 w-32">Termin 4</th>
                                    <th class="px-3 py-2 w-32">Total</th>
                                    <th class="px-3 py-2 min-w-[150px]">Status</th>
                                    <th class="px-3 py-2 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, i) in items" :key="i">
                                    <tr class="border-t hover:bg-indigo-50 transition group focus-within:bg-indigo-50">
                                        <td class="p-1"><input type="text" :name="`items[${i}][nama_kegiatan]`" x-model="item.nama_kegiatan" :x-ref="`row_${i}_kegiatan`" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 transition"></td>
                                        <td class="p-1"><input type="text" :name="`items[${i}][pekerjaan]`" x-model="item.pekerjaan" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][nilai_kontrak]`" x-model="item.nilai_kontrak" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1"><input type="date" :name="`items[${i}][tanggal_mulai]`" x-model="item.tanggal_mulai" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 transition"></td>
                                        <td class="p-1"><input type="date" :name="`items[${i}][tanggal_akhir]`" x-model="item.tanggal_akhir" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][uang_muka]`" x-model="item.uang_muka" @input="recalc(i)" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][termin1]`" x-model="item.termin1" @input="recalc(i)" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][termin2]`" x-model="item.termin2" @input="recalc(i)" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][termin3]`" x-model="item.termin3" @input="recalc(i)" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1"><input type="number" :name="`items[${i}][termin4]`" x-model="item.termin4" @input="recalc(i)" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 text-right transition"></td>
                                        <td class="p-1 text-right font-mono text-xs font-bold text-gray-800 bg-gray-50/50" x-text="item.total"></td>
                                        <td class="p-1"><input type="text" :name="`items[${i}][status]`" x-model="item.status" class="w-full rounded border border-gray-300 bg-white text-xs focus:ring-indigo-500 focus:border-indigo-500 px-2 py-1.5 transition"></td>
                                        <td class="p-1 text-center">
                                            <button type="button" @click="removeItem(i)" class="text-red-400 hover:text-red-600 focus:outline-none transition w-8 h-8 rounded-full hover:bg-red-50" title="Hapus Baris">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-8 flex justify-end border-t pt-4">
                        <button type="submit" class="px-5 py-2 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-md hover:bg-emerald-700 transition flex items-center gap-2">
                            <i class="fas fa-save"></i> <span x-text="isEdit ? 'Perbarui Kontrak' : 'Simpan Kontrak'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="border border-slate-200 dark:border-slate-700 rounded-2xl bg-white dark:bg-slate-900 shadow-sm overflow-x-auto transition-all duration-300">
        <table class="w-full min-w-[800px] text-sm text-left text-slate-700">
            <thead class="bg-indigo-50/50 dark:bg-indigo-950/20 text-[10px] uppercase font-bold text-indigo-600 dark:text-indigo-400 tracking-widest border-b border-indigo-100 dark:border-indigo-900/50 transition-all">
                <tr>
                    <th class="px-6 py-4 w-12 text-center text-slate-500">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 h-4 w-4 transition-all">
                    </th>
                    <th class="px-6 py-4">No.</th>
                    <th class="px-6 py-4">Tahun Anggaran</th>
                    <th class="px-6 py-4 text-center">Jumlah Item</th>
                    <th class="px-6 py-4 text-right">Total Anggaran (Rp)</th>
                    <th class="px-6 py-4">Update Terakhir</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $i => $row)
                    @php $hl = request('highlight'); @endphp
                    <tr class="border-t dark:border-slate-800 transition-all duration-200 {{ $hl === ($row['id'] ?? null) ? 'bg-orange-50 dark:bg-orange-950/30' : '' }}" :class="{ 'bg-indigo-50/50 dark:bg-indigo-950/20': selected.includes('{{ $row['id'] }}') }">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" value="{{ $row['id'] }}" x-model="selected" @click="updateSelectAll()" class="rounded-md border-slate-300 text-indigo-600 h-4 w-4 focus:ring-indigo-500 transition-all opacity-70 hover:opacity-100">
                        </td>
                        <td class="px-6 py-4">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">{{ $row['tahun'] ?: '-' }}</td>
                        <td class="px-6 py-4 text-center">{{ $row['kontrak_count'] }}</td>
                        <td class="px-6 py-4 text-right font-medium text-slate-900 dark:text-slate-100">{{ number_format($row['nilai_total'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-[11px] font-medium tracking-tight">
                            <div class="flex flex-col">
                                <span>{{ \Carbon\Carbon::createFromTimestamp($row['updated'])->translatedFormat('d F Y') }}</span>
                                <span class="text-[10px] opacity-60 uppercase">{{ \Carbon\Carbon::createFromTimestamp($row['updated'])->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('reports.belanja.modal.show', $row['id']) }}" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm border border-slate-800 dark:border-slate-600" title="Lihat">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('reports.belanja.modal.export_excel', $row['id']) }}" class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors shadow-sm border border-emerald-200 dark:border-emerald-800" title="Export Excel">
                                    <i class="fas fa-file-excel text-xs"></i>
                                </a>
                                <button type="button" @click='initModal(true, @json($row["id"]), @json($row["tahun"]), @json($row["raw_items"]))' class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm border border-slate-800 dark:border-slate-600" title="Edit">
                                    <i class="far fa-edit text-xs"></i>
                                </button>
                                <form action="{{ route('reports.belanja.modal.delete', $row['id']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="if(confirm('Hapus transaksi ini?')) $el.form.submit()" class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm border border-slate-800 dark:border-slate-600" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-3 py-6 text-center text-gray-500" colspan="7">Belum ada data belanja modal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('reports.belanja.modal.bulk_delete') }}" class="inline-block">
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
