@extends('layouts.admin')

@section('header', 'Pengaturan Profil & Penandatangan')
@section('subheader', 'Kelola identitas dinas dan daftar pejabat penandatangan laporan dalam satu tempat')

@section('content')
<div class="max-w-7xl mx-auto pb-12">
    <form action="{{ route('settings.opd.update') }}" method="POST" class="space-y-8">
        @csrf
        
        {{-- ── IDENTITAS OPD ────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden group">
            <div class="px-8 py-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-100">
                        <i class="fas fa-university text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">Identitas OPD</h2>
                        <p class="text-xs text-slate-400 font-medium">Informasi dasar organisasi perangkat daerah</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-full border border-indigo-100 uppercase tracking-wider">Wajib Diisi</span>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {{-- Left Col: Basic Info --}}
                    <div class="lg:col-span-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap OPD <span class="text-rose-500">*</span></label>
                                <div class="relative group/input">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within/input:text-indigo-600 text-slate-400">
                                    </div>
                                    <input type="text" name="nama_opd" value="{{ old('nama_opd', $setting->nama_opd) }}" 
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm font-bold uppercase placeholder:font-normal placeholder:lowercase" 
                                        placeholder="Contoh: Dinas Komunikasi dan Informatika" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Singkatan OPD</label>
                                <div class="relative group/input">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-600">
                                    </div>
                                    <input type="text" name="singkatan_opd" value="{{ old('singkatan_opd', $setting->singkatan_opd) }}" 
                                        class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm font-bold uppercase" 
                                        placeholder="Contoh: DISKOMINFO">
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Kantor</label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within/input:text-indigo-600">
                                </div>
                                <input type="text" name="alamat_opd" value="{{ old('alamat_opd', $setting->alamat_opd) }}" 
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm" 
                                    placeholder="Alamat lengkap kantor OPD...">
                            </div>
                        </div>
                    </div>

                    {{-- Right Col: Security/Status --}}
                    <div class="lg:col-span-4">
                        <div class="h-full bg-rose-50/50 border-2 border-dashed border-rose-200 rounded-3xl p-6 flex flex-col justify-between group-hover:border-rose-300 transition-colors">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-rose-500 flex items-center justify-center shadow-lg shadow-rose-200">
                                        <i class="fas fa-lock text-white text-sm"></i>
                                    </div>
                                    <h4 class="font-bold text-rose-800 text-sm tracking-tight tracking-wide">Tutup Buku Transaksi</h4>
                                </div>
                                <p class="text-[11px] text-rose-600 leading-relaxed font-medium">
                                    Sistem akan secara otomatis <b>mengunci</b> transaksi yang berumur lebih tua dari tanggal ini untuk alasan keamanan audit berkala.
                                </p>
                            </div>
                            <div class="mt-6">
                                <input type="date" name="tutup_buku_date" value="{{ old('tutup_buku_date', $setting->tutup_buku_date) }}" 
                                    class="w-full px-4 py-3 bg-white border-2 border-rose-200 rounded-2xl focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 outline-none transition-all font-bold text-rose-900 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── PEJABAT PENANDATANGAN ───────────────────────────────────────── --}}
        <div>
            <div class="flex items-center justify-between mb-6 px-2">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Pejabat Penandatangan</h2>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-slate-400">
                    <i class="fas fa-info-circle"></i>
                    Semua nama pejabat di bawah ini akan muncul pada dokumen & laporan
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {{-- KEPALA OPD --}}
                <x-master-card title="Kepala OPD" subtitle="Penanggung Jawab Utama" icon="fa-user-tie" color="indigo">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="kepala_nama" value="{{ old('kepala_nama', $setting->kepala_nama) }}" placeholder="Contoh: Nama, SH" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP / Identitas</label>
                            <input type="text" name="kepala_nip" value="{{ old('kepala_nip', $setting->kepala_nip) }}" placeholder="NIP: 198..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pangkat</label>
                                <input type="text" name="kepala_pangkat" value="{{ old('kepala_pangkat', $setting->kepala_pangkat) }}" placeholder="Pembina..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jabatan</label>
                                <input type="text" name="kepala_jabatan" value="{{ old('kepala_jabatan', $setting->kepala_jabatan) }}" placeholder="Kepala Dinas" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>
                </x-master-card>

                {{-- PPK --}}
                <x-master-card title="PPK" subtitle="Pejabat Pembuat Komitmen" icon="fa-file-signature" color="violet">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="ppk_nama" value="{{ old('ppk_nama', $notaMaster->ppk_nama) }}" placeholder="Nama Lengkap PPK" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="ppk_nip" value="{{ old('ppk_nip', $notaMaster->ppk_nip) }}" placeholder="NIP PPK" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-violet-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Penugasan</label>
                            <input type="text" name="ppk_alamat" value="{{ old('ppk_alamat', $notaMaster->ppk_alamat) }}" placeholder="Alamat Kantor PPK" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-violet-500 transition-all">
                        </div>
                    </div>
                </x-master-card>

                {{-- PEJABAT PENGADAAN --}}
                <x-master-card title="Pejabat Pengadaan" subtitle="Staf Teknis Pengadaan" icon="fa-briefcase" color="emerald">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="pejabat_nama" value="{{ old('pejabat_nama', $notaMaster->pejabat_nama) }}" placeholder="Nama Pejabat Pengadaan" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="pejabat_nip" value="{{ old('pejabat_nip', $notaMaster->pejabat_nip) }}" placeholder="NIP Pejabat" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-emerald-500 transition-all">
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 mt-2">
                            <p class="text-[11px] text-emerald-700 leading-relaxed italic">
                                Mencakup pejabat yang menandatangani B.A Pemeriksaan Barang.
                            </p>
                        </div>
                    </div>
                </x-master-card>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                {{-- PPTK --}}
                <x-master-card title="PPTK" subtitle="Pejabat Pelaksana Teknis Kegiatan" icon="fa-tasks" color="sky">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="pptk_nama" value="{{ old('pptk_nama', $notaMaster->pptk_nama) }}" placeholder="Nama PPTK" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="pptk_nip" value="{{ old('pptk_nip', $notaMaster->pptk_nip) }}" placeholder="NIP PPTK" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-sky-500 transition-all">
                        </div>
                    </div>
                </x-master-card>

                {{-- BENDAHARA --}}
                <x-master-card title="Bendahara" subtitle="Pengelola Keuangan" icon="fa-coins" color="rose">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="bendahara_nama" value="{{ old('bendahara_nama', $notaMaster->bendahara_nama) }}" placeholder="Nama Bendahara" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="bendahara_nip" value="{{ old('bendahara_nip', $notaMaster->bendahara_nip) }}" placeholder="NIP Bendahara" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-rose-500 transition-all">
                        </div>
                    </div>
                </x-master-card>

                {{-- PENGURUS BARANG --}}
                <x-master-card title="Pengurus Barang Pengguna" subtitle="Pengelola Inventaris Utama" icon="fa-boxes-packing" color="orange">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="pengurus_nama" value="{{ old('pengurus_nama', $setting->pengurus_nama) }}" placeholder="Nama Pengurus" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="pengurus_nip" value="{{ old('pengurus_nip', $setting->pengurus_nip) }}" placeholder="NIP Pengurus" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-orange-500 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pangkat</label>
                                <input type="text" name="pengurus_pangkat" value="{{ old('pengurus_pangkat', $setting->pengurus_pangkat) }}" placeholder="Pembina..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-orange-500 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jabatan</label>
                                <input type="text" name="pengurus_jabatan" value="{{ old('pengurus_jabatan', $setting->pengurus_jabatan) }}" placeholder="Pengurus Barang" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-orange-500 transition-all">
                            </div>
                        </div>
                    </div>
                </x-master-card>

                {{-- PENGGUNA BARANG --}}
                <x-master-card title="Pejabat Penatausahaan" subtitle="Seksi Pengelola Inventaris" icon="fa-user-shield" color="indigo">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="pengguna_nama" value="{{ old('pengguna_nama', $setting->pengguna_nama) }}" placeholder="Nama Atasan" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">NIP</label>
                            <input type="text" name="pengguna_nip" value="{{ old('pengguna_nip', $setting->pengguna_nip) }}" placeholder="NIP Atasan" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pangkat</label>
                                <input type="text" name="pengguna_pangkat" value="{{ old('pengguna_pangkat', $setting->pengguna_pangkat) }}" placeholder="Penata..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jabatan</label>
                                <input type="text" name="pengguna_jabatan" value="{{ old('pengguna_jabatan', $setting->pengguna_jabatan) }}" placeholder="Pembantu Pengurus" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-[13px] focus:bg-white focus:border-indigo-500 transition-all">
                            </div>
                        </div>
                    </div>
                </x-master-card>
            </div>
        </div>

        {{-- ── FORM ACTIONS ────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-10 border-t border-slate-100">
            <button type="reset" class="w-full sm:w-auto px-8 py-3 rounded-2xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-undo"></i> Reset Perubahan
            </button>
            <button type="submit" class="w-full sm:w-auto px-10 py-3.5 rounded-2xl font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 shadow-2xl shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                <i class="fas fa-cloud-upload-alt text-indigo-300"></i> Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
