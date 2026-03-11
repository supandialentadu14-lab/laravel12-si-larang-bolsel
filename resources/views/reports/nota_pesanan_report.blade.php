@extends('layouts.admin')

@section('header', 'Nota Pesanan')
@section('content')
    <style>
        .preview-paper { 
            width: 210mm; 
            min-height: 330mm; 
            margin: 16px auto; 
            background: #ffffff; 
            padding: 10mm 15mm;
            line-height: 1.4;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-radius: 8px;
        }
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: static !important;
                width: auto !important;
                overflow: visible !important;
            }

            @page {
                size: 210mm 330mm;
                margin: 5mm 15mm;
            }
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
        }

        @media screen {
            html, body { background: #f3f4f6; }
            #print-area { width: 210mm; margin: 0 auto; }
            .preview-paper { width: 210mm; min-height: 330mm; margin: 16px auto; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 5mm 15mm; border-radius: 8px; }
        }

        .kop {
            width: 100%;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: top;
        }

        .kop-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text .line1 {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        .kop-text .line2 {
            font-size: 15px;
            font-weight: 700;
            margin-top: 0.1px;
        }

        .kop-text .line3 {
            font-size: 12px;
            margin-top: 0.1px;
        }

        .kop-text .line4 {
            font-size: 12px;
        }

        .report-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid black;
            padding: 6px;
            font-size: 12px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
        }

        .report-table th {
            text-align: center;
            font-weight: bold;
        }

        .kop {
            margin-bottom: 10px;
        }

        .kop h1 {
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 16px;
            margin: 6px 0;
        }

        .bold {
            font-weight: 700;
        }

        .rules {
            padding-left: 20px;
            margin-left: 6px;
        }

        .rules li {
            margin: 2px 0;
            line-height: 1.4;
        }

        .italic {
            font-style: italic;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            padding: 0;
            vertical-align: top;
            font-size: 12px;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
        }

        .header-table .label {
            width: 15%;
            font-weight: 700;
        }

        .header-table .colon {
            width: 2%;
        }

        .header-table .spacer {
            width: 10%;
        }

        .header-table .content {
            width: 43%;
        }

        .header-table .city {
            width: 30%;
            text-align: left;
        }

        .header-table .date {
            width: 0%;
            text-align: left;
        }

        /*   */
    </style>

    <div class="bg-white rounded-lg shadow p-6 mb-6 print:hidden flex gap-2">
        <a href="{{ route('reports.nota.list') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold shadow flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="button" onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-bold shadow">
            <i class="fas fa-print mr-2"></i> Print
        </button>
    </div>

    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="preview-paper">
            @php
            function toWordsId($value)
            {
                $huruf = [
                    '',
                    'satu',
                    'dua',
                    'tiga',
                    'empat',
                    'lima',
                    'enam',
                    'tujuh',
                    'delapan',
                    'sembilan',
                    'sepuluh',
                    'sebelas',
                ];
                $value = intval($value);
                if ($value < 12) {
                    return $huruf[$value];
                }
                if ($value < 20) {
                    return toWordsId($value - 10) . ' belas';
                }
                if ($value < 100) {
                    return toWordsId(intval($value / 10)) . ' puluh ' . toWordsId($value % 10);
                }
                if ($value < 200) {
                    return 'seratus ' . toWordsId($value - 100);
                }
                if ($value < 1000) {
                    return toWordsId(intval($value / 100)) . ' ratus ' . toWordsId($value % 100);
                }
                if ($value < 2000) {
                    return 'seribu ' . toWordsId($value - 1000);
                }
                if ($value < 1000000) {
                    return toWordsId(intval($value / 1000)) . ' ribu ' . toWordsId($value % 1000);
                }
                if ($value < 1000000000) {
                    return toWordsId(intval($value / 1000000)) . ' juta ' . toWordsId($value % 1000000);
                }
                return (string) $value;
            }
        @endphp

        {{-- KOP Surat reusable --}}
        @include('partials.kop', ['opd' => $opd])
        <table class="header-table mb-4">
            <td class="date" style="text-align: right">Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</td>
        </table>
        <table class="header-table mb-2">
            <tr>
                <td class="label">Nomor</td>
                <td class="colon">:</td>
                <td class="bold content">{{ $data['nomor'] }}</td>
                <td class="spacer"></td>
                <td class="city">Kepada Yth.</td>
                {{-- <td class="date">{{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('F Y') }}</td> --}}
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="colon">:</td>
                <td class="content">-</td>
                <td class="spacer"></td>
                <td class="city"><span class="bold">{{ $data['penyedia']['toko'] ?? '' }}</span><br></td>
                <td class="date"></td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td class="colon">:</td>
                <td class="content bold">
                    {{ $data['pekerjaan'] ?? '' }}
                </td>
                <td class="spacer"></td>
                <td class="city">

                    di-<br>
                    <span class="indent">Tempat</span>
                </td>
                <td class="date"></td>
            </tr>
        </table>
        <br>
        <div class="kop">
            <h1 class="text-center font-extrabold mb-4">NOTA PESANAN BARANG / BAHAN</h1>
        </div>

        <p class="text-sm mb-2">Dengan hormat,</p>
        <p class="text-sm mb-2 justify-between px-1">
            Untuk keperluan pengadaan barang/jasa sebagaimana terurai dalam {{ $data['pekerjaan'] ?? '' }}, harap dapat diberikan barang/bahan di bawah ini:
        </p>

        <table class="report-table mb-2">
            <colgroup>
                <col style="width: 5%">
                <col style="width: 45%">
                <col style="width: 10%">
                <col style="width: 10%">
                <col style="width: 15%">
                <col style="width: 15%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Bahan/Alat (Barang)</th>
                    <th>Kuantitas</th>
                    <th>Satuan</th>
                    <th>Harga<br>Satuan (Rp)</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grand = 0;
                @endphp
                @foreach ($data['items'] as $row)
                    @php $grand += $row['total']; @endphp
                    <tr>
                        <td align="center">{{ $no++ }}</td>
                        <td>{{ $row['name'] }}</td>
                        <td align="center">{{ $row['qty'] }}</td>
                        <td align="center">{{ $row['unit'] }}</td>
                        <td align="right">{{ number_format($row['price'], 0, ',', '.') }}</td>
                        <td align="right">{{ number_format($row['total'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right" class="bold">Jumlah</td>
                    <td align="right" class="bold">{{ number_format($grand, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="6" align="center" class="bold">{{ ucwords(toWordsId((int) $grand)) }} Rupiah</td>
                </tr>
            </tbody>
        </table>

        <p class="text-sm mb-2"><span class="bold">Dengan Ketentuan :</span></p>
        <ol class="text-sm mb-4 rules">
            <li>1. Pembayaran melalui bendahara pengeluaran
                {{ \Illuminate\Support\Str::title($opd->nama_opd ?? 'Dinas Komunikasi dan Informatika') }}.</li>
            <li>2. Pembayaran dilaksanakan apabila barang-bahan tersebut telah diperiksa oleh Panitia Pemeriksa Barang
                sesuai dengan kualitas dan kuantitas barang yang diperiksa.</li>
        </ol>

        <div class="grid grid-cols-2 gap-6 mt-6">
            <div class="text-center text-sm">
                <p class="mb-1">&nbsp;</p>
                <p class="mb-1">Setuju Untuk Melaksanakan Pekerjaan</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $data['penyedia']['pemilik'] ?? '' }}</p>
            </div>
            <div class="text-center text-sm">
                <p class="mb-1">Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>

                <p class="mb-1">Pejabat Pengadaan</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $data['pejabat']['nama'] ?? '' }}</p>
                <p class="text-sm">NIP. {{ $data['pejabat']['nip'] ?? '-' }}</p>
            </div>

        </div>

        <div class="grid grid-cols-1 mt-8 text-sm">
            <div class="text-center">
                <p class="mb-1">MENGETAHUI,</p>
                <p class="mb-1">PENGGUNA ANGGARAN SELAKU PPK</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $data['ppk']['nama'] ?? '' }}</p>
                <p class="text-sm">NIP. {{ $data['ppk']['nip'] ?? '-' }}</p>
            </div>
        </div>
    </div>
@endsection
