@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Berita Acara Pinjam Pakai</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.pinjam.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <button onclick="openPrintPreview()" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
                <i class="fas fa-print text-xs"></i>
            </button>
        </div>
    </div>

    {{-- Document Preview Card --}}
    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm overflow-hidden">
        <div id="paper-container" class="w-full no-scrollbar flex justify-center items-start" style="padding-bottom:8px;">
            <div id="paper-scale" class="flex-shrink-0" style="transform-origin: top center; width: 850px; margin: 0 auto;">
                <style>
                    .preview-paper-mobile { width: 850px; min-height: 1200px; margin: 0; background: #fff; padding: 24px; line-height: 1.4; color: black; font-family: 'Nunito', sans-serif; box-shadow: 0 0 30px rgba(0,0,0,0.12); border: 1px solid #f1f5f9; }
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
                    
                    <div class="text-center mb-6" style="margin-top: 30px;">
                        <h2 class="font-bold underline uppercase" style="font-size: 18px; margin-bottom: 0;">BERITA ACARA SERAH TERIMA BARANG INVENTARIS</h2>
                        <p style="font-size: 14px;">NO: {{ $data['nomor'] ?? '' }}</p>
                    </div>

                    <p>{{ $data['pembuka'] ?? ('Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('l d F Y') . ', bertempat di ' . ucwords(strtolower(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-'))) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:') }}</p>

                    <div style="margin: 15px 0;">
                        <table style="border: none; width: 100%;">
                            <tr style="border: none;">
                                <td style="border: none; width: 120px;">N a m a</td>
                                <td style="border: none; width: 15px;">:</td>
                                <td style="border: none;" class="font-bold">{{ $data['pihak_pertama']['nama'] }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">N I P</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $data['pihak_pertama']['nip'] ?? '-' }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">Jabatan</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $data['pihak_pertama']['jabatan'] }}</td>
                            </tr>
                        </table>
                        <p class="font-bold">Selanjutnya disebut PIHAK PERTAMA</p>
                    </div>

                    <div style="margin: 15px 0;">
                        <table style="border: none; width: 100%;">
                            <tr style="border: none;">
                                <td style="border: none; width: 120px;">N a m a</td>
                                <td style="border: none; width: 15px;">:</td>
                                <td style="border: none;" class="font-bold">{{ $data['pihak_kedua']['nama'] }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">N I P</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $data['pihak_kedua']['nip'] ?? '-' }}</td>
                            </tr>
                            <tr style="border: none;">
                                <td style="border: none;">Jabatan</td>
                                <td style="border: none;">:</td>
                                <td style="border: none;">{{ $data['pihak_kedua']['jabatan'] }}</td>
                            </tr>
                        </table>
                        <p class="font-bold">Selanjutnya disebut PIHAK KEDUA</p>
                    </div>

                    <p>Bahwa kedua belah pihak sepakat mengadakan perjanjian serah terima barang inventaris kantor/kendaraan milik Pemerintah Kabupaten Bolaang Mongondow Selatan :</p>

                    <table>
                        <thead>
                            <tr class="text-center font-bold" style="background-color: #f8fafc;">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Merk/Type</th>
                                <th>No. Polisi</th>
                                <th>Tahun</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['items'] as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                    <td>{{ ($item['merk'] ?? '-') . ' / ' . ($item['tipe'] ?? '-') }}</td>
                                    <td class="text-center">{{ $item['identitas'] ?? '-' }}</td>
                                    <td class="text-center">{{ $item['tahun'] ?? '-' }}</td>
                                    <td class="text-center">{{ $item['kondisi'] ?? '-' }}</td>
                                    <td class="text-center font-bold">{{ $item['jumlah'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p style="margin-top: 15px;">Dengan ketentuan sebagai berikut:</p>
                    <div style="padding-left: 20px;">
                        @php
                            $rulesLines = preg_split("/\r\n|\n|\r/", $data['ketentuan'] ?? '');
                            $rulesLines = array_values(array_filter($rulesLines, fn($l) => trim($l) !== ''));
                            $defaultRules = [
                                'PIHAK PERTAMA selaku Pengguna Barang meminjamkan Barang Milik Daerah tersebut di atas kepada PIHAK KEDUA untuk mendukung kelancaran pelaksanaan tugas.',
                                'PIHAK KEDUA bertanggung jawab dalam hal penggunaan, pemeliharaan dan pengamanan barang tersebut.',
                                'PIHAK KEDUA dilarang memindahtangankan barang tersebut kepada pihak lain tanpa seizin PIHAK PERTAMA;',
                                'PIHAK KEDUA sanggup mengganti rugi apabila barang yang dipinjamkan hilang;',
                                'PIHAK KEDUA wajib mengembalikan Barang Milik Daerah kepada PIHAK PERTAMA apabila telah selesai masa tugasnya.',
                            ];
                            $list = count($rulesLines) ? $rulesLines : $defaultRules;
                        @endphp
                        @foreach ($list as $i => $line)
                            <div style="display: flex; margin-bottom: 4px;">
                                <div style="width: 25px;">{{ chr(97 + $i) }}.</div>
                                <div style="flex: 1;">{!! trim($line) !!}</div>
                            </div>
                        @endforeach
                    </div>

                    <p style="margin-top: 15px;">Demikian Berita Acara Serah Terima Barang Inventaris ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
                    
                    <div class="text-right" style="margin-top: 20px;">
                        {{ ucwords(strtolower($data['tempat'] ?? '')) }}, {{ \Illuminate\Support\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center;">
                        <div>
                            <p>PIHAK KEDUA</p>
                            <div style="height: 80px;"></div>
                            <p class="font-bold underline uppercase">{{ $data['pihak_kedua']['nama'] }}</p>
                            <p>NIP. {{ $data['pihak_kedua']['nip'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p>PIHAK PERTAMA</p>
                            <div style="height: 80px;"></div>
                            <p class="font-bold underline uppercase">{{ $data['pihak_pertama']['nama'] }}</p>
                            <p>NIP. {{ $data['pihak_pertama']['nip'] ?? '-' }}</p>
                        </div>
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
                <title>Cetak Berita Acara Pinjam Pakai</title>
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
    (function() {
        const paperScale = document.getElementById('paper-scale');
        const doc = document.getElementById('document-preview');
        const container = document.getElementById('paper-container');
        function fit() {
            if (!paperScale || !doc || !container) return;
            const baseW = 850;
            const baseH = doc.scrollHeight || 1200;
            const rect = container.getBoundingClientRect();
            const availW = container.clientWidth;
            const availH = Math.max(320, window.innerHeight - rect.top - 12);
            const scale = Math.min(availW / baseW, availH / baseH);
            const clamped = Math.max(0.22, Math.min(scale, 1));
            paperScale.style.transform = `scale(${clamped})`;
            paperScale.style.marginLeft = 'auto';
            paperScale.style.marginRight = 'auto';
        }
        window.addEventListener('resize', fit);
        document.addEventListener('DOMContentLoaded', fit);
        setTimeout(fit, 0);
    })();
</script>
@endsection
