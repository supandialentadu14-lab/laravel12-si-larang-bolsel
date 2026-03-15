@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6 animate-slide-up pb-32">
  {{-- Page Header --}}
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Instansi</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Profil & Penandatangan</p>
    </div>
    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
      <i class="fas fa-university text-xs"></i>
    </div>
  </div>

  {{-- User Profile Card --}}
  <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-100">
      {{ substr(Auth::user()->name, 0, 1) }}
    </div>
    <div class="flex-1 min-w-0">
      <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate leading-tight">{{ Auth::user()->name }}</h3>
      <p class="text-[9px] font-bold text-slate-400 lowercase tracking-widest mt-1 truncate">{{ strtolower(Auth::user()->email) }}</p>
    </div>
  </div>

  {{-- Settings Form --}}
  <form action="{{ route('settings.opd.update') }}" method="POST" class="space-y-6">
    @csrf

    {{-- Identitas OPD --}}
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
      <div class="flex items-center justify-between border-b border-slate-50 pb-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-university text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest leading-none">Identitas OPD</h3>
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Organisasi Perangkat Daerah</p>
          </div>
        </div>
        <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-[8px] font-black rounded-lg border border-indigo-100 uppercase tracking-widest">Wajib</span>
      </div>

      <div class="space-y-5">
        <div class="space-y-2">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Lengkap OPD <span class="text-rose-500">*</span></label>
          <input type="text" name="nama_opd" value="{{ old('nama_opd', $setting->nama_opd) }}" placeholder="Contoh: Dinas Komunikasi dan Informatika" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none uppercase" required>
        </div>

        <div class="space-y-2">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Singkatan OPD</label>
          <input type="text" name="singkatan_opd" value="{{ old('singkatan_opd', $setting->singkatan_opd) }}" placeholder="Contoh: DISKOMINFO" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none uppercase">
        </div>

        <div class="space-y-2">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Alamat Kantor</label>
          <textarea name="alamat_opd" rows="3" placeholder="Alamat lengkap kantor OPD..." class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed">{{ old('alamat_opd', $setting->alamat_opd) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Tutup Buku Card --}}
    <div class="bg-rose-500 rounded-[2.5rem] p-6 text-white shadow-xl shadow-rose-100 space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
          <i class="fas fa-lock text-xs"></i>
        </div>
        <div>
          <h3 class="text-[11px] font-black uppercase tracking-widest leading-none">Kunci Transaksi</h3>
          <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1">Audit Keamanan Berkala</p>
        </div>
      </div>
      <p class="text-[9px] font-bold opacity-90 uppercase leading-relaxed tracking-widest">Sistem akan secara otomatis <b>mengunci</b> transaksi yang berumur lebih tua dari tanggal ini.</p>
      <input type="date" name="tutup_buku_date" value="{{ old('tutup_buku_date', $setting->tutup_buku_date) }}" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold placeholder:text-white/40 outline-none">
    </div>

    {{-- Pejabat Penandatangan --}}
    <div class="space-y-4">
      <div class="px-4 flex items-center justify-between">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pejabat Penandatangan</h3>
        <i class="fas fa-info-circle text-slate-300 text-[10px]" title="Muncul pada dokumen & laporan"></i>
      </div>
      
      {{-- KEPALA OPD --}}
      <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-user-tie text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">Kepala OPD</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Penanggung Jawab Utama</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="kepala_nama" value="{{ old('kepala_nama', $setting->kepala_nama) }}" placeholder="Contoh: Nama Lengkap, SH" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP / Identitas</label>
            <input type="text" name="kepala_nip" value="{{ old('kepala_nip', $setting->kepala_nip) }}" placeholder="NIP: 198..." class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Pangkat</label>
              <input type="text" name="kepala_pangkat" value="{{ old('kepala_pangkat', $setting->kepala_pangkat) }}" placeholder="Pembina..." class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Jabatan</label>
              <input type="text" name="kepala_jabatan" value="{{ old('kepala_jabatan', $setting->kepala_jabatan) }}" placeholder="Kepala Dinas" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
          </div>
        </div>
      </div>

      {{-- PPK --}}
      <div class="bg-violet-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-violet-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-file-signature text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">PPK</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Pejabat Pembuat Komitmen</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="ppk_nama" value="{{ old('ppk_nama', $notaMaster->ppk_nama) }}" placeholder="Nama Lengkap PPK" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="ppk_nip" value="{{ old('ppk_nip', $notaMaster->ppk_nip) }}" placeholder="NIP PPK" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Alamat Penugasan</label>
            <input type="text" name="ppk_alamat" value="{{ old('ppk_alamat', $notaMaster->ppk_alamat) }}" placeholder="Alamat Kantor PPK" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
        </div>
      </div>

      {{-- PEJABAT PENGADAAN --}}
      <div class="bg-emerald-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-emerald-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-briefcase text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">Pejabat Pengadaan</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Staf Teknis Pengadaan</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="pejabat_nama" value="{{ old('pejabat_nama', $notaMaster->pejabat_nama) }}" placeholder="Nama Pejabat Pengadaan" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="pejabat_nip" value="{{ old('pejabat_nip', $notaMaster->pejabat_nip) }}" placeholder="NIP Pejabat" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="bg-white/10 p-4 rounded-2xl">
            <p class="text-[9px] text-white leading-relaxed font-bold uppercase tracking-widest opacity-80">
              <i class="fas fa-info-circle mr-1"></i> Mencakup pejabat yang menandatangani B.A Pemeriksaan Barang.
            </p>
          </div>
        </div>
      </div>

      {{-- PPTK --}}
      <div class="bg-sky-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-sky-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-tasks text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">PPTK</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Pejabat Pelaksana Teknis Kegiatan</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="pptk_nama" value="{{ old('pptk_nama', $notaMaster->pptk_nama) }}" placeholder="Nama PPTK" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="pptk_nip" value="{{ old('pptk_nip', $notaMaster->pptk_nip) }}" placeholder="NIP PPTK" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
        </div>
      </div>

      {{-- BENDAHARA --}}
      <div class="bg-rose-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-rose-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-coins text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">Bendahara</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Pengelola Keuangan</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="bendahara_nama" value="{{ old('bendahara_nama', $notaMaster->bendahara_nama) }}" placeholder="Nama Bendahara" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="bendahara_nip" value="{{ old('bendahara_nip', $notaMaster->bendahara_nip) }}" placeholder="NIP Bendahara" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
        </div>
      </div>

      {{-- PENGURUS BARANG --}}
      <div class="bg-orange-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-orange-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-boxes-packing text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">Pengurus Barang</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Pengelola Inventaris Utama</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="pengurus_nama" value="{{ old('pengurus_nama', $setting->pengurus_nama) }}" placeholder="Nama Pengurus" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="pengurus_nip" value="{{ old('pengurus_nip', $setting->pengurus_nip) }}" placeholder="NIP Pengurus" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Pangkat</label>
              <input type="text" name="pengurus_pangkat" value="{{ old('pengurus_pangkat', $setting->pengurus_pangkat) }}" placeholder="Pembina..." class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Jabatan</label>
              <input type="text" name="pengurus_jabatan" value="{{ old('pengurus_jabatan', $setting->pengurus_jabatan) }}" placeholder="Pengurus Barang" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nomor SK Pengurus</label>
            <input type="text" name="pengurus_sk" value="{{ old('pengurus_sk', $setting->pengurus_sk) }}" placeholder="27 Tahun 2026" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
        </div>
      </div>

      {{-- PENGGUNA BARANG --}}
      <div class="bg-blue-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-blue-100 space-y-6">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
          <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center">
            <i class="fas fa-user-shield text-xs"></i>
          </div>
          <div>
            <h3 class="text-[11px] font-black uppercase tracking-widest leading-none text-white">Pejabat Penatausahaan</h3>
            <p class="text-[8px] font-bold opacity-70 uppercase tracking-widest mt-1 text-white">Seksi Pengelola Inventaris</p>
          </div>
        </div>
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Nama Lengkap</label>
            <input type="text" name="pengguna_nama" value="{{ old('pengguna_nama', $setting->pengguna_nama) }}" placeholder="Nama Atasan / Pejabat Penatausahaan" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="space-y-1.5">
            <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">NIP</label>
            <input type="text" name="pengguna_nip" value="{{ old('pengguna_nip', $setting->pengguna_nip) }}" placeholder="NIP Atasan" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Pangkat</label>
              <input type="text" name="pengguna_pangkat" value="{{ old('pengguna_pangkat', $setting->pengguna_pangkat) }}" placeholder="Penata..." class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black opacity-70 uppercase tracking-widest ml-4 text-white">Jabatan</label>
              <input type="text" name="pengguna_jabatan" value="{{ old('pengguna_jabatan', $setting->pengguna_jabatan) }}" placeholder="Pembantu Pengurus" class="w-full px-6 py-4 bg-white/10 border-none rounded-2xl text-[10px] font-bold focus:ring-2 focus:ring-white/20 outline-none text-white placeholder:text-white/30">
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Submit Button --}}
    <div class="px-2">
      <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">
        Simpan Semua Pengaturan
      </button>
    </div>
  </form>
</div>
@endsection
