@extends('layouts.admin')
@section('header', 'Data Master Nota')
@section('content')

<div class="space-y-6">

    {{-- ── Hero Card ──────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-colors duration-300">
        {{-- Info banner --}}
        <div class="px-6 py-3 bg-amber-50 dark:bg-amber-900/10 border-b border-amber-100 dark:border-amber-900/30 flex items-center gap-2 text-xs text-amber-700 dark:text-amber-400 transition-colors">
            <i class="fas fa-info-circle text-amber-500"></i>
            <span>Data di bawah ini otomatis diisi pada setiap dokumen yang dicetak. Pastikan data sudah benar sebelum mencetak laporan.</span>
        </div>
    </div>

    {{-- ── PPK & Pejabat Pengadaan ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-master-card title="PPK" subtitle="Pejabat Pembuat Komitmen" icon="fa-user-tie" color="indigo">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-5">
                <div class="md:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['ppk']['nama'] ?? null" />
                </div>
                <div class="md:col-span-12">
                    <x-field-item label="NIP" :value="$data['ppk']['nip'] ?? null" mono />
                </div>
                <div class="md:col-span-12">
                    <x-field-item label="Jabatan" :value="$data['ppk']['jabatan'] ?? null" />
                </div>
                <div class="md:col-span-12">
                    <x-field-item label="Alamat" :value="$data['ppk']['alamat'] ?? null" />
                </div>
            </div>
        </x-master-card>

        <x-master-card title="Pejabat Pengadaan" subtitle="Pejabat yang bertanggung jawab atas pengadaan barang/jasa" icon="fa-briefcase" color="violet">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-5">
                <div class="md:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['pejabat']['nama'] ?? null" />
                </div>
                <div class="md:col-span-12">
                    <x-field-item label="NIP" :value="$data['pejabat']['nip'] ?? null" mono />
                </div>
            </div>
        </x-master-card>
    </div>



    {{-- ── PPTK + Bendahara ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-master-card title="PPTK" subtitle="Pejabat Pelaksana Teknis Kegiatan" icon="fa-user-check" color="sky">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-6 gap-y-5">
                <div class="lg:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['pptk']['nama'] ?? null" />
                </div>
                <div class="lg:col-span-12">
                    <x-field-item label="NIP" :value="$data['pptk']['nip'] ?? null" mono />
                </div>
            </div>
        </x-master-card>

        <x-master-card title="Bendahara" subtitle="Bendahara pengeluaran unit" icon="fa-coins" color="emerald">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-6 gap-y-5">
                <div class="lg:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['bendahara']['nama'] ?? null" />
                </div>
                <div class="lg:col-span-12">
                    <x-field-item label="NIP" :value="$data['bendahara']['nip'] ?? null" mono />
                </div>
            </div>
        </x-master-card>
    </div>

    {{-- ── Pengurus Barang + Pengurus Barang Pengguna ───────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-master-card title="Pengurus Barang Pengguna" subtitle="Pengurus barang milik daerah" icon="fa-boxes-stacked" color="orange">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-6 gap-y-5">
                <div class="lg:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['pengurus_barang']['nama'] ?? null" />
                </div>
                <div class="lg:col-span-12">
                    <x-field-item label="NIP" :value="$data['pengurus_barang']['nip'] ?? null" mono />
                </div>
            </div>
        </x-master-card>

        <x-master-card title="Pembantu Pengurus Barang" subtitle="Pembantu pengurus barang di unit pengguna" icon="fa-user-gear" color="rose">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-6 gap-y-5">
                <div class="lg:col-span-12">
                    <x-field-item label="Nama Lengkap" :value="$data['pengurus_pengguna']['nama'] ?? null" />
                </div>
                <div class="lg:col-span-12">
                    <x-field-item label="NIP" :value="$data['pengurus_pengguna']['nip'] ?? null" mono />
                </div>
            </div>
        </x-master-card>
    </div>

    {{-- ── Bottom Action ─────────────────────────────────────────────────── --}}
    <div class="flex justify-end pb-4">
        <a href="{{ route('settings.nota.master.edit') }}"
           class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-100 dark:shadow-none transition-all duration-200 hover:-translate-y-0.5">
            <i class="fas fa-pen-to-square"></i> Ubah Data Master Nota
        </a>
    </div>

</div>

@endsection
