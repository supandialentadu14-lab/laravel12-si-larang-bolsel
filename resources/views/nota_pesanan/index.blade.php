@extends('layouts.admin')

@section('header', 'Daftar Surat Pesanan')
@section('content')

<div x-data="notaPesanan()" class="bg-white rounded-lg shadow p-6 mb-6">
    
    {{-- Modal Create/Edit --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-10 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition.scale.95 
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-200 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                
                {{-- Modal Header --}}
                <div class="bg-slate-800 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i :class="isEdit ? 'fas fa-edit text-amber-400' : 'fas fa-plus-circle text-emerald-400'"></i>
                        <span x-text="isEdit ? 'Edit Surat Pesanan' : 'Buat Surat Pesanan Baru'"></span>
                    </h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white transition-colors duration-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('reports/nota-pesanan') }}/' + editId + '/update' : '{{ route('reports.nota.save') }}'" 
                    method="POST" 
                    class="p-0">
                    @csrf
                    <template x-if="isEdit">
                        @method('POST')
                    </template>
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-info-circle text-indigo-500"></i> Informasi Umum
                            </h4>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kegiatan <span class="text-rose-500">*</span></label>
                                    <textarea name="kegiatan" x-model="kegiatan" @input="updatePekerjaan()" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition uppercase text-sm font-bold bg-white" rows="3" placeholder="..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sub Kegiatan <span class="text-rose-500">*</span></label>
                                    <textarea name="sub_kegiatan" x-model="sub_kegiatan" @input="updatePekerjaan()" class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition uppercase text-sm font-bold bg-white" rows="3" placeholder="..." required></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rekening <span class="text-rose-500">*</span></label>
                                        <input type="text" name="rekening" x-model="rekening" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="5.x.x.x" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori Belanja <span class="text-rose-500">*</span></label>
                                        <select name="belanja" x-model="belanja" @change="updatePekerjaan()" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="pekerjaan" x-model="pekerjaan">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Nota (Hanya Angka) <span class="text-rose-500">*</span></label>
                                    <div class="flex items-center">
                                        <input type="text" name="nomor" x-model="nomor" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-mono text-sm" placeholder="001" required>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 italic italic">Format otomatis: [No]/NPB/[OPD]/IV/{{ now()->year }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Surat Pesanan <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal" x-model="tanggal" @change="updatePekerjaan()" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Supplier <span class="text-rose-500">*</span></label>
                                    <select name="supplier_id" x-model="supplier_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $s) <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->toko }})</option> @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200">
                            <div class="flex flex-wrap justify-between items-center gap-4 mb-4 border-b border-slate-100 pb-3">
                                <h4 class="text-slate-800 font-bold flex items-center gap-2">
                                    <i class="fas fa-list text-indigo-500"></i> Rincian Barang / Jasa
                                </h4>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="addItem()" class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center gap-2 border border-indigo-200">
                                        <i class="fas fa-plus"></i> Tambah Baris
                                    </button>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold border-b border-slate-200">
                                            <th class="px-2 py-3 text-left w-12 text-center">No</th>
                                            <th class="px-4 py-3 text-left min-w-[350px]">Nama Barang / Deskripsi</th>
                                            <th class="px-2 py-3 text-center min-w-[100px]">Unit</th>
                                            <th class="px-2 py-3 text-right min-w-[160px]">Harga Satuan</th>
                                            <th class="px-2 py-3 text-center min-w-[100px]">Qty</th>
                                            <th class="px-2 py-3 text-right min-w-[180px]">Total</th>
                                            <th class="px-2 py-3 text-center w-12">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="transition-all duration-200 group">
                                                <td class="px-2 py-3 text-center font-mono text-slate-400" x-text="index + 1"></td>
                                                <td class="px-4 py-3">
                                                    <div class="relative">
                                                        <select x-model="item.name" @change="onProductChange(index, $el.value)" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-0 transition text-sm appearance-none outline-none bg-white">
                                                            <option value="">-- Cari/Pilih Barang --</option>
                                                            <template x-for="p in productsByBelanja()" :key="p.id">
                                                                <option :value="p.name" x-text="p.name"></option>
                                                            </template>
                                                        </select>
                                                        <div class="absolute right-2 top-2.5 text-slate-300 pointer-events-none group-hover:text-slate-400">
                                                            <i class="fas fa-chevron-down text-xs"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" :name="`items[${index}][name]`" x-model="item.name">
                                                </td>
                                                <td class="px-2 py-3">
                                                    <input type="text" :name="`items[${index}][unit]`" x-model="item.unit" class="w-full px-2 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-0 text-center text-sm outline-none bg-white font-medium" placeholder="...">
                                                </td>
                                                <td class="px-2 py-3">
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-2.5 text-slate-300 text-xs font-mono">Rp</span>
                                                        <input type="number" :name="`items[${index}][price]`" x-model="item.price" @input="recalc(index)" class="w-full pl-8 pr-2 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-0 text-right text-sm font-mono outline-none bg-white" placeholder="0" style="min-width: 140px;">
                                                    </div>
                                                </td>
                                                <td class="px-2 py-3">
                                                    <input type="number" :name="`items[${index}][qty]`" x-model="item.qty" @input="recalc(index)" class="w-full px-2 py-2 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-0 text-center text-sm font-bold outline-none bg-white" placeholder="0" style="min-width: 80px;">
                                                </td>
                                                <td class="px-2 py-3 text-right">
                                                    <div class="relative">
                                                        <span class="absolute left-2 top-2.5 text-slate-300 text-xs font-mono">Rp</span>
                                                        <input type="text" readonly :value="Number(item.total).toLocaleString('id-ID')" class="w-full pl-8 pr-2 py-2 rounded-lg border bg-slate-50 border-slate-100 text-right text-sm font-bold text-slate-700 outline-none" style="min-width: 150px;">
                                                        <input type="hidden" :name="`items[${index}][total]`" x-model="item.total">
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-600 transition-colors p-2 hover:bg-rose-50 rounded-lg">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-indigo-500 text-white font-bold">
                                            <td colspan="5" class="px-4 py-3 text-right text-xs uppercase tracking-wider">Total Keseluruhan :</td>
                                            <td class="px-4 py-3 text-right font-mono">
                                                <span class="text-xs mr-1 opacity-70 font-normal italic">Rp</span>
                                                <span class="text-lg" x-text="items.reduce((sum, item) => sum + (Number(item.total) || 0), 0).toLocaleString('id-ID')"></span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-slate-50 px-6 py-4 flex justify-between items-center border-t border-slate-200">
                        <p class="text-[11px] text-slate-400 max-w-sm italic">
                            * Pastikan nomor nota belum digunakan sebelumnya. Data akan otomatis terhubung ke BAP Pemeriksaan & Penerimaan.
                        </p>
                        <div class="flex gap-3">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 border border-emerald-700 rounded-lg text-sm font-bold text-white hover:bg-emerald-700 transition drop-shadow-md flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <button @click="initForm(false)" class="inline-flex justify-center w-full lg:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all duration-200">
                <i class="fas fa-plus-circle"></i> <span class="whitespace-nowrap">Buat Surat Pesanan Baru</span>
            </button>
        </div>

        <div class="w-full lg:max-w-xl">
            <form action="{{ route('reports.nota.list') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            onsearch="this.form.requestSubmit()"
                            placeholder="Cari nomor nota atau rincian belanja..."
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
            <thead class="bg-indigo-50/50 text-[10px] uppercase font-bold text-indigo-600 tracking-widest border-b border-indigo-100">
                <tr>
                    <th class="px-6 py-4 w-12 text-center text-slate-500">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all h-4 w-4">
                    </th>
                    <th class="px-6 py-4">Nomor & Tanggal Transaksi</th>
                    <th class="px-6 py-4 w-1/3">Pekerjaan / Jenis Belanja</th>
                    <th class="px-6 py-4 text-right">Nilai Anggaran</th>
                    <th class="px-6 py-4 text-center">Update</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($items as $row)
                    @php 
                        $raw = $row['raw_data'] ?? [];
                        $totalVal = (int)($row['total'] ?? 0);
                    @endphp
                    <tr class="transition-all duration-200 group" :class="{ 'bg-indigo-50/30': selected.includes('{{ $row['id'] }}') }">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" value="{{ $row['id'] }}" x-model="selected" @click="updateSelectAll()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 text-sm tracking-tight">{{ $row['nomor'] }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1 uppercase">
                                    <i class="far fa-calendar-alt text-indigo-400"></i> {{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 group-hover:pl-8 transition-all duration-300 border-l-2 border-transparent group-hover:border-indigo-400">
                            <div class="flex flex-col">
                                <span class="text-slate-700 font-semibold whitespace-normal leading-relaxed text-xs max-w-sm">{{ $row['belanja'] }}</span>
                                <span class="text-[10px] text-slate-400 flex items-center gap-1 uppercase tracking-tighter">
                                    <i class="fas fa-tag"></i> {{ $raw['rekening'] ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="font-mono font-bold text-slate-800">Rp {{ number_format($totalVal, 0, ',', '.') }}</span>
                                <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded font-bold uppercase">{{ $raw['rekening'] ?? '' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col">
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($row['updated'])->diffForHumans() }}</span>
                                <span class="text-[10px] text-slate-300 italic">oleh sistem</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @include('partials.action_buttons', [
                                'show' => route('reports.nota.show', $row['id']),
                                'edit' => '#',
                                'editClick' => "initForm(true, '" . $row['id'] . "', " . json_encode($raw) . ")",
                                'delete' => route('reports.nota.delete', $row['id'])
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center group">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 group-hover:text-indigo-400 transition-colors duration-500 border border-slate-100 border-dashed">
                                    <i class="fas fa-folder-open text-3xl"></i>
                                </div>
                                <span class="text-slate-400 italic text-sm">Belum ada dokumen surat pesanan.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <form x-show="selected.length > 0" method="POST" action="{{ route('reports.nota.bulk_delete') }}" class="inline-block" @submit.prevent="if(confirm('Hapus ' + selected.length + ' dokumen terpilih?')) $el.submit()">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-800 rounded-lg text-slate-800 font-bold text-[10px] hover:bg-slate-50 transition-all shadow-sm group">
                <i class="fas fa-trash text-slate-800 group-hover:text-rose-600 transition-colors"></i>
                <span>HAPUS <span x-text="selected.length"></span> ITEM TERPILIH</span>
            </button>
        </form>
        <div class="flex-1 w-full md:w-auto overflow-x-auto">
            {{ $items->links() }}
        </div>
    </div>
</div>

<script>
function notaPesanan() {
    return {
        selected: [],
        allSelected: false,
        showModal: false,
        isEdit: false,
        editId: '',
        
        // Form Data
        kegiatan: '',
        sub_kegiatan: '',
        rekening: '',
        pekerjaan: '',
        nomor: '',
        tanggal: '{{ now()->toDateString() }}',
        belanja: '{{ $categories->first()->name ?? "Belanja Barang & Jasa" }}',
        supplier_id: '',
        items: [],
        
        products: {!! json_encode($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'unit' => $p->unit,
            'price' => (int)($p->price ?? 0),
            'category_name' => optional($p->category)->name
        ])) !!},

        toggleAll() {
            this.allSelected = !this.allSelected;
            this.selected = this.allSelected ? {!! json_encode($items->map(fn($r) => (string)$r['id'])) !!} : [];
        },
        updateSelectAll() {
            this.allSelected = this.selected.length === {{ count($items) }};
        },

        initForm(edit = false, id = '', data = null) {
            this.isEdit = edit;
            this.editId = id;
            if (edit && data) {
                this.kegiatan = data.kegiatan || '';
                this.sub_kegiatan = data.sub_kegiatan || '';
                this.rekening = data.rekening || '';
                this.pekerjaan = data.pekerjaan || '';
                this.belanja = data.belanja || '';
                // If nomor has a code like "001/NPB/...", we show the "001" if it was originally input as "001"
                this.nomor = data.nomor_raw || data.nomor || '';
                this.tanggal = data.tanggal || '';
                this.belanja = data.belanja || '';
                this.supplier_id = data.supplier_id || (data.penyedia ? data.penyedia.id : '');
                this.items = (data.items || []).map(it => ({
                    name: it.name,
                    qty: it.qty,
                    unit: it.unit,
                    price: it.price,
                    total: it.total,
                }));
            } else {
                this.kegiatan = '';
                this.sub_kegiatan = '';
                this.rekening = '';
                this.pekerjaan = '';
                this.belanja = ''; // Reset belanja to empty for new form
                this.nomor = '';
                this.tanggal = '{{ now()->toDateString() }}';
                this.supplier_id = '';
                this.items = [];
                this.addItem();
            }
            this.showModal = true;
        },

        updatePekerjaan() {
            if (!this.belanja || !this.kegiatan || !this.sub_kegiatan) {
                this.pekerjaan = '';
                return;
            }
            const tahun = this.tanggal ? new Date(this.tanggal).getFullYear() : new Date().getFullYear();
            this.pekerjaan = `Belanja ${this.belanja} Pada Keg. ${this.kegiatan} Tahun ${tahun} (DAU) Sub Keg. ${this.sub_kegiatan} Tahun ${tahun}`;
        },

        addItem() {
            this.items.push({ name: '', qty: '', unit: '', price: '', total: 0 });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            if (this.items.length === 0) this.addItem();
        },
        onProductChange(index, name) {
            const p = this.products.find(x => x.name === name);
            if (p) {
                this.items[index].unit = p.unit || '';
                this.items[index].price = p.price || 0;
            }
            this.recalc(index);
        },
        recalc(index) {
            const it = this.items[index];
            it.total = (Number(it.qty) || 0) * (Number(it.price) || 0);
        },
        productsByBelanja() {
            const b = this.belanja;
            if (!b) return this.products;
            return this.products.filter(p => p.category_name === b);
        }
    }
}
</script>

@endsection
