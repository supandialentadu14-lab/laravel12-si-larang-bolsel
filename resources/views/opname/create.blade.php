@extends('layouts.admin')

@section('title', 'Berita Acara Stock Opname')
@section('header', 'Berita Acara Stock Opname Persediaan Barang Habis Pakai')

@section('content')
    <script>
        window.opnameForm = function () {
            return {
                items: {!! json_encode(($data['items'] ?? [])) !!},
                onDateChange() { this.updatePembuka(); },
                updatePembuka() {
                    try {
                        const v = this.$refs.tanggal?.value;
                        const tempat = this.$refs.tempat?.value || '-';
                        if (!v) return;
                        const parts = v.split('-');
                        const year = parseInt(parts[0], 10);
                        const monthIndex = parseInt(parts[1], 10) - 1;
                        const day = parseInt(parts[2], 10);
                        const d = new Date(year, monthIndex, day);
                        
                        const hariMap = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const bulanMap = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        
                        const hari = hariMap[d.getDay()];
                        const bulan = bulanMap[d.getMonth()];
                        const tanggal = day;
                        const tahun = year;
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
                        const cap = s => s.replace(/\b\w/g, c => c.toUpperCase());
                        this.$refs.pembuka.value =
                            `Pada hari ini ${hari} Tanggal ${cap(tanggalKata)} Bulan ${bulan} Tahun ${cap(tahunKata)}, yang bertanda tangan di bawah ini:`;
                    } catch (e) {}
                }
            }
        }
    </script>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-clipboard-check"></i> Form Berita Acara Opname
                </h6>
            </div>

            <form method="POST" action="{{ route('reports.opname.save') }}" x-data="opnameForm()" x-init="$nextTick(() => { updatePembuka(); })" class="p-6 space-y-6">
                @csrf
                @if(session('opname_current_id'))
                    <input type="hidden" name="id" value="{{ session('opname_current_id') }}">
                @endif
                @if(isset($data['id']))
                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                @endif

                <div class="bg-white dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 mb-6 shadow-sm transition-all duration-300">
                    <h4 class="text-slate-800 dark:text-slate-100 font-bold mb-6 flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Umum
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="space-y-1.5">
                            <label class="block text-xs sm:text-sm font-black text-black dark:text-white uppercase tracking-widest transition-colors duration-300">Nomor Surat <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor" value="{{ $data['nomor'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="001" required>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 italic">Masukkan nomor urut, otomatis diformat.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs sm:text-sm font-black text-black dark:text-white uppercase tracking-widest transition-colors duration-300">Tanggal <span class="text-rose-500">*</span></label>
                            <input x-ref="tanggal" @change="updatePembuka()" type="date" name="tanggal" value="{{ $data['tanggal'] ?? now()->toDateString() }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs sm:text-sm font-black text-black dark:text-white uppercase tracking-widest transition-colors duration-300">Tempat <span class="text-rose-500">*</span></label>
                            <input x-ref="tempat" @input="updatePembuka()" type="text" name="tempat" value="{{ $data['tempat'] ?? ($opd->nama_opd ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Boroko" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs sm:text-sm font-black text-black dark:text-white uppercase tracking-widest transition-colors duration-300">Narasi Pembuka</label>
                        <textarea x-ref="pembuka" name="pembuka" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm leading-relaxed">{{ $data['pembuka'] ?? '' }}</textarea>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 italic text-right">Otomatis terisi berdasarkan tanggal.</p>
                    </div>
                </div>

                <!-- Pihak Pihak -->
                <div class="bg-indigo-50/30 dark:bg-slate-950/40 p-6 rounded-2xl border border-indigo-100/50 dark:border-slate-800 transition-all duration-300 shadow-inner">
                    <h4 class="text-indigo-900 dark:text-indigo-400 font-bold mb-6 flex items-center gap-2 border-b border-indigo-100/50 dark:border-slate-800 pb-3">
                        <i class="fas fa-users text-indigo-500"></i> Pihak Yang Bertanda Tangan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                        <!-- Kolom Kiri: Pihak Pertama -->
                        <div class="space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                                <h3 class="font-black text-slate-800 dark:text-slate-200 text-xs uppercase tracking-widest border-l-4 border-indigo-500 pl-3">Pihak Pertama</h3>
                                @if(isset($opd) && $opd->kepala_nama)
                                    <button type="button" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider hover:bg-indigo-700 transition-all shadow-sm active:scale-95" @click="
                                        $refs.pp_nama.value='{{ $opd->kepala_nama }}';
                                        $refs.pp_nip.value='{{ $opd->kepala_nip }}';
                                        $refs.pp_jabatan.value='{{ $opd->kepala_jabatan }}';
                                    ">Cepat Isi Kepala OPD</button>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right transition-colors duration-300">Nama</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pp_nama" type="text" name="pihak_pertama[nama]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm shadow-sm" placeholder="Nama Lengkap">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right transition-colors duration-300">NIP</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pp_nip" type="text" name="pihak_pertama[nip]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono shadow-sm" placeholder="NIP">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-start">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right mt-2 transition-colors duration-300">Jabatan</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pp_jabatan" type="text" name="pihak_pertama[jabatan]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm shadow-sm" placeholder="Jabatan">
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Pihak Kedua -->
                        <div class="space-y-5 border-l-0 md:border-l border-slate-200 dark:border-slate-800 pl-0 md:pl-12">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                                <h3 class="font-black text-slate-800 dark:text-slate-200 text-xs uppercase tracking-widest border-l-4 border-emerald-500 pl-3">Pihak Kedua</h3>
                                @if(isset($opd) && $opd->pengurus_nama)
                                    <button type="button" class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider hover:bg-emerald-700 transition-all shadow-sm active:scale-95" @click="
                                        $refs.pk_nama.value='{{ $opd->pengurus_nama }}';
                                        $refs.pk_nip.value='{{ $opd->pengurus_nip }}';
                                        $refs.pk_jabatan.value='{{ $opd->pengurus_jabatan }}';
                                    ">Cepat Isi Pengurus</button>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right transition-colors duration-300">Nama</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pk_nama" type="text" name="pihak_kedua[nama]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm shadow-sm" placeholder="Nama Lengkap">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right transition-colors duration-300">NIP</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pk_nip" type="text" name="pihak_kedua[nip]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono shadow-sm" placeholder="NIP">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-start">
                                <label class="col-span-12 sm:col-span-3 text-xs sm:text-xs font-black text-black dark:text-white uppercase tracking-widest sm:text-right mt-2 transition-colors duration-300">Jabatan</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input x-ref="pk_jabatan" type="text" name="pihak_kedua[jabatan]" value="" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm shadow-sm" placeholder="Jabatan">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('reports.opname.list'),
                ])
            </form>
        </div>
    </div>
@endsection
