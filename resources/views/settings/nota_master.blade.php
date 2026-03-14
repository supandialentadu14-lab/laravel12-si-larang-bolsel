@extends('layouts.admin')

@section('header', 'Data Master Nota Pesanan')
@section('subheader', 'Isikan data pihak-pihak untuk prefill Nota Pesanan')

@section('content')
<div class="max-w-5xl mx-auto">
  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-colors duration-300">
    {{-- Form Header --}}
    <div class="px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50/50 to-white  transition-colors">
      <h6 class="font-bold text-slate-800 flex items-center gap-2 transition-colors">
        <i class="fas fa-file-signature text-indigo-500"></i> Form Edit Master Data Nota
      </h6>
    </div>

    <form action="{{ route('settings.nota.master.update') }}" method="POST" class="p-6 space-y-8">
      @csrf

      {{-- ── Identitas OPD ────────────────────────────────────────────── --}}
      <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 transition-colors">
        <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2 transition-colors">
          <i class="fas fa-building text-indigo-500"></i> Identitas OPD
        </h4>
        <div class="grid grid-cols-1 gap-6">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama OPD</label>
            <input type="text" name="opd[nama]" value="{{ old('opd.nama', $data['opd']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm font-semibold" placeholder="Contoh: DINAS KOMUNIKASI DAN INFORMATIKA">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Alamat OPD</label>
            <input type="text" name="opd[alamat]" value="{{ old('opd.alamat', $data['opd']['alamat'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm" placeholder="Contoh: Jalan Ir. Soekarno Komplek Perkantoran Panango">
          </div>
        </div>
      </div>

      {{-- ── PPK ──────────────────────────────────────────────────────── --}}
      <div class="bg-indigo-50/30 border border-indigo-100 rounded-xl p-6 transition-colors">
        <h4 class="text-indigo-900 font-bold mb-5 flex items-center gap-2 border-b border-indigo-100 pb-2 transition-colors">
          <i class="fas fa-user-tie text-indigo-500"></i> Pejabat Pembuat Komitmen (PPK)
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
            <input type="text" name="ppk[nama]" value="{{ old('ppk.nama', $data['ppk']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm" placeholder="Nama lengkap beserta gelar">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
            <input type="text" name="ppk[nip]" value="{{ old('ppk.nip', $data['ppk']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="Nomor Induk Pegawai">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Jabatan</label>
            <input type="text" name="ppk[jabatan]" value="{{ old('ppk.jabatan', $data['ppk']['jabatan'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm" placeholder="Jabatan">
          </div>
          <div class="md:col-span-2 lg:col-span-3">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Alamat</label>
            <input type="text" name="ppk[alamat]" value="{{ old('ppk.alamat', $data['ppk']['alamat'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition text-sm" placeholder="Alamat lengkap">
          </div>
        </div>
      </div>

      {{-- ── Pejabat Lainnya ────────────────────────────────────────────── --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Pejabat Pengadaan --}}
        <div class="bg-violet-50/30 border border-violet-100 rounded-xl p-6 transition-colors">
          <h4 class="text-violet-900 font-bold mb-5 flex items-center gap-2 border-b border-violet-100 pb-2 transition-colors">
            <i class="fas fa-briefcase text-violet-500"></i> Pejabat Pengadaan
          </h4>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
              <input type="text" name="pejabat[nama]" value="{{ old('pejabat.nama', $data['pejabat']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 outline-none transition text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
              <input type="text" name="pejabat[nip]" value="{{ old('pejabat.nip', $data['pejabat']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-violet-500/10 focus:border-violet-500 outline-none transition text-sm font-mono">
            </div>
          </div>
        </div>

        {{-- PPTK --}}
        <div class="bg-sky-50/30 border border-sky-100 rounded-xl p-6 transition-colors">
          <h4 class="text-sky-900 font-bold mb-5 flex items-center gap-2 border-b border-sky-100 pb-2 transition-colors">
            <i class="fas fa-user-check text-sky-500"></i> PPTK
          </h4>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
              <input type="text" name="pptk[nama]" value="{{ old('pptk.nama', $data['pptk']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
              <input type="text" name="pptk[nip]" value="{{ old('pptk.nip', $data['pptk']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-sky-500/10 focus:border-sky-500 outline-none transition text-sm font-mono">
            </div>
          </div>
        </div>

        {{-- Bendahara --}}
        <div class="bg-emerald-50/30 border border-emerald-100 rounded-xl p-6 transition-colors">
          <h4 class="text-emerald-900 font-bold mb-5 flex items-center gap-2 border-b border-emerald-100 pb-2 transition-colors">
            <i class="fas fa-coins text-emerald-500"></i> Bendahara
          </h4>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
              <input type="text" name="bendahara[nama]" value="{{ old('bendahara.nama', $data['bendahara']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
              <input type="text" name="bendahara[nip]" value="{{ old('bendahara.nip', $data['bendahara']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition text-sm font-mono">
            </div>
          </div>
        </div>

        {{-- Pengurus Barang --}}
        <div class="bg-orange-50/30 border border-orange-100 rounded-xl p-6 transition-colors">
          <h4 class="text-orange-900 font-bold mb-5 flex items-center gap-2 border-b border-orange-100 pb-2 transition-colors">
            <i class="fas fa-boxes-stacked text-orange-500"></i> Pengurus Barang
          </h4>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
              <input type="text" name="pengurus_barang[nama]" value="{{ old('pengurus_barang.nama', $data['pengurus_barang']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
              <input type="text" name="pengurus_barang[nip]" value="{{ old('pengurus_barang.nip', $data['pengurus_barang']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition text-sm font-mono">
            </div>
          </div>
        </div>

        {{-- Pengurus Barang Pengguna --}}
        <div class="bg-rose-50/30 border border-rose-100 rounded-xl p-6 md:col-span-2 transition-colors">
          <h4 class="text-rose-900 font-bold mb-5 flex items-center gap-2 border-b border-rose-100 pb-2 transition-colors">
            <i class="fas fa-user-gear text-rose-500"></i> Pengurus Barang Pengguna
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">Nama Lengkap</label>
              <input type="text" name="pengurus_pengguna[nama]" value="{{ old('pengurus_pengguna.nama', $data['pengurus_pengguna']['nama'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition text-sm">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 transition-colors">NIP</label>
              <input type="text" name="pengurus_pengguna[nip]" value="{{ old('pengurus_pengguna.nip', $data['pengurus_pengguna']['nip'] ?? '') }}" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-800 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition text-sm font-mono">
            </div>
          </div>
        </div>

      </div>

      {{-- ── Form Actions ────────────────────────────────────────────────── --}}
      <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end gap-3 transition-colors">
        <a href="{{ route('settings.nota.master.list') }}" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</a>
        <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-colors flex items-center gap-2">
          <i class="fas fa-save"></i> Simpan Data
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
