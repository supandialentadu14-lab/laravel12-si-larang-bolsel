@extends('layouts.admin')

@section('header', 'Nota Pesanan')
@section('content')
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
                color: #000000 !important;
                background-color: #ffffff !important;
            }

            @page {
                size: 210mm 330mm;
                margin: 5mm 15mm;
            }
            body { margin: 0; background-color: white !important; }
            .preview-paper { 
                width: 100% !important; 
                min-height: auto !important; 
                padding: 0 !important; 
                margin: 0 !important; 
                box-sizing: border-box; 
                background-color: #ffffff !important; 
                box-shadow: none !important; 
                color: #000000 !important;
            }
            .border-b-4 { border-color: #000000 !important; }
        }

        @media screen {
            html, body { transition: background 0.3s ease; }
            .theme-dark html, .theme-dark body { background-color: #0f172a; }
            #print-area { width: 210mm; margin: 0 auto; }
        }

        .report-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #1e293b;
            padding: 6px;
            font-size: 12px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
        }

        /* Dark mode table borders */
        .theme-dark .report-table th,
        .theme-dark .report-table td {
            border-color: #475569;
        }

        .report-table th {
            text-align: center;
            font-weight: bold;
            background: rgba(0,0,0,0.02);
        }

        .theme-dark .report-table th {
            background: rgba(255,255,255,0.05);
        }

        .kop h1 {
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 16px;
            margin: 6px 0;
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
    </style>
    
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-transparent dark:border-slate-700 p-6 mb-6 print:hidden flex justify-between items-center">
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 uppercase tracking-tight">Pratinjau Nota Pesanan</h2>
        <div class="flex gap-2">
            <a href="{{ route('reports.nota.list') }}" class="bg-slate-500 hover:bg-slate-600 dark:bg-slate-600 dark:hover:bg-slate-500 text-white px-4 py-2 rounded-lg font-bold shadow transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 text-white px-4 py-2 rounded-lg font-bold shadow transition flex items-center gap-2">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
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
