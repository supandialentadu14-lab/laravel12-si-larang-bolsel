@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up pb-20">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pratinjau</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Kwitansi Pembayaran</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.kwitansi.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
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
                        padding: 15mm 20mm; 
                        line-height: 1.4; 
                        color: black; 
                        font-family: 'Nunito', sans-serif;
                        box-shadow: 0 0 30px rgba(0,0,0,0.12);
                        border: 1px solid #f1f5f9;
                    }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { padding: 8px 12px; font-size: 14px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                    .italic { font-style: italic; }
                    .border-kwt { border: 1px solid black; }
                    .border-t-kwt { border-top: 1px solid black; }
                    .border-r-kwt { border-right: 1px solid black; }
                </style>
                <div id="document-preview" class="preview-paper-mobile">
                    <div class="text-center font-bold text-2xl mb-6 uppercase italic">KWITANSI</div>
                    
                    <div class="border-kwt">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 200px;" class="italic">TAHUN ANGGARAN</td>
                                <td style="width: 20px;" class="text-center">:</td>
                                <td>{{ $data['tahun'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="italic">KODE REKENING</td>
                                <td class="text-center">:</td>
                                <td>{{ $data['rekening'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="italic">NO. KWT</td>
                                <td class="text-center">:</td>
                                <td>{{ $data['nomor_kwt'] ?? '' }}</td>
                            </tr>
                        </table>
                        
                        <table class="border-t-kwt" style="width: 100%;">
                            <tr>
                                <td style="width: 200px; vertical-align: top;" class="italic">Sudah Terima Dari</td>
                                <td style="width: 20px; vertical-align: top;" class="text-center">:</td>
                                <td class="italic">Bendahara Pengeluaran {{ $data['opd_nama'] ?? ($opd->nama_opd ?? '') }} Kabupaten Bolaang Mongondow Selatan</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;" class="italic">Banyaknya Uang</td>
                                <td style="vertical-align: top;" class="text-center">:</td>
                                <td class="italic font-bold">{{ $data['terbilang'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;" class="italic">Untuk Pembayaran</td>
                                <td style="vertical-align: top;" class="text-center">:</td>
                                <td class="italic">{{ $data['pembayaran_uraian'] ?? '' }}</td>
                            </tr>
                        </table>

                        <div class="border-t-kwt flex font-bold italic" style="display: flex; border-top: 1px solid black;">
                            <div class="border-r-kwt" style="padding: 10px 15px; width: 100px; border-right: 1px solid black; font-size: 18px;">Rp</div>
                            <div style="padding: 10px 15px; font-size: 18px;">{{ number_format($data['jumlah'] ?? 0, 0, ',', '.') }}</div>
                        </div>

                        <div style="padding: 30px;">
                            <div style="display: flex; justify-content: flex-end; margin-bottom: 30px;">
                                <div class="text-right">{{ $data['lokasi_tanggal'] ?? '' }}</div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center; margin-bottom: 40px;">
                                <div>
                                    <div class="italic font-bold">PPTK</div>
                                    <div style="height: 80px;"></div>
                                    <div class="font-bold underline uppercase">{{ $data['pejabat']['pptk'] ?? '' }}</div>
                                    <div style="font-size: 12px;">NIP. {{ $data['pptk_nip'] ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="italic font-bold">Bendahara Pengeluaran,</div>
                                    <div style="height: 80px;"></div>
                                    <div class="font-bold underline uppercase">{{ $data['pejabat']['bendahara'] ?? '' }}</div>
                                    <div style="font-size: 12px;">NIP. {{ $data['bendahara_nip'] ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="italic font-bold">Yang Menerima,</div>
                                    <div class="italic font-bold">Pihak Ketiga</div>
                                    <div style="height: 60px;"></div>
                                    <div class="font-bold underline uppercase">{{ $data['pejabat']['pihak_ketiga'] ?? '' }}</div>
                                </div>
                            </div>

                            <div class="text-center" style="margin-top: 40px;">
                                <div class="italic font-bold">Mengetahui,</div>
                                <div class="italic font-bold">Pengguna Anggaran</div>
                                <div style="height: 80px;"></div>
                                <div class="font-bold underline uppercase">{{ $data['pejabat']['pengguna'] ?? '' }}</div>
                                <div style="font-size: 12px;">NIP. {{ $data['ppk_nip'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-center gap-2 text-slate-400">
            <i class="fas fa-arrows-left-right text-xs animate-pulse"></i>
            <p class="text-[10px] font-bold uppercase tracking-widest">Geser untuk melihat detail dokumen</p>
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
                <title>Cetak Kwitansi</title>
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px; font-family: 'Nunito', sans-serif; background: #fff; }
                    .preview-paper-mobile { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; color: black; }
                    .preview-paper-mobile p { margin: 5px 0; font-size: 14px; }
                    .preview-paper-mobile h2 { margin: 5px 0; }
                    .preview-paper-mobile table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    .preview-paper-mobile th, .preview-paper-mobile td { padding: 8px 12px; font-size: 14px; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .underline { text-decoration: underline; }
                    .uppercase { text-transform: uppercase; }
                    .italic { font-style: italic; }
                    .border-kwt { border: 1px solid black; }
                    .border-t-kwt { border-top: 1px solid black; }
                    .border-r-kwt { border-right: 1px solid black; }
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
