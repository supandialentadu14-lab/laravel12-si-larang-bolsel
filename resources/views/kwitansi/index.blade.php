@extends('layouts.admin')

@section('header', 'Daftar Kwitansi')
@section('content')

<div x-data="kwitansi()" class="bg-white rounded-lg shadow p-6 mb-6">
    
    {{-- Modal Create/Edit --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4 pb-10 text-center">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <div x-show="showModal" x-transition.scale.95 
                class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                
                {{-- Modal Header --}}
                <div class="bg-slate-800 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i :class="isEdit ? 'fas fa-edit text-amber-400' : 'fas fa-file-invoice-dollar text-emerald-400'"></i>
                        <span x-text="isEdit ? 'Edit Kwitansi Pembayaran' : 'Buat Kwitansi Pembayaran Baru'"></span>
                    </h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white transition-colors duration-200 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('reports/kwitansi') }}/' + editId + '/update' : '{{ route('reports.kwitansi.save') }}'" method="POST" class="p-0">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="id" :value="editId">
                    </template>
                    
                    <div class="p-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                            <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                                <i class="fas fa-info-circle text-indigo-500"></i> Informasi Pembayaran
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Kwitansi (Hanya Angka) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nomor_kwt" x-model="nomor_kwt" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition font-mono text-sm" placeholder="Contoh: 001" required>
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Format otomatis: [No]/KW/{{ $opd->singkatan_opd ?? 'OPD' }}/IV/{{ now()->year }}</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                                    <input type="number" name="tahun" x-model="tahun" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm bg-white font-bold" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kode Rekening <span class="text-rose-500">*</span></label>
                                    <input type="text" name="rekening" x-model="rekening" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="5.x.x.x" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Kwitansi <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal" x-model="tanggal" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ref. BAP Penerimaan <span class="text-rose-500">*</span></label>
                                    <select name="penerimaan_nomor" x-model="penerimaan_nomor" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm appearance-none bg-white font-bold" required>
                                        <option value="">-- Pilih BAP Penerimaan --</option>
                                        @foreach($docs as $n)
                                            <option value="{{ $n['nomor'] }}">[{{ $n['tanggal'] }}] - {{ $n['nomor'] }} - {{ $n['belanja'] }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                        <i class="fas fa-info-circle text-indigo-400"></i> Kwitansi akan merujuk pada data BAP Penerimaan yang dipilih.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-5 border-t border-slate-100 flex justify-end gap-3 px-2">
                            <button type="submit" class="px-7 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex items-center gap-2">
                                <i class="fas fa-save"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Data Kwitansi'"></span>
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
                <i class="fas fa-plus-circle"></i> <span class="whitespace-nowrap">Buat Kwitansi Baru</span>
            </button>
        </div>

        <div class="w-full lg:max-w-xl">
            <form action="{{ route('reports.kwitansi.list') }}" method="GET">
                <div x-data="{ query: '{{ request('search') }}' }" class="flex items-center rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-500 transition-all overflow-hidden h-11">
                    <div class="h-full px-4 border-r border-slate-100 flex items-center justify-center text-slate-400 bg-slate-50/50">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <div class="flex-1 flex items-center h-full">
                        <input type="text" name="search" x-model="query" 
                            onsearch="this.form.requestSubmit()"
                            placeholder="Cari nomor kwitansi atau rincian..."
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
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 transition-all">
                    </th>
                    <th class="px-6 py-4">Nomor & Tanggal Kwitansi</th>
                    <th class="px-6 py-4 w-1/3">Rincian Pembayaran</th>
                    <th class="px-6 py-4 text-right">Nilai Pembayaran</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($items as $row)
                    @php $raw = $row['raw_data'] ?? []; @endphp
                    <tr class="transition-all duration-200 group" :class="{ 'bg-indigo-50/30': selected.includes('{{ $row['id'] }}') }">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" value="{{ $row['id'] }}" x-model="selected" @click="updateSelectAll()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 transition duration-150">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 tracking-tight text-sm">{{ $row['nomor_kwt'] }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 uppercase font-semibold flex items-center gap-1 italic tracking-tight">
                                    <i class="far fa-calendar-alt text-slate-300"></i> {{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-slate-700 font-semibold whitespace-normal leading-relaxed text-xs max-w-xs">{{ $row['uraian'] }}</span>
                                <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 w-fit mt-1 uppercase tracking-tighter shadow-sm font-mono">Ref: {{ $row['penerimaan_nomor'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="font-mono font-extrabold text-slate-800 text-base">Rp {{ number_format($row['jumlah'], 0, ',', '.') }}</span>
                                <span class="text-[9px] text-slate-400 bg-slate-50 px-1 rounded uppercase tracking-tighter">Budget {{ $raw['tahun'] ?? now()->year }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @include('partials.action_buttons', [
                                'show' => route('reports.kwitansi.show', $row['id']),
                                'edit' => '#',
                                'editClick' => "initForm(true, '" . $row['id'] . "', " . json_encode($raw) . ")",
                                'delete' => route('reports.kwitansi.delete', $row['id'])
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center group bg-slate-50/20">
                            <div class="flex flex-col items-center gap-4 transition-all duration-700 group-hover:scale-105 transform">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-200 group-hover:text-indigo-300 transition-colors duration-500 border border-slate-100 border-dashed shadow-sm">
                                    <i class="fas fa-receipt text-3xl"></i>
                                </div>
                                <span class="text-slate-400 italic text-sm font-semibold tracking-tight italic">Belum ada dokumen kwitansi yang dibuat.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <form x-show="selected.length > 0" method="POST" action="{{ route('reports.kwitansi.bulk_delete') }}" class="inline-block" @submit.prevent="if(confirm('Hapus dokumen kwitansi yang terpilih?')) $el.submit()">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="submit" 
                class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-slate-800 rounded-lg text-slate-800 font-bold text-[10px] hover:bg-slate-50 transition-all shadow-sm group">
                <i class="fas fa-trash text-slate-800 group-hover:text-rose-600 transition-colors"></i>
                <span>HAPUS <span x-text="selected.length"></span> KWITANSI TERPILIH</span>
            </button>
        </form>
        <div class="flex-1 w-full md:w-auto overflow-x-auto drop-shadow-sm">
            {{ $items->links() }}
        </div>
    </div>
</div>

<script>
function kwitansi() {
    return {
        selected: [],
        allSelected: false,
        showModal: false,
        isEdit: false,
        editId: '',
        
        // Form Data
        tahun: '{{ now()->year }}',
        rekening: '',
        nomor_kwt: '',
        tanggal: '{{ now()->toDateString() }}',
        penerimaan_nomor: '',

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
                this.tahun = data.tahun || '{{ now()->year }}';
                this.rekening = data.rekening || '';
                // Handle nomor_kwt_raw if available, else first segment of formatted number
                this.nomor_kwt = data.nomor_kwt_raw || (data.nomor_kwt ? data.nomor_kwt.split('/')[0] : '');
                this.tanggal = data.tanggal || '';
                this.penerimaan_nomor = data.penerimaan_nomor || '';
            } else {
                this.tahun = '{{ now()->year }}';
                this.rekening = '';
                this.nomor_kwt = '';
                this.tanggal = '{{ now()->toDateString() }}';
                this.penerimaan_nomor = '';
            }
            this.showModal = true;
        }
    }
}
</script>

@endsection