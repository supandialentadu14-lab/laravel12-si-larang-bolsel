@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('title', 'Edit Belanja Modal')

@section('content')
    <script>
        window.belanjaModalForm = function () {
            return {
                tahun: '{{ $data['tahun'] ?? now()->year }}',
                items: {!! json_encode(($data['items'] ?? [])) !!},
                init() {
                    const seen = new Set();
                    this.items = (this.items || []).filter((row) => {
                        const key = `${row.nama_kegiatan || ''}|${row.pekerjaan || ''}|${row.nilai_kontrak || ''}|${row.tanggal_mulai || ''}|${row.tanggal_akhir || ''}|${row.uang_muka || ''}|${row.termin1 || ''}|${row.termin2 || ''}|${row.termin3 || ''}|${row.termin4 || ''}|${row.status || ''}`;
                        if (seen.has(key)) return false;
                        seen.add(key);
                        return true;
                    });
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

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Belanja Modal</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Perbarui Kontrak dan Termin</p>
            </div>
            <a href="{{ route('reports.belanja.modal.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-times text-xs"></i>
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
            <form method="POST" action="{{ route('reports.belanja.modal.save') }}" x-data="belanjaModalForm()" x-init="init()" class="space-y-6">
                @csrf
                <input type="hidden" name="id" value="{{ session('belanja_modal_current_id') }}">

                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Umum & Header
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                            <input type="number" min="2000" max="2100" name="tahun" x-model="tahun" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama OPD Pelaksana</label>
                            <input type="text" name="opd" value="{{ $master['opd']['nama'] ?? ($opd->nama_opd ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm bg-white" placeholder="Nama Dinas/Badan...">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="addItem()" class="w-full md:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Tambah Baris Kontrak
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/30 p-4 rounded-xl border border-indigo-100">
                    <h4 class="text-indigo-900 font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-list text-indigo-500"></i> Daftar Rincian Belanja Modal
                    </h4>
                    <div class="overflow-x-auto border border-indigo-100 rounded-xl bg-white">
                        <table class="w-full text-left text-slate-700">
                            <thead class="bg-indigo-50 text-[10px] uppercase font-bold text-indigo-600 tracking-wider">
                            <tr>
                                <th class="px-3 py-3 border-b border-indigo-100">Kegiatan</th>
                                <th class="px-3 py-3 border-b border-indigo-100">Pekerjaan</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-28">Nilai Kontrak</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-24">Mulai</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-24">Akhir</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-20">UM (Rp)</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-20">T1 (Rp)</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-20">T2 (Rp)</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-20">T3 (Rp)</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-20">T4 (Rp)</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-24 text-right">Total Realisasi</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-24">Status</th>
                                <th class="px-3 py-3 border-b border-indigo-100 w-10"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-indigo-50">
                                <template x-for="(item, i) in items" :key="i">
                                    <tr class="hover:bg-indigo-50/50 transition">
                                        <td class="p-2 min-w-[150px]"><input type="text" :name="`items[${i}][nama_kegiatan]`" x-model="item.nama_kegiatan" :x-ref="`row_${i}_kegiatan`" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition"></td>
                                        <td class="p-2 min-w-[150px]"><input type="text" :name="`items[${i}][pekerjaan]`" x-model="item.pekerjaan" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][nilai_kontrak]`" x-model="item.nilai_kontrak" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition font-mono border-indigo-200"></td>
                                        <td class="p-2"><input type="date" :name="`items[${i}][tanggal_mulai]`" x-model="item.tanggal_mulai" class="w-full rounded-md border border-slate-300 bg-white text-[10px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition"></td>
                                        <td class="p-2"><input type="date" :name="`items[${i}][tanggal_akhir]`" x-model="item.tanggal_akhir" class="w-full rounded-md border border-slate-300 bg-white text-[10px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][uang_muka]`" x-model="item.uang_muka" @input="recalc(i)" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][termin1]`" x-model="item.termin1" @input="recalc(i)" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][termin2]`" x-model="item.termin2" @input="recalc(i)" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][termin3]`" x-model="item.termin3" @input="recalc(i)" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition"></td>
                                        <td class="p-2"><input type="number" :name="`items[${i}][termin4]`" x-model="item.termin4" @input="recalc(i)" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right py-1.5 transition"></td>
                                        <td class="p-2 text-right font-mono text-[11px] font-bold text-indigo-700" x-text="new Intl.NumberFormat('id-ID').format(item.total)"></td>
                                        <td class="p-2 min-w-[100px]"><input type="text" :name="`items[${i}][status]`" x-model="item.status" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="Status..."></td>
                                        <td class="p-2 text-center">
                                            <button type="button" @click="removeItem(i)" class="text-rose-400 hover:text-rose-600 transition p-1"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('reports.belanja.modal.list') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection
