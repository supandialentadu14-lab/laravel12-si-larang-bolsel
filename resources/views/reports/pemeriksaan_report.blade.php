@extends('layouts.admin')

@section('title', 'Cetak Berita Acara Pemeriksaan')
@section('header', 'Berita Acara Pemeriksaan Barang/Pekerjaan')
@section('subheader', 'Pratinjau & cetak')

@section('actions')
    <a href="{{ route('reports.pemeriksaan.list') }}" class="no-print btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    <button onclick="window.print()" class="no-print btn btn-neutral ml-2"><i class="fas fa-print"></i> Print</button>
@endsection

@section('content')
    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="preview-paper">
        <div class="mb-4">
    <style>
        .preview-paper { 
            width: 210mm; 
            min-height: 330mm; 
            margin: 16px auto; 
            background-color: #ffffff !important; 
            color: #1e293b !important;
            padding: 10mm 15mm;
            line-height: 1.4;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Dark mode overrides */
        .theme-dark .preview-paper {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .theme-dark .preview-paper .border-b-4 {
            border-color: #475569 !important;
        }

        .preview-paper p { margin: 5px 0; }
        .preview-paper h2 { margin: 5px 0; }
        .preview-paper table { margin-top: 6px; }

        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: static !important; width: auto !important; overflow: visible !important; }
            @page { size: 210mm 330mm; margin: 5mm 15mm; }
            body { margin: 0; background-color: #ffffff !important; }
            .preview-paper { 
                width: 100% !important; 
                min-height: auto !important; 
                padding: 0 !important; 
                margin: 0 !important; 
                box-sizing: border-box; 
                background-color: #ffffff !important; 
                color: #000000 !important;
                box-shadow: none !important; 
                line-height: 1.4;
            }
            .preview-paper p { margin: 5px 0; }
            .preview-paper h2 { margin: 5px 0; }
            .preview-paper table { margin-top: 6px; }
            thead, tbody, tfoot, tr, th, td { background-color: #ffffff !important; border-color: #000000 !important; color: #000000 !important; }
            .border-b-4 { border-color: #000000 !important; }
        }

        @media screen {
            html, body { transition: background 0.3s ease; }
            .theme-dark html, .theme-dark body { background-color: #020617 !important; }
            #print-area { width: 210mm; margin: 0 auto; }
        }

        table.items { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; 
        }
        table.items td, table.items th { 
            border: 1px solid #1e293b; 
            word-wrap: break-word; 
            overflow-wrap: break-word;
            white-space: normal !important;
            padding: 4px;
        }
        .theme-dark table.items td, .theme-dark table.items th {
            border-color: #475569;
        }
        .info-table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
            vertical-align: top;
        }
    </style>
            @include('partials.kop', ['opd' => $opd])
        </div>
        
        <div class="text-center mb-4">
            <h2 class="font-extrabold text-lg">BERITA ACARA PEMERIKSAAN BARANG/PEKERJAAN</h2>
            <p class="text-sm">NOMOR: {{ $data['nomor'] ?? '' }}</p>
        </div>
        
        <p class="mb-3 text-sm">
            {{ $data['tanggal_kata'] ?? ('Pada hari ' . \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l') . ' tanggal ' . \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y')) }},
            kami yang bertanda tangan di bawah ini:
        </p>
        
        <div class="mb-4">
            <table class="w-full text-sm info-table" style="table-layout: fixed;">
                <colgroup>
                    <col style="width: 20%">
                    <col style="width: 2%">
                    <col style="width: 78%">
                </colgroup>
                <tr>
                    <td class="pl-6">Nama</td>
                    <td>:</td>
                    <td><span class="font-bold">{{ $data['ppk']['nama'] ?? '' }}</span></td>
                </tr>
                <tr>
                    <td class="pl-6">Jabatan</td>
                    <td>:</td>
                    <td>Pejabat Pembuat Komitmen</td>
                </tr>
                <tr>
                    <td class="pl-6">Alamat</td>
                    <td>:</td>
                    <td>{{ $data['ppk']['alamat'] ?? '' }}</td>
                </tr>
            </table>
        </div>
 
        @php
            $pekerjaan = $data['nota']['belanja'] ?? '';
        @endphp
        <p class="mb-1 text-sm">Menerangkan dengan benar bahwa Pihak Pertama telah menyerahkan pekerjaan : <span class="font-bold">{{ $pekerjaan }}</span></p>
        <table class="w-full text-sm mb-3 info-table" style="table-layout: fixed;">
            <colgroup>
                <col style="width: 25%">
                <col style="width: 2%">
                <col style="width: 73%">
            </colgroup>
            <tr>
                <td class="pl-6">Nama Penyedia Jasa</td>
                <td>:</td>
                <td class="font-bold">{{ $data['nota']['penyedia']['toko'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="pl-6 align-top">Alamat</td>
                <td class="align-top">:</td>
                <td>{{ $data['nota']['penyedia']['alamat'] ?? '' }}</td>
            </tr>
        </table>
        
        <p class="mb-3 text-sm">
            Sebagai realisasi Nota Pesanan Nomor : {{ $data['nota']['nomor'] ?? '-' }} tanggal {{ \Carbon\Carbon::parse($data['nota']['tanggal'] ?? now())->locale('id')->translatedFormat('d F Y') }},
            dengan jumlah/jenis daftar barang terlampir dan berkesimpulan bahwa barang/pekerjaan dapat diterima sesuai mestinya:
        </p>
        
        <div class="mb-4">
            <table class="items w-full text-xs border border-black">
                <colgroup>
                    <col style="width: 5%">
                    <col style="width: 35%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 10%">
                </colgroup>
                <thead>
                    <tr class="text-center font-bold">
                        <th class="px-1 py-1">No</th>
                        <th class="px-1 py-1">Jenis Bahan/Alat (Barang)</th>
                        <th class="px-1 py-1">Kuantitas</th>
                        <th class="px-1 py-1">Satuan</th>
                        <th class="px-1 py-1">Harga<br>Satuan</th>
                        <th class="px-1 py-1">Total</th>
                        <th class="px-1 py-1">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($data['items'] as $i => $item)
                        @php $total += (int)($item['jumlah'] ?? 0); @endphp
                        <tr>
                            <td class="px-2 py-1 text-center">{{ $i + 1 }}</td>
                            <td class="px-2 py-1">{{ $item['nama'] }}</td>
                            <td class="px-2 py-1 text-center">{{ $item['kuantitas'] }}</td>
                            <td class="px-2 py-1 text-center">{{ $item['satuan'] ?? '-' }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="px-2 py-1 text-right font-bold">Jumlah</td>
                        <td class="px-2 py-1 text-right font-bold">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="mb-3 text-sm text-center font-extrabold">Terbilang {{ \Illuminate\Support\Str::upper($data['terbilang'] ?? '') }} rupiah</td>

                    </tr>
                </tbody>
            </table>
        </div>
        
        <p </p>
        
        <p class="mb-3 text-sm">1. Barang Baik (V)</p>
        <p class="mb-6 text-sm">2. Barang Tidak Baik (X)</p>
        
        <div class="grid grid-cols-2 gap-6 mt-6">
            <div class="text-center">
                <p class="mb-1">Penyedia</p>
                <div class="h-24"></div>
                <p class="font-bold underline">{{ $data['nota']['penyedia']['toko'] ?? '' }}</p>
            </div>
            <div class="text-center">
                <p class="mb-1">Pejabat Pembuat Komitmen</p>
                <div class="h-24"></div>
                <p class="font-bold underline">{{ $data['ppk']['nama'] ?? '' }}</p>
                <p class="text-sm">NIP. {{ $data['ppk']['nip'] ?? '' }}</p>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="mb-1">MENGETAHUI,</p>
            <p class="mb-1">PENGGUNA ANGGARAN SELAKU PPK</p>
            <div class="h-24"></div>
            <p class="font-bold underline">{{ $data['ppk']['nama'] ?? '' }}</p>
            <p class="text-sm">NIP. {{ $data['ppk']['nip'] ?? '' }}</p>
        </div>
    </div>
@endsection
