@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('title', 'Tambah Belanja Modal')

@section('content')
    <script>
        window.belanjaModalForm = function () {
            return {
                tahun: '{{ $data['tahun'] ?? now()->year }}',
                items: {!! json_encode(($data['items'] ?? [])) !!},
                init() {
                    const isBlank = (it) => {
                        const nz = (v) => Number(v || 0) === 0;
                        return (!String(it?.nama_kegiatan || '').trim()
                            && !String(it?.pekerjaan || '').trim()
                            && nz(it?.nilai_kontrak)
                            && !String(it?.tanggal_mulai || '').trim()
                            && !String(it?.tanggal_akhir || '').trim()
                            && nz(it?.uang_muka) && nz(it?.termin1) && nz(it?.termin2) && nz(it?.termin3) && nz(it?.termin4)
                            && !String(it?.status || '').trim());
                    };
                    if (!Array.isArray(this.items) || this.items.length === 0) {
                        this.addItem();
                        this.focusRow(0);
                        return;
                    }
                    const allBlank = this.items.every(isBlank);
                    if (allBlank && this.items.length > 1) {
                        this.items = [this.items[0]];
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

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah Belanja Modal</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Input Kontrak dan Termin</p>
            </div>
            <a href="{{ route('reports.belanja.modal.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
            <form method="POST" action="{{ route('reports.belanja.modal.save') }}" x-data="belanjaModalForm()" x-init="init()" class="space-y-6">
                @csrf

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

                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-indigo-900 font-bold flex items-center gap-2 uppercase tracking-widest text-[10px]">
                            <i class="fas fa-list text-indigo-500"></i> Rincian Belanja Modal
                        </h4>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Total: <span x-text="items.length"></span> Item
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <template x-for="(item, i) in items" :key="i">
                            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-[10px] font-black" x-text="i + 1"></div>
                                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Item Belanja Modal</span>
                                    </div>
                                    <button type="button" @click="removeItem(i)" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center shadow-sm active:scale-90">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="p-6 space-y-6">
                                    {{-- Row 1: Kegiatan & Pekerjaan --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                            <input type="text" :name="`items[${i}][nama_kegiatan]`" x-model="item.nama_kegiatan" :x-ref="`row_${i}_kegiatan`" 
                                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition" 
                                                placeholder="Contoh: Pembangunan Jalan..." required>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nama Pekerjaan</label>
                                            <input type="text" :name="`items[${i}][pekerjaan]`" x-model="item.pekerjaan" 
                                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition"
                                                placeholder="Detail pekerjaan...">
                                        </div>
                                    </div>

                                    {{-- Row 2: Nilai & Tanggal --}}
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nilai Kontrak (Rp)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-xs font-bold text-slate-400">Rp</span>
                                                </div>
                                                <input type="number" :name="`items[${i}][nilai_kontrak]`" x-model="item.nilai_kontrak" 
                                                    class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-right">
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Tanggal Mulai</label>
                                            <input type="date" :name="`items[${i}][tanggal_mulai]`" x-model="item.tanggal_mulai" 
                                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Tanggal Akhir</label>
                                            <input type="date" :name="`items[${i}][tanggal_akhir]`" x-model="item.tanggal_akhir" 
                                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition">
                                        </div>
                                    </div>

                                    {{-- Row 3: Termin Payments --}}
                                    <div class="bg-indigo-50/20 rounded-[2rem] p-6 border border-indigo-100/30">
                                        <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-4 text-center">Realisasi Pembayaran (Termin)</label>
                                        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                                            <div class="space-y-1">
                                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center">Uang Muka</label>
                                                <input type="number" :name="`items[${i}][uang_muka]`" x-model="item.uang_muka" @input="recalc(i)" 
                                                    class="w-full px-2 py-2.5 rounded-xl border border-slate-100 bg-white text-xs font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-center shadow-sm">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center">Termin I</label>
                                                <input type="number" :name="`items[${i}][termin1]`" x-model="item.termin1" @input="recalc(i)" 
                                                    class="w-full px-2 py-2.5 rounded-xl border border-slate-100 bg-white text-xs font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-center shadow-sm">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center">Termin II</label>
                                                <input type="number" :name="`items[${i}][termin2]`" x-model="item.termin2" @input="recalc(i)" 
                                                    class="w-full px-2 py-2.5 rounded-xl border border-slate-100 bg-white text-xs font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-center shadow-sm">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center">Termin III</label>
                                                <input type="number" :name="`items[${i}][termin3]`" x-model="item.termin3" @input="recalc(i)" 
                                                    class="w-full px-2 py-2.5 rounded-xl border border-slate-100 bg-white text-xs font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-center shadow-sm">
                                            </div>
                                            <div class="space-y-1 col-span-2 lg:col-span-1">
                                                <label class="block text-[9px] font-black text-slate-400 uppercase tracking-tighter text-center">Termin IV</label>
                                                <input type="number" :name="`items[${i}][termin4]`" x-model="item.termin4" @input="recalc(i)" 
                                                    class="w-full px-2 py-2.5 rounded-xl border border-slate-100 bg-white text-xs font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-center shadow-sm">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Footer: Total & Status --}}
                                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-2">
                                        <div class="w-full md:w-auto flex items-center gap-4 bg-indigo-600 px-6 py-4 rounded-3xl text-white shadow-lg shadow-indigo-100">
                                            <span class="text-[9px] font-black uppercase tracking-widest opacity-80">Total Realisasi</span>
                                            <span class="text-lg font-mono font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.total)"></span>
                                        </div>
                                        <div class="w-full md:flex-1 space-y-2">
                                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Status Pekerjaan</label>
                                            <input type="text" :name="`items[${i}][status]`" x-model="item.status" 
                                                class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition"
                                                placeholder="Contoh: Selesai 100% / Dalam Proses...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('reports.belanja.modal.list') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
