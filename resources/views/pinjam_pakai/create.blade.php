@extends('layouts.admin')

@section('title', 'Berita Acara Pinjam Pakai')
@section('header', 'Berita Acara Pinjam Pakai')

@section('content')
    <script>
        window.formData = function () {
            return {
                items: [{ _key: 1 }],
                nextKey: 2,
                ensureKeys() {
                    const withKeys = (this.items || []).map(it => ({ ...it, _key: it._key || (this.nextKey++) }));
                    this.items = withKeys;
                },
                init() {
                    this.ensureKeys();
                    this.updatePembuka();
                },
                prefill() { this.items = {!! json_encode(($data['items'] ?? [])) !!}; this.ensureKeys(); },
                addItem() { this.items.push({ _key: this.nextKey++ }); },
                removeItem(i) { this.items.splice(i, 1); },
                updatePembuka() {
                    try {
                        const v = this.$refs.tanggal?.value;
                        const tempat = this.$refs.tempat?.value || '-';
                        if (!v) return;
                        const d = new Date(v);
                        const hari = d.toLocaleDateString('id-ID', { weekday: 'long' });
                        const bulan = d.toLocaleDateString('id-ID', { month: 'long' });
                        const tanggal = d.getDate();
                        const tahun = d.getFullYear();
                        const toWords = (n) => {
                            n = parseInt(n, 10);
                            const h = ["","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas"];
                            const cap = s => s.replace(/\b\w/g, c => c.toUpperCase());
                            const w = (v) => {
                                if (v < 12) return h[v];
                                if (v < 20) return w(v-10) + " belas";
                                if (v < 100) return w(Math.floor(v/10)) + " puluh " + w(v%10);
                                if (v < 200) return "seratus " + w(v-100);
                                if (v < 1000) return w(Math.floor(v/100)) + " ratus " + w(v%100);
                                if (v < 2000) return "seribu " + w(v-1000);
                                if (v < 1000000) return w(Math.floor(v/1000)) + " ribu " + w(v%1000);
                                if (v < 1000000000) return w(Math.floor(v/1000000)) + " juta " + w(v%1000000);
                                return String(v);
                            };
                            return cap(w(n).trim());
                        };
                        const tanggalKata = toWords(tanggal);
                        const tahunKata = toWords(tahun);
                        this.$refs.pembuka.value =
                            `Pada hari ini ${hari} Tanggal ${tanggalKata} Bulan ${bulan} Tahun ${tahunKata}, yang bertanda tangan di bawah ini:`;
                    } catch (e) {}
                }
            }
        }
    </script>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-hand-holding"></i> Form Berita Acara Pinjam Pakai
                </h6>
            </div>

            <form method="POST" action="{{ route('reports.pinjam.save') }}" x-data="formData()" x-init="init()" class="p-6 space-y-6">
                @csrf
                @if(session('pinjam_current_id'))
                    <input type="hidden" name="id" value="{{ session('pinjam_current_id') }}">
                @endif
                @if(isset($data['id']))
                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                @endif

                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Umum
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                       <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor" value="{{ $data['nomor'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="Contoh: 001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Berita Acara <span class="text-rose-500">*</span></label>
                            <input x-ref="tanggal" @change="updatePembuka()" type="date" name="tanggal" value="{{ $data['tanggal'] ?? now()->toDateString() }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tempat Penandatanganan <span class="text-rose-500">*</span></label>
                            <input x-ref="tempat" @input="updatePembuka()" type="text" name="tempat" value="{{ $data['tempat'] ?? 'Bolaang Uki' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Nama Tempat" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Narasi Pembuka</label>
                        <textarea x-ref="pembuka" name="pembuka" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm leading-relaxed" placeholder="Narasi ini akan terisi otomatis berdasarkan tanggal..."></textarea>
                        <p class="text-[10px] text-slate-400 mt-1 italic text-right leading-tight">Narasi pembuka diperbarui otomatis berdasarkan input Tanggal.</p>
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100 mb-6">
                    <h4 class="text-indigo-900 font-bold mb-5 flex items-center gap-2 border-b border-indigo-100 pb-2">
                        <i class="fas fa-users text-indigo-500"></i> Pihak Yang Bersepakat
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Pihak Pertama -->
                        <div class="space-y-4">
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2 mb-4">
                                <h3 class="font-bold text-indigo-800 text-xs uppercase tracking-wider">PIHAK PERTAMA (Pemberi)</h3>
                                @if(isset($opd) && $opd->kepala_nama)
                                    <button type="button" class="px-2 py-1 rounded bg-indigo-600 text-white text-[10px] font-bold hover:bg-indigo-700 transition" @click="
                                        $refs.pp_nama.value='{{ $opd->kepala_nama }}';
                                        $refs.pp_nip.value='{{ $opd->kepala_nip }}';
                                        $refs.pp_jabatan.value='{{ $opd->kepala_jabatan }}';
                                    ">Cepat Isi Kepala OPD</button>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <label class="col-span-12 sm:col-span-3 text-[11px] font-bold text-indigo-500 uppercase tracking-tighter text-right hidden sm:block">Nama</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pp_nama" type="text" name="pihak_pertama[nama]" value="{{ $data['pihak_pertama']['nama'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <label class="col-span-12 sm:col-span-3 text-[11px] font-bold text-indigo-500 uppercase tracking-tighter text-right hidden sm:block">NIP</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pp_nip" type="text" name="pihak_pertama[nip]" value="{{ $data['pihak_pertama']['nip'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3 items-start">
                                <label class="col-span-12 sm:col-span-3 mt-2 text-[11px] font-bold text-indigo-500 uppercase tracking-tighter text-right hidden sm:block">Jabatan</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea x-ref="pp_jabatan" name="pihak_pertama[jabatan]" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm leading-snug" required>{{ $data['pihak_pertama']['jabatan'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pihak Kedua -->
                        <div class="space-y-4 border-l border-indigo-100 pl-4 md:pl-8">
                            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-2 mb-4">
                                <h3 class="font-bold text-rose-800 text-xs uppercase tracking-wider">PIHAK KEDUA (Peminjam)</h3>
                            </div>
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <label class="col-span-12 sm:col-span-3 text-[11px] font-bold text-rose-500 uppercase tracking-tighter text-right hidden sm:block">Nama</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input type="text" name="pihak_kedua[nama]" value="{{ $data['pihak_kedua']['nama'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition text-sm font-bold" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3 items-center">
                                <label class="col-span-12 sm:col-span-3 text-[11px] font-bold text-rose-500 uppercase tracking-tighter text-right hidden sm:block">NIP</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input type="text" name="pihak_kedua[nip]" value="{{ $data['pihak_kedua']['nip'] ?? '' }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition text-sm font-mono" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-3 items-start">
                                <label class="col-span-12 sm:col-span-3 mt-2 text-[11px] font-bold text-rose-500 uppercase tracking-tighter text-right hidden sm:block">Jabatan</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea name="pihak_kedua[jabatan]" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition text-sm leading-snug" required>{{ $data['pihak_kedua']['jabatan'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5 border-b border-slate-200 pb-3">
                        <h4 class="text-slate-800 font-bold flex items-center gap-2">
                             <i class="fas fa-boxes text-indigo-500"></i> Daftar Barang Pinjam Pakai
                        </h4>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="button" @click="prefill()" class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 hover:bg-emerald-100 transition flex items-center justify-center gap-1">
                                <i class="fas fa-sync"></i> Ambil Ulang Data
                            </button>
                            <button type="button" @click="addItem()" class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition flex items-center justify-center gap-1">
                                <i class="fas fa-plus"></i> Tambah Baris
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl bg-white">
                        <table class="w-full text-[11px] text-left text-slate-700">
                            <thead class="bg-slate-100 text-[10px] uppercase font-bold text-slate-600 tracking-wider">
                                <tr>
                                    <th class="px-3 py-3 border-b border-slate-200">Nama / Jenis Barang</th>
                                    <th class="px-3 py-3 border-b border-slate-200">Merk</th>
                                    <th class="px-3 py-3 border-b border-slate-200">Tipe</th>
                                    <th class="px-3 py-3 border-b border-slate-200">No. Pabrik / Identitas</th>
                                    <th class="px-3 py-3 border-b border-slate-200 w-20 text-center">Tahun</th>
                                    <th class="px-3 py-3 border-b border-slate-200 w-24 text-center">Kondisi</th>
                                    <th class="px-3 py-3 border-b border-slate-200 w-20 text-center">Jumlah</th>
                                    <th class="px-3 py-3 border-b border-slate-200 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, i) in items" :key="item._key">
                                    <tr class="hover:bg-indigo-50/30 transition">
                                        <td class="p-2"><input type="text" :name="`items[${i}][nama]`" x-model="item.nama" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="Nama Barang"></td>
                                        <td class="p-2"><input type="text" :name="`items[${i}][merk]`" x-model="item.merk" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="Merk"></td>
                                        <td class="p-2"><input type="text" :name="`items[${i}][tipe]`" x-model="item.tipe" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="Tipe"></td>
                                        <td class="p-2"><input type="text" :name="`items[${i}][identitas]`" x-model="item.identitas" class="w-full rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="No. Seri"></td>
                                        <td class="p-2 text-center"><input type="text" :name="`items[${i}][tahun]`" x-model="item.tahun" class="w-full text-center rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="2024"></td>
                                        <td class="p-2 text-center"><input type="text" :name="`items[${i}][kondisi]`" x-model="item.kondisi" class="w-full text-center rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition" placeholder="Baik"></td>
                                        <td class="p-2 text-center"><input type="text" :name="`items[${i}][jumlah]`" x-model="item.jumlah" class="w-full text-center rounded-md border border-slate-300 bg-white text-[11px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 transition font-bold" placeholder="1"></td>
                                        <td class="p-2 text-center">
                                            <button type="button" @click="removeItem(i)" class="text-rose-400 hover:text-rose-600 transition p-1"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('reports.pinjam.list'),
                ])
            </form>
        </div>
    </div>
@endsection
