@extends('layouts.admin')

@section('header', 'Berita Acara Penerimaan')
@section('subheader', 'Pratinjau & cetak')

@section('actions')
    <a href="{{ route('reports.penerimaan.list') }}" class="no-print btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    <button onclick="window.print()" class="no-print btn btn-neutral ml-2"><i class="fas fa-print"></i> Cetak</button>
@endsection

@section('content')
    <style>
        .preview-paper { 
            width: 210mm; 
            min-height: 330mm; 
            margin: 16px auto; 
            background: #fff; 
            padding: 10mm 15mm;
            line-height: 1.4;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        .preview-paper p { margin: 5px 0; }
        .preview-paper h2 { margin: 5px 0; }
        .preview-paper table { margin-top: 6px; }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: static !important; width: auto !important; overflow: visible !important; }
            @page { size: 210mm 330mm; margin: 5mm 15mm; }
            body { margin: 0; }
            .preview-paper { 
                width: 100% !important; 
                min-height: auto !important; 
                padding: 0 !important; 
                margin: 0 !important; 
                box-sizing: border-box; 
                background: #ffffff !important; 
                box-shadow: none !important; 
                line-height: 1.4;
            }
            .preview-paper p { margin: 5px 0; }
            .preview-paper h2 { margin: 5px 0; }
            .preview-paper table { margin-top: 6px; }
        }
        @media screen {
            html, body { background: #f3f4f6; }
            #print-area { width: 210mm; margin: 0 auto; }
            .preview-paper { width: 210mm; min-height: 330mm; margin: 16px auto; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 5mm 15mm; border-radius: 8px; }
        }
        .report-table { 
            border-collapse: collapse; 
            width: 100%; 
            table-layout: fixed;
        }
        .report-table th, .report-table td { 
            border: 1px solid #000; 
            padding: 4px; 
            font-size: 12px; 
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
        }
        .kop { margin-bottom: 10px; }
        .kop h1 { text-align: center; font-weight: 800; text-transform: uppercase; font-size: 16px; margin: 6px 0; }
    </style>

    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="preview-paper text-black">
            @include('partials.kop', ['opd' => $opd])

        <div class="text-center mb-2">
            <h2 class="font-extrabold text-lg">BERITA ACARA PENERIMAAN BARANG/PEKERJAAN</h2>
            <p class="text-sm">NOMOR: {{ $data['nomor'] ?? '' }}</p>
        </div>

        <p class="mb-3 text-sm">
            {{ $data['tanggal_kata'] ?? '' }}
        </p>

        <table class="w-full text-sm mb-3" style="table-layout: fixed;">
            <colgroup>
                <col style="width: 20%">
                <col style="width: 2%">
                <col style="width: 78%">
            </colgroup>
            <tr>
                <td class="align-top pl-6">Nama</td>
                <td class="align-top">:</td>
                <td class="align-top"><span class="font-bold">{{ $data['pengguna']['nama'] ?? '' }}</span></td>
            </tr>
            <tr>
                <td class="align-top pl-6">NIP</td>
                <td class="align-top">:</td>
                <td class="align-top">{{ $data['pengguna']['nip'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="align-top pl-6">Jabatan</td>
                <td class="align-top">:</td>
                <td class="align-top pr-2">{{ $data['pengguna']['jabatan'] ?? 'Pengurus Barang' }}</td>
            </tr>
        </table>

        <p class="mb-1 text-sm">Berdasarkan Berita Acara Pemeriksaan Barang Nomor: {{ $data['pemeriksaan_nomor'] ?? '-' }}. Telah menerima barang yang diserahkan oleh Pihak Ketiga sebagai berikut :</p>

        <table class="report-table items text-sm mb-3">
            <colgroup>
                <col style="width: 5%">
                <col style="width: 40%">
                <col style="width: 10%">
                <col style="width: 10%">
                <col style="width: 17%">
                <col style="width: 18%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Bahan/Alat (Barang)</th>
                    <th>Kuantitas</th>
                    <th>Satuan</th>
                    <th>Harga<br>Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $i=1; @endphp
                @foreach(($data['items'] ?? []) as $row)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $row['nama'] ?? '' }}</td>
                    <td class="text-center">{{ $row['kuantitas'] ?? '' }}</td>
                    <td class="text-center">{{ $row['satuan'] ?? '' }}</td>
                    <td class="text-right">Rp {{ number_format((int)($row['harga'] ?? 0), 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((int)($row['jumlah'] ?? 0), 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right font-bold">Jumlah</td>
                    <td class="text-right font-bold">Rp {{ number_format((int)($data['total'] ?? 0), 0, ',', '.') }}</td>
                    
                </tr>
                <tr>
                    <td colspan="6" class="text-center font-bold italic">Terbilang : {{ $data['terbilang'] ?? '' }} rupiah</td>
                </tr>
            </tbody>
            
        </table>

        

        <div class="grid grid-cols-2 gap-6 text-sm mt-8 mb-4">
            <div class="text-center">
                <div class="font-bold">Yang Menerima,</div>
                <div class="font-bold mb-12 uppercase">{{ $data['pengguna']['jabatan'] ?? 'Pengurus Barang' }}</div>
                <br>
                <div class="font-bold underline uppercase">{{ $data['pengguna']['nama'] ?? '' }}</div>
                <div>NIP: {{ $data['pengguna']['nip'] ?? '' }}</div>
            </div>
            <div class="text-center">
                <div class="font-bold">Mengetahui,</div>
                <div class="font-bold mb-12">Pejabat Pembuat Komitmen</div>
                <br>
                <div class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</div>
                <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
            </div>
        </div>
<br>
        <div class="text-center text-sm mt-4">
            <div class="font-bold mb-2">MENGETAHUI,</div>
            <div class="mb-12">PENGGUNA ANGGARAN SELAKU PPK</div>
            <br>
            <div class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</div>
            <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
        </div>
        </div>
    </div>
@endsection
