@extends('layouts.admin')

@section('header', 'Data OPD')
@section('subheader', 'Digunakan untuk prefilling pada form dan laporan')

@section('content')
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transition-colors duration-300">
      <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
        <h6 class="font-bold text-white flex items-center gap-2">
          <i class="fas fa-building text-indigo-400"></i> Profil & Pengaturan OPD
        </h6>
      </div>
      <form action="{{ route('settings.opd.update') }}" method="POST" class="p-6 space-y-6">
        @csrf
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 transition-colors">
          <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2 transition-colors">
            <i class="fas fa-info-circle text-indigo-500"></i> Informasi Identitas OPD
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 transition-colors">Nama Lengkap OPD <span class="text-rose-500">*</span></label>
              <input type="text" name="nama_opd" value="{{ old('nama_opd', $setting->nama_opd) }}" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold uppercase" placeholder="Contoh: Dinas Komunikasi dan Informatika" required>
              <p class="text-[10px] text-slate-400 mt-1 italic leading-tight transition-colors">Nama ini akan tampil di bagian atas semua laporan.</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 transition-colors">Singkatan OPD</label>
              <input type="text" name="singkatan_opd" value="{{ old('singkatan_opd', $setting->singkatan_opd) }}" placeholder="Contoh: DISKOMINFO" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold">
            </div>
          </div>
          
          <div class="mb-6">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 transition-colors">Alamat Kantor OPD</label>
            <textarea name="alamat_opd" rows="3" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm leading-relaxed" placeholder="Contoh: Kompleks Perkantoran Pemerintah Daerah Bolsel, Boroko">{{ old('alamat_opd', $setting->alamat_opd) }}</textarea>
          </div>

          <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl transition-colors">
            <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-2 flex items-center gap-2 transition-colors">
              <i class="fas fa-calendar-times"></i> Batas Kunci Transaksi (Tutup Buku)
            </label>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
              <input type="date" name="tutup_buku_date" value="{{ old('tutup_buku_date', $setting->tutup_buku_date) }}" class="w-full sm:w-auto px-4 py-2 rounded-lg border border-rose-300 bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none transition text-rose-900 font-bold text-sm shadow-sm">
              <div class="flex-1">
                <p class="text-[11px] text-rose-600 font-medium leading-relaxed transition-colors">
                  Transaksi (Masuk/Keluar) yang bertanggal <b>sebelum atau sama dengan</b> tanggal ini akan <b>DIKUNCI otomatis</b> dan tidak bisa diubah/dihapus demi keamanan audit.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100 mb-6 transition-colors">
          <h4 class="text-indigo-900 font-bold mb-5 flex items-center gap-2 border-b border-indigo-100 pb-2 transition-colors">
            <i class="fas fa-user-tie text-indigo-500"></i> Pejabat Berwenang
          </h4>
          
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kepala OPD -->
            <div class="space-y-3">
              <h3 class="font-bold text-slate-700 text-[11px] uppercase tracking-wider bg-slate-200 px-2 py-1 rounded transition-colors">Kepala OPD</h3>
              <input type="text" name="kepala_nama" value="{{ old('kepala_nama', $setting->kepala_nama) }}" placeholder="Nama Lengkap" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="kepala_pangkat" value="{{ old('kepala_pangkat', $setting->kepala_pangkat) }}" placeholder="Pangkat/Golongan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="kepala_jabatan" value="{{ old('kepala_jabatan', $setting->kepala_jabatan) }}" placeholder="Jabatan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="kepala_nip" value="{{ old('kepala_nip', $setting->kepala_nip) }}" placeholder="NIP" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
            </div>

            <!-- Pengurus Barang -->
            <div class="space-y-3">
              <h3 class="font-bold text-slate-700 text-[11px] uppercase tracking-wider bg-slate-200 px-2 py-1 rounded transition-colors">Pengurus Barang</h3>
              <input type="text" name="pengurus_nama" value="{{ old('pengurus_nama', $setting->pengurus_nama) }}" placeholder="Nama Lengkap" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengurus_pangkat" value="{{ old('pengurus_pangkat', $setting->pengurus_pangkat) }}" placeholder="Pangkat/Golongan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengurus_jabatan" value="{{ old('pengurus_jabatan', $setting->pengurus_jabatan) }}" placeholder="Jabatan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengurus_nip" value="{{ old('pengurus_nip', $setting->pengurus_nip) }}" placeholder="NIP" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
            </div>

            <!-- Pengurus Barang Pembantu -->
            <div class="space-y-3">
              <h3 class="font-bold text-slate-700 text-[11px] uppercase tracking-wider bg-slate-200 px-2 py-1 rounded transition-colors">Bendahara Barang</h3>
              <input type="text" name="pengguna_nama" value="{{ old('pengguna_nama', $setting->pengguna_nama) }}" placeholder="Nama Lengkap" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengguna_pangkat" value="{{ old('pengguna_pangkat', $setting->pengguna_pangkat) }}" placeholder="Pangkat/Golongan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengguna_jabatan" value="{{ old('pengguna_jabatan', $setting->pengguna_jabatan) }}" placeholder="Jabatan" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
              <input type="text" name="pengguna_nip" value="{{ old('pengguna_nip', $setting->pengguna_nip) }}" placeholder="NIP" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-300 text-sm font-mono text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
            </div>
          </div>
        </div>

        

        @include('partials.form-actions', [
          'saveText' => 'Simpan Data OPD',
        ])
      </form>
    </div>
  </div>
@endsection
