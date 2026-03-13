@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Nota Pesanan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.nota.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Document Preview Card --}}
    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden flex flex-col items-center">
        <div class="w-full flex justify-center no-scrollbar overflow-x-auto">
            <div class="flex-shrink-0 origin-top transform scale-[0.38] min-[400px]:scale-[0.45] sm:scale-100 mb-[-120%] min-[400px]:mb-[-100%] sm:mb-0" style="width: 210mm;">
                <style>
                    .preview-paper-mobile { 
                        width: 210mm; 
                        min-height: 297mm; 
                        margin: 0; 
                        background: #fff; 
                        padding: 10mm 15mm; 
                        line-height: 1.4; 
                        color: black; 
                        font-family: 'Nunito', sans-serif;
                        box-shadow: 0 0 30px rgba(0,0,0,0.12);
                        border: 1px solid #f1f5f9;
                    }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                </style>
                <div id="document-preview" class="preview-paper-mobile">
                    @include('partials.kop', ['opd' => $opd])
                    
                    <div class="text-right mb-4">
                        <p>Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
                    </div>

                    <table style="border: none; width: 100%; margin-bottom: 20px;">
                        <tr style="border: none;">
                            <td style="border: none; width: 100px;">Nomor</td>
                            <td style="border: none; width: 15px;">:</td>
                            <td style="border: none;" class="font-bold">{{ $data['nomor'] }}</td>
                            <td style="border: none; width: 50px;"></td>
                            <td style="border: none;">Kepada Yth.</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none;">Lampiran</td>
                            <td style="border: none;">:</td>
                            <td style="border: none;">-</td>
                            <td style="border: none;"></td>
                            <td style="border: none;" class="font-bold">{{ $data['penyedia']['toko'] ?? '' }}</td>
                        </tr>
                        <tr style="border: none;">
                            <td style="border: none; vertical-align: top;">Perihal</td>
                            <td style="border: none; vertical-align: top;">:</td>
                            <td style="border: none; vertical-align: top;" class="font-bold">
                                Belanja {{ $data['belanja'] }} Pada Keg. {{ $data['kegiatan'] }} Sub Keg. {{ $data['sub_kegiatan'] }} Tahun {{ $data['tahun'] }}
                            </td>
                            <td style="border: none;"></td>
                            <td style="border: none; vertical-align: top;">di-<br><span style="padding-left: 20px;">Tempat</span></td>
                        </tr>
                    </table>

                    <div class="text-center mb-6">
                        <h2 class="font-bold uppercase" style="font-size: 18px;">NOTA PESANAN BARANG / BAHAN</h2>
                    </div>

                    <p>Dengan hormat,</p>
                    <p>Untuk keperluan pengadaan {{ $data['belanja'] }} dalam Kegiatan {{ $data['kegiatan'] }}, Sub Kegiatan {{ $data['sub_kegiatan'] }} pada Tahun {{ $data['tahun'] }}, harap dapat diberikan barang/bahan di bawah ini:</p>

                    <table>
                        <thead>
                            <tr class="text-center font-bold" style="background-color: #f8fafc;">
                                <th style="width:30px">No</th>
                                <th>Jenis Bahan/Alat (Barang)</th>
                                <th style="width:80px">Kuantitas</th>
                                <th style="width:80px">Satuan</th>
                                <th style="width:120px">Harga Satuan</th>
                                <th style="width:120px">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grand = 0; @endphp
                            @foreach ($data['items'] as $i => $row)
                                @php $grand += $row['total']; @endphp
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td class="text-center">{{ $row['qty'] }}</td>
                                    <td class="text-center">{{ $row['unit'] }}</td>
                                    <td class="text-right">Rp {{ number_format($row['price'], 0, ',', '.') }}</td>
                                    <td class="text-right font-bold">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #f8fafc;">
                                <td colspan="5" class="text-right font-bold">Jumlah Total</td>
                                <td class="text-right font-bold" style="font-size: 14px; color: #1e293b;">Rp {{ number_format($grand, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top: 20px;" class="font-bold">Dengan Ketentuan :</p>
                    <div style="padding-left: 20px;">
                        <p>1. Pembayaran melalui bendahara pengeluaran {{ $opd->nama_opd ?? '' }}.</p>
                        <p>2. Pembayaran dilaksanakan apabila barang-bahan tersebut telah diperiksa oleh Panitia Pemeriksa Barang sesuai dengan kualitas dan kuantitas barang yang diperiksa.</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center;">
                        <div>
                            <p>&nbsp;</p>
                            <p>Setuju Untuk Melaksanakan Pekerjaan</p>
                            <div style="height: 80px;"></div>
                            <p class="font-bold underline uppercase">{{ $data['penyedia']['pemilik'] ?? '' }}</p>
                        </div>
                        <div>
                            <p>Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
                            <p>Pejabat Pengadaan</p>
                            <div style="height: 80px;"></div>
                            <p class="font-bold underline uppercase">{{ $data['pejabat']['nama'] ?? '' }}</p>
                            <p>NIP. {{ $data['pejabat']['nip'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top: 40px;">
                        <p>MENGETAHUI,</p>
                        <p>PENGGUNA ANGGARAN SELAKU PPK</p>
                        <div style="height: 80px;"></div>
                        <p class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</p>
                        <p>NIP. {{ $data['ppk']['nip'] ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openPrintPreview() {
        const printArea = document.getElementById('document-preview');
        if (!printArea) return;
        
        const content = printArea.innerHTML;
        
        const win = window.open('', '_blank', 'width=900,height=1200');
        if (!win) {
            alert('Silakan izinkan popup untuk mencetak laporan.');
            return;
        }
        
        win.document.open();
        win.document.write(`
            <!doctype html>
            <html>
            <head>
                <title>Cetak Nota Pesanan</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                    @media print { 
                        body { padding: 0; }
                        @page { size: 210mm 330mm; margin: 10mm; }
                    }
                </style>
            </head>
            <body>
                <div class="preview-paper-mobile">
                    ${content}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() { window.close(); };
                    };
                <\/script>
            </body>
            </html>
        `);
        win.document.close();
    }
</script>
@endsection
