@extends('layouts.admin')

@section('header', 'Paket Dokumen')
@section('content')
    <style>
        .bundle-paper {
            width: 210mm;
            min-height: 330mm;
            margin: 16px auto;
            background: #ffffff;
            padding: 10mm 15mm;
            line-height: 1.4;
            color: black;
            font-family: 'Nunito', sans-serif;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
            box-sizing: border-box;
        }

        .bundle-sheet {
            page-break-after: always;
        }

        .bundle-sheet:last-child {
            page-break-after: auto;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .uppercase { text-transform: uppercase; }
        .italic { font-style: italic; }
        .border-kwt { border: 1px solid black; }
        .border-t-kwt { border-top: 1px solid black; }
        .border-r-kwt { border-right: 1px solid black; }

        .doc-nota .bundle-paper p,
        .doc-pemeriksaan .bundle-paper p,
        .doc-penerimaan .bundle-paper p { margin: 5px 0; font-size: 14px; }
        .doc-nota .bundle-paper h2,
        .doc-pemeriksaan .bundle-paper h2,
        .doc-penerimaan .bundle-paper h2 { margin: 5px 0; }
        .doc-nota .bundle-paper table,
        .doc-pemeriksaan .bundle-paper table,
        .doc-penerimaan .bundle-paper table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .doc-nota .bundle-paper th, .doc-nota .bundle-paper td,
        .doc-pemeriksaan .bundle-paper th, .doc-pemeriksaan .bundle-paper td,
        .doc-penerimaan .bundle-paper th, .doc-penerimaan .bundle-paper td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
        .doc-kwitansi .bundle-paper th, .doc-kwitansi .bundle-paper td { padding: 8px 12px; font-size: 14px; }

        @media print {
            body * { visibility: hidden; }
            #bundle-print-area, #bundle-print-area * { visibility: visible; }
            #bundle-print-area { position: static !important; width: auto !important; overflow: visible !important; }
            @page { size: 210mm 330mm; margin: 10mm 15mm; }
            body { margin: 0; }
            .bundle-paper { width: 100% !important; min-height: auto !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>

    <div class="bg-white rounded-lg shadow p-6 mb-6 print:hidden flex items-center justify-end gap-2">
            <a href="{{ route('reports.nota.list') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold shadow flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" onclick="openPrintPreview()" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-bold shadow">
                <i class="fas fa-print mr-2"></i> Print 4 Dokumen
            </button>
    </div>

    <div id="bundle-print-area">
        <div class="bundle-sheet doc-nota">
            <div class="bundle-paper">
                @include('reports.partials.docs.nota_pesanan', ['data' => $nota, 'opd' => $opd])
            </div>
        </div>

        <div class="bundle-sheet doc-pemeriksaan">
            <div class="bundle-paper">
                @if($pemeriksaan)
                    @include('reports.partials.docs.pemeriksaan', ['data' => $pemeriksaan, 'opd' => $opd])
                @else
                    <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PEMERIKSAAN TIDAK DITEMUKAN</div>
                    <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
                @endif
            </div>
        </div>

        <div class="bundle-sheet doc-penerimaan">
            <div class="bundle-paper">
                @if($penerimaan)
                    @include('reports.partials.docs.penerimaan', ['data' => $penerimaan, 'opd' => $opd])
                @else
                    <div class="text-center font-bold" style="margin-top: 120px;">BERITA ACARA PENERIMAAN TIDAK DITEMUKAN</div>
                    <div class="text-center" style="margin-top: 12px;">Nomor Nota: {{ $nota['nomor'] ?? '-' }}</div>
                @endif
            </div>
        </div>

        <div class="bundle-sheet doc-kwitansi">
            <div class="bundle-paper">
                @if($kwitansi)
                    @include('reports.partials.docs.kwitansi', ['data' => $kwitansi, 'opd' => $opd])
                @else
                    <div class="text-center font-bold" style="margin-top: 120px;">KWITANSI TIDAK DITEMUKAN</div>
                    <div class="text-center" style="margin-top: 12px;">Nomor Penerimaan: {{ $penerimaan['nomor'] ?? '-' }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function openPrintPreview() {
            const area = document.getElementById('bundle-print-area');
            if (!area) return;
            const content = area.innerHTML;
            const win = window.open('', '_blank', 'width=900,height=1200');
            if (!win) {
                alert('Silakan izinkan popup untuk mencetak laporan.');
                return;
            }
            win.document.open();
            win.document.write(`<!doctype html>
                <html>
                <head>
                    <title>Cetak Paket Dokumen</title>
                    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
                    <style>
                        body { margin: 0; padding: 0; font-family: 'Nunito', sans-serif; background: #fff; color: #000; }
                        .bundle-paper { width: 210mm; min-height: 330mm; margin: 0 auto; background: #fff; padding: 10mm 15mm; line-height: 1.4; box-sizing: border-box; }
                        .bundle-sheet { page-break-after: always; }
                        .bundle-sheet:last-child { page-break-after: auto; }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .font-bold { font-weight: bold; }
                        .underline { text-decoration: underline; }
                        .uppercase { text-transform: uppercase; }
                        .italic { font-style: italic; }
                        .border-kwt { border: 1px solid black; }
                        .border-t-kwt { border-top: 1px solid black; }
                        .border-r-kwt { border-right: 1px solid black; }
                        .signature-block { break-inside: avoid; page-break-inside: avoid; }
                        .signature-block * { break-inside: avoid; page-break-inside: avoid; }

                        .doc-nota .bundle-paper p,
                        .doc-pemeriksaan .bundle-paper p,
                        .doc-penerimaan .bundle-paper p { margin: 5px 0; font-size: 14px; }
                        .doc-nota .bundle-paper h2,
                        .doc-pemeriksaan .bundle-paper h2,
                        .doc-penerimaan .bundle-paper h2 { margin: 5px 0; }
                        .doc-nota .bundle-paper table,
                        .doc-pemeriksaan .bundle-paper table,
                        .doc-penerimaan .bundle-paper table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        .doc-nota .bundle-paper th, .doc-nota .bundle-paper td,
                        .doc-pemeriksaan .bundle-paper th, .doc-pemeriksaan .bundle-paper td,
                        .doc-penerimaan .bundle-paper th, .doc-penerimaan .bundle-paper td { border: 1px solid black; padding: 6px 10px; font-size: 12px; }
                        .doc-kwitansi .bundle-paper th, .doc-kwitansi .bundle-paper td { padding: 8px 12px; font-size: 14px; }

                        @media print {
                            @page { size: 210mm 330mm; margin: 10mm 15mm; }
                            body { margin: 0; }
                            .bundle-paper { width: 100% !important; min-height: auto !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
                        }
                    </style>
                </head>
                <body>
                    <div id="bundle-print-area">${content}</div>
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() { window.close(); };
                        };
                    <\/script>
                </body>
                </html>`);
            win.document.close();
        }
    </script>
@endsection
