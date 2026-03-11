@extends('layouts.admin')

@section('header', 'Kwitansi')
@section('subheader', 'Ringkasan pembayaran berdasarkan BAP Penerimaan')

@section('actions')
    <a href="{{ route('reports.kwitansi.list') }}" class="no-print btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    <button onclick="window.print()" class="no-print btn btn-neutral ml-2"><i class="fas fa-print"></i> Cetak</button>
    @if (!empty($data['penerimaan_nomor']))
        <a href="{{ route('reports.kwitansi.print_all', ['penerimaan_nomor' => $data['penerimaan_nomor']]) }}" class="no-print btn btn-neutral ml-2">
            <i class="fas fa-print"></i> Cetak Berkas Full
        </a>
    @endif
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
        }
        @media screen {
            html, body { background: #f3f4f6; }
            #print-area { width: 210mm; margin: 0 auto; }
            .preview-paper { width: 210mm; min-height: 330mm; margin: 16px auto; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.08); padding: 5mm 15mm; border-radius: 8px; }
        }
        .kwitansi-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .kwitansi-table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
            vertical-align: top;
        }
    </style>
    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="preview-paper text-black">
            <div class="text-center font-bold text-xl mb-4 uppercase italic">KWITANSI</div>
        <div class="border border-black">
            <table class="kwitansi-table text-sm">
                <colgroup>
                    <col style="width: 30%">
                    <col style="width: 2%">
                    <col style="width: 68%">
                </colgroup>
                <tr>
                    <td class="px-2 py-1 italic">TAHUN ANGGARAN</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1">{{ $data['tahun'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="px-2 py-1 italic">KODE REKENING</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1">{{ $data['rekening'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="px-2 py-1 italic">NO. KWT</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1">{{ $data['nomor_kwt'] ?? '' }}</td>
                </tr>
            </table>
            
            <table class="kwitansi-table text-sm border-t border-black mt-1">
                <colgroup>
                    <col style="width: 30%">
                    <col style="width: 2%">
                    <col style="width: 68%">
                </colgroup>
                <tr>
                    <td class="px-2 py-1 italic">Sudah Terima Dari</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1 italic">Bendahara Pengeluaran {{ $data['opd_nama'] ?? ($opd->nama_opd ?? '') }} Kabupaten Bolaang Mongondow Selatan</td>
                </tr>
                <tr>
                    <td class="px-2 py-1 italic">Banyaknya Uang</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1 italic font-bold"># {{ \Illuminate\Support\Str::upper($data['terbilang'] ?? '') }} RUPIAH #</td>
                </tr>
                <tr>
                    <td class="px-2 py-1 italic">Untuk Pembayaran</td>
                    <td class="px-2 py-1 text-center">:</td>
                    <td class="px-2 py-1 italic font-bold">{{ $data['pembayaran_uraian'] ?? '' }}</td>
                </tr>
            </table>

            <div class="border-t border-black flex text-sm font-bold italic">
                <div class="px-2 py-1 border-r border-black w-32">Rp</div>
                <div class="px-2 py-1 flex-1">{{ number_format($data['jumlah'] ?? 0, 0, ',', '.') }}</div>
            </div>

            <div class="p-4 text-sm">
                <div class="flex justify-end mb-8">
                    <div class="text-right">{{ $data['lokasi_tanggal'] ?? '' }}</div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 text-center mb-8">
                    <div>
                        <div class="italic font-bold">PPTK</div>
                        <div class="h-20"></div>
                        <div class="font-bold underline uppercase">{{ $data['pejabat']['pptk'] ?? '' }}</div>
                        <div>NIP. {{ $data['pptk_nip'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="italic font-bold">Bendahara Pengeluaran,</div>
                        <div class="h-20"></div>
                        <div class="font-bold underline uppercase">{{ $data['pejabat']['bendahara'] ?? '' }}</div>
                        <div>NIP. {{ $data['bendahara_nip'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="italic font-bold">Yang Menerima,</div>
                        <div class="italic font-bold">Pihak Ketiga</div>
                        <div class="h-16"></div>
                        <div class="font-bold underline uppercase">{{ $data['pejabat']['pihak_ketiga'] ?? '' }}</div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="italic font-bold">Mengetahui,</div>
                    <div class="italic font-bold">Pengguna Anggaran</div>
                    <div class="h-20"></div>
                    <div class="font-bold underline uppercase">{{ $data['pejabat']['pengguna'] ?? '' }}</div>
                    <div>NIP. {{ $data['ppk_nip'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
