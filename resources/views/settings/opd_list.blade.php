@extends('layouts.admin')

@section('header', 'Daftar OPD')
@section('subheader', 'Data OPD yang tersimpan')

@section('content')

<div class="space-y-6 max-w-6xl mx-auto">

  @forelse ($items as $item)
    {{-- ── Hero Card: Identitas Utama OPD ────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-colors duration-300">
      {{-- Header --}}
      <div class="px-5 sm:px-6 py-5 border-b border-slate-100 bg-gradient-to-r from-indigo-50/50 to-white  transition-colors">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6">
          <div class="flex items-center gap-4 w-full sm:w-auto">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center shadow-md shadow-indigo-100 shrink-0">
              <i class="fas fa-building text-white text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h2 class="text-base sm:text-lg font-bold text-slate-800 uppercase tracking-tight leading-snug break-words transition-colors">{{ $item->nama_opd }}</h2>
              @if($item->singkatan_opd)
                <div class="mt-1.5">
                  <span class="bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-widest inline-block transition-colors">{{ $item->singkatan_opd }}</span>
                </div>
              @endif
            </div>
          </div>
          <div class="w-full sm:w-auto mt-2 sm:mt-0">
            <a href="{{ route('settings.opd.edit') }}"
              class="flex justify-center items-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-100 transition-all duration-200 hover:-translate-y-0.5 whitespace-nowrap">
              <i class="fas fa-pen-to-square"></i> Edit OPD
            </a>
          </div>
        </div>

        {{-- Alamat --}}
        <div class="mt-4 bg-white/50 p-3 sm:p-4 rounded-xl border border-slate-200/60 flex items-start gap-3 shadow-[inset_0_1px_3px_rgba(0,0,0,0.02)] transition-colors">
          <i class="fas fa-map-marker-alt text-slate-400 mt-0.5 shrink-0"></i>
          <p class="text-[11px] sm:text-xs text-slate-600 leading-relaxed font-medium transition-colors">{{ $item->alamat_opd }}</p>
        </div>
      </div>

      {{-- Info banner --}}
      <div class="px-6 py-3 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-slate-500 transition-colors">
        <div class="flex items-center gap-2">
          <i class="fas fa-calendar-alt text-slate-400 "></i>
          <span>Tutup Buku: <strong class="text-slate-700 ">{{ $item->tutup_buku_date ? \Carbon\Carbon::parse($item->tutup_buku_date)->translatedFormat('d M Y') : 'Belum diatur' }}</strong></span>
        </div>
        <div class="flex items-center gap-2 mt-1 sm:mt-0">
          <i class="fas fa-clock text-slate-400 "></i>
          <span>Terakhir diupdate: <strong class="text-slate-700 ">{{ $item->updated_at?->translatedFormat('d M Y H:i') }}</strong></span>
        </div>
      </div>
      
      <div class="p-6 bg-slate-50/50 transition-colors">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          {{-- ── Kepala OPD ── --}}
          <x-master-card title="Kepala OPD" subtitle="Penanggung jawab instansi" icon="fa-user-tie" color="indigo">
            <div class="grid grid-cols-1 gap-y-5">
              <x-field-item label="Nama Lengkap" :value="$item->kepala_nama" />
              <x-field-item label="NIP" :value="$item->kepala_nip" mono />
              <x-field-item label="Jabatan" :value="$item->kepala_jabatan" />
              <x-field-item label="Pangkat / Gol" :value="$item->kepala_pangkat" />
            </div>
          </x-master-card>

          {{-- ── Pengurus Barang ── --}}
          <x-master-card title="Pengurus Barang" subtitle="Pengelola aset daerah" icon="fa-boxes-stacked" color="orange">
            <div class="grid grid-cols-1 gap-y-5">
              <x-field-item label="Nama Lengkap" :value="$item->pengurus_nama" />
              <x-field-item label="NIP" :value="$item->pengurus_nip" mono />
              <x-field-item label="Jabatan" :value="$item->pengurus_jabatan" />
              <x-field-item label="Pangkat / Gol" :value="$item->pengurus_pangkat" />
            </div>
          </x-master-card>

          {{-- ── Bendahara Barang ── --}}
          <x-master-card title="Bendahara Barang" subtitle="Pengurus barang pembantu" icon="fa-user-gear" color="rose">
            <div class="grid grid-cols-1 gap-y-5">
              <x-field-item label="Nama Lengkap" :value="$item->pengguna_nama" />
              <x-field-item label="NIP" :value="$item->pengguna_nip" mono />
              <x-field-item label="Jabatan" :value="$item->pengguna_jabatan" />
              <x-field-item label="Pangkat / Gol" :value="$item->pengguna_pangkat" />
            </div>
          </x-master-card>
        </div>
      </div>
    </div>

  @empty
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-12 text-center transition-colors">
      <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors">
        <i class="fas fa-building text-3xl text-slate-400 "></i>
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-2 transition-colors">Data OPD Belum Diatur</h3>
      <p class="text-slate-500 max-w-md mx-auto mb-6 transition-colors">Silakan atur identitas dan pejabat OPD untuk keperluan prefilling dokumen laporan, kwitansi, dan nota pesanan.</p>
      <a href="{{ route('settings.opd.edit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-100 transition-all duration-200 hover:-translate-y-0.5">
        <i class="fas fa-plus"></i> Atur Profil OPD
      </a>
    </div>
  @endforelse

</div>

@endsection
