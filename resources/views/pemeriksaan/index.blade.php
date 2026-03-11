@extends('layouts.admin')

@section('header', 'Berita Acara Pemeriksaan (BAP)')
@section('content')

<div x-data="pemeriksaan()" class="bg-white rounded-lg shadow p-6 mb-6">
    
    {{-- Modal Create/Edit --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="showModal = false"></div>

            <div x-show="showModal" x-transition.scale.95 
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 antialiased" style="backface-visibility: hidden; transform: translateZ(0);">
                
                {{-- Modal Header --}}
                <div class="bg-slate-800 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i :class="isEdit ? 'fas fa-edit text-amber-400' : 'fas fa-check-double text-emerald-400'"></i>
                        <span x-text="isEdit ? 'Edit BAP Pemeriksaan' : 'Buat BAP Pemeriksaan Baru'"></span>
                    </h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white transition-colors duration-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('reports.pemeriksaan.save') }}" method="POST" class="p-0">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="id" :value="editId">
                    </template>
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-info-circle text-indigo-500"></i> Informasi Pemeriksaan
                            </h4>
                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor BAP (Hanya Angka) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nomor" x-model="nomor" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-mono text-sm" placeholder="Contoh: 001" required>
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Format otomatis: [No]/BAPB/{{ $opd->singkatan_opd ?? 'OPD' }}/IV/{{ now()->year }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal BAP <span class="text-rose-500">*</span></label>
                                        <input type="date" name="tanggal" x-model="tanggal" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tempat <span class="text-rose-500">*</span></label>
                                        <input type="text" name="tempat" x-model="tempat" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Bolaang Uki" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Referensi Surat Pesanan (Nota) <span class="text-rose-500">*</span></label>
                                    <select name="nota_id" x-model="nota_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        <option value="">-- Pilih Nota Pesanan --</option>
                                        @foreach($notaDocs as $n)
                                            <option value="{{ $n['id'] }}">[{{ $n['tanggal'] }}] - {{ $n['nomor'] }} - {{ $n['belanja'] }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <i class="fas fa-info-circle text-indigo-400"></i> BAP ini akan mengambil detail barang secara otomatis dari Nota Pesanan yang dipilih.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data BAP'"></span>
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
                <i class="fas fa-plus-circle"></i> <span class="whitespace-nowrap">Buat BAP Pemeriksaan Baru</span>
            </button>
        </div>

        <div class="w-full lg:max-w-xl">
            <form action="{{ route('reports.pemeriksaan.list') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            onsearch="this.form.requestSubmit()"
                            placeholder="Cari nomor BAP atau nomor Nota..."
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
                    <th class="px-6 py-4 w-12 text-center">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all h-4 w-4">
                    </th>
                    <th class="px-6 py-4">Nomor BAP Dokumen</th>
                    <th class="px-6 py-4">Tanggal Pemeriksaan</th>
                    <th class="px-6 py-4">Referensi Dokumen</th>
                    <th class="px-6 py-4 text-right">Nilai Total</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($items as $row)
                    @php $raw = $row['raw_data'] ?? []; @endphp
                    <tr class="transition-all duration-200 group" :class="{ 'bg-indigo-50/40': selected.includes('{{ $row['id'] }}') }">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" value="{{ $row['id'] }}" x-model="selected" @click="updateSelectAll()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 tracking-tight">{{ $row['nomor'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-600 uppercase text-xs font-semibold">{{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="bg-slate-100 text-slate-600 text-[10px] px-2 py-0.5 rounded-full border border-slate-200 font-bold">{{ $row['nota_nomor'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-mono font-bold text-slate-700">Rp {{ number_format($row['total'], 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @include('partials.action_buttons', [
                                'show' => route('reports.pemeriksaan.show', $row['id']),
                                'edit' => '#',
                                'editClick' => "initForm(true, '" . $row['id'] . "', " . json_encode($raw) . ")",
                                'delete' => route('reports.pemeriksaan.delete', $row['id'])
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data BAP Pemeriksaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <form x-show="selected.length > 0" method="POST" action="{{ route('reports.pemeriksaan.bulk_delete') }}" class="inline-block" @submit.prevent="if(confirm('Hapus item terpilih?')) $el.submit()">
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
        <div class="flex-1 overflow-x-auto">
            {{ $items->links() }}
        </div>
    </div>
</div>

<script>
function pemeriksaan() {
    return {
        selected: [],
        allSelected: false,
        showModal: false,
        isEdit: false,
        editId: '',
        
        // Form Data
        nomor: '',
        tanggal: '{{ now()->toDateString() }}',
        tempat: 'Bolaang Uki',
        nota_id: '',

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
                this.nomor = data.nomor_raw || data.nomor || '';
                this.tanggal = data.tanggal || '';
                this.tempat = data.tempat || 'Bolaang Uki';
                this.nota_id = data.nota ? data.nota.id : '';
            } else {
                this.nomor = '';
                this.tanggal = '{{ now()->toDateString() }}';
                this.tempat = 'Bolaang Uki';
                this.nota_id = '';
            }
            this.showModal = true;
        }
    }
}
</script>

@endsection
