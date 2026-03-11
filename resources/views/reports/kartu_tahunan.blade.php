@extends('layouts.admin')

@section('header', 'Kartu Persediaan Barang Tahunan')

@section('content')

    <style>
        /* ============================= */
        /* TABEL UTAMA                  */
        /* ============================= */
        .report-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            /* Agar kolom stabil saat print */
        }

        .report-table th,
        .report-table td {
            border: 1px solid black;
            padding: 4px;
            font-size: 12px;
            /* Diperkecil agar muat 1 halaman */
            word-wrap: break-word;
        }

        .report-table th {
            text-align: center;
            font-weight: bold;
        }

        /* Agar header tabel muncul lagi jika pindah halaman */
        .report-table thead {
            display: table-header-group;
        }

        .report-table tfoot {
            display: table-footer-group;
        }

        .report-table tr {
            page-break-inside: avoid;
            /* Jangan pecah baris di tengah halaman */
        }

        /* ============================= */
        /* INFO HEADER                  */
        /* ============================= */
        .info-table {
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-table td {
            border: none;
            padding: 2px 4px;
        }

        /* ============================= */
        /* TANDA TANGAN                 */
        /* ============================= */
        .ttd-table {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
            /* Jangan pindah halaman */
        }

        .ttd-table td {
            border: none !important;
            font-size: 14px;
            padding: 4px;
        }

        .ttd-table strong {
            font-size: 14px;
        }

        /* ============================= */
        /* PRINT SETTING                */
        /* ============================= */
        html, body {
            margin: 0;
            padding: 0;
            border: none;
        }
        .print-area {
            border: none !important;
            box-shadow: none !important;
        }
        @media screen {
            #print-area {
                width: 330mm;
                min-height: 210mm;
                margin: 16px auto;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                background: #ffffff;
            }
            .report-table { width: 100%; }
        }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area {
                position: static !important;
                width: auto !important;
                overflow: visible !important;
                border: none !important;
                display: block !important;
                background: #ffffff !important;
            }
            .print\:hidden { display: none !important; }
            @page { size: 330mm 210mm; margin: 12mm; }
            body { margin: 0; background: #ffffff !important; }
        }
    </style>


    <div class="print:hidden bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200 p-5 mb-8 sticky top-0 z-10 transition-all duration-300">
        <form method="GET" action="{{ route('reports.kartu.tahunan') }}" class="flex flex-col lg:flex-row lg:items-end gap-6">
            <div class="flex flex-col sm:flex-row gap-5 flex-1">
                <div class="w-full sm:w-1/2 group">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-1"></i> Dari Tanggal
                    </label>
                    <div class="relative">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full rounded-xl border-slate-200 text-sm bg-white shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 h-11 px-4">
                    </div>
                </div>
                <div class="w-full sm:w-1/2 group">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-calendar-check mr-1"></i> Sampai Tanggal
                    </label>
                    <div class="relative">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full rounded-xl border-slate-200 text-sm bg-white shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 h-11 px-4">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 shadow-md shadow-indigo-100 hover:shadow-indigo-200 transition-all duration-200 flex-1 sm:flex-none">
                    <i class="fas fa-filter text-indigo-200"></i>
                    Terapkan Filter
                </button>

                <div class="flex gap-2 flex-1 sm:flex-none">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-sm hover:bg-slate-900 shadow-lg shadow-slate-100 transition-all flex-1 sm:flex-none">
                        <i class="far fa-print text-slate-400"></i>
                        Cetak
                    </button>
                    <a href="{{ route('reports.export_kartu_tahunan', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg transition-all duration-200 hover:-translate-y-0.5 active:scale-95 flex-1 sm:flex-none"
                        style="background-color: #16a34a; color: white; box-shadow: 0 4px 14px rgba(22,163,74,0.3);"
                        onmouseenter="this.style.backgroundColor='#15803d'"
                        onmouseleave="this.style.backgroundColor='#16a34a'">
                        <i class="fas fa-file-excel" style="color: #bbf7d0;"></i>
                        Excel
                    </a>
                </div>

                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all flex-1 sm:flex-none">
                    <i class="fas fa-arrow-left text-slate-400"></i>
                    Kembali
                </a>
            </div>
        </form>
    </div>


    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="print-area bg-white shadow-lg border p-4 sm:p-8 rounded-lg mx-auto" style="min-width: 330mm;">

        @php
            $grouped = $grouped ?? [];
            $lastIndex = count($grouped);
            $current = 1;
        @endphp

        <div class="text-center mb-4">
            <h2 class="text-xl font-bold uppercase">
                KARTU PERSEDIAAN BARANG
            </h2>
            <div class="text-xl font-bold uppercase">
                DI LINGKUNGAN PEMERINTAH KABUPATEN BOLAANG MONGONDOW SELATAN
            </div>
            <h5 class="text-sm font-semibold mt-1">
                Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            </h5>
        </div>

        {{-- HEADER SKPD --}}
        <table class="info-table">
            <tr>
                <td width="140"><strong>SKPD</strong></td>
                <td width="10">:</td>
                <td>{{ ($master['opd']['nama'] ?? null) ?: ($opd->nama_opd ?? '-') }}</td>
            </tr>
            <tr>
                <td><strong>Kabupaten</strong></td>
                <td>:</td>
                <td>Bolaang Mongondow Selatan</td>
            </tr>
        </table>

        @foreach ($grouped as $data)
            @php
                $product = $data['product'];
                $rows = $data['rows'];
                $saldo = 0;
                $harga = 0;
            @endphp
            <table class="info-table">
                <tr>
                    <td width="140"><strong>Nama barang</strong></td>
                    <td width="10">:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td><strong>Satuan</strong></td>
                    <td>:</td>
                    <td>{{ $product->unit }}</td>
                </tr>
            </table>
            <div class="mb-8">

                {{-- HEADER PRODUK --}}
                <table class="report-table">
                    <colgroup>
                        <col style="width:2%"> {{-- No --}}
                        <col style="width:9%"> {{-- Tanggal --}}
                        <col style="width:18%"> {{-- Nomor Surat --}}
                        <col style="width:16%"> {{-- Uraian --}}
                        <col style="width:4%"> {{-- Masuk --}}
                        <col style="width:4%"> {{-- Keluar --}}
                        <col style="width:4%"> {{-- Sisa --}}
                        <col style="width:7%"> {{-- Harga --}}
                        <col style="width:7%"> {{-- Jml Masuk --}}
                        <col style="width:7%"> {{-- Jml Keluar --}}
                        <col style="width:7%"> {{-- Jml Sisa --}}
                        <col style="width:10%"> {{-- Keterangan --}}
                    </colgroup>
                    <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">Nomor Surat Dasar<br>Penerimaan/Pengeluaran</th>
                        <th rowspan="2">Uraian</th>
                        <th colspan="3">Barang-Barang</th>
                        <th rowspan="2">Harga Satuan<br>(Rp)</th>
                        <th colspan="3">Jumlah Harga (Rp)</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Sisa</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Sisa</th>
                    </tr>
                    </thead>
                    <tbody>

                        @php $no = 1; @endphp

                        @foreach ($rows as $date => $row)
                            @php
                                $saldo += $row['masuk'] - $row['keluar'];
                                $harga = $row['harga'] ?? 0;
                            @endphp

                            <tr align="center">
                                <td>{{ $no++ }}</td>
                                <td align="center">
                                    {{ $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d F Y') : '-' }}
                                </td>
                                <td>{{ $row['nosur'] }}</td>
                                <td align="center">{{ $product->name }}</td>
                                <td>{{ $row['masuk'] ?: '' }}</td>
                                <td>{{ $row['keluar'] ?: '' }}</td>
                                <td><strong>{{ $saldo }}</strong></td>
                                <td align="right">{{ number_format($harga, 0, ',', '.') }}</td>
                                <td align="right">
                                    {{ $row['masuk'] ? number_format($row['masuk'] * $harga, 0, ',', '.') : '' }}</td>
                                <td align="right">
                                    {{ $row['keluar'] ? number_format($row['keluar'] * $harga, 0, ',', '.') : '' }}</td>
                                <td align="right"><strong>{{ number_format($saldo * $harga, 0, ',', '.') }}</strong></td>
                                <td></td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="6" align="center">
                                <strong>Saldo Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</strong>
                            </td>
                            <td align="center">
                                <strong>{{ $saldo == 0 ? 'Nihil' : $saldo }}</strong>
                            </td>
                            <td colspan="3"></td>
                            <td align="right">
                                <strong>
                                    {{ $saldo == 0 ? 'Nihil' : number_format($saldo * $harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($current == $lastIndex)
                <div style="display: flex; justify-content: space-between; padding: 0 40px; margin-top: 50px; page-break-inside: avoid; font-size: 14px;">
                    <div style="text-align: center;">
                        Dibuat Oleh<br>
                        Pengurus Barang<br><br><br><br><br>
                        <strong><u>{{ $opd->pengurus_nama ?? '' }}</u></strong><br>
                        NIP. {{ $opd->pengurus_nip ?? '' }}
                    </div>
                    <div style="text-align: center;">
                        Mengetahui<br>
                        Kepala Dinas<br><br><br><br><br>
                        <strong><u>{{ $opd->kepala_nama ?? '' }}</u></strong><br>
                        NIP. {{ $opd->kepala_nip ?? '' }}
                    </div>
                </div>
            @endif

            @php $current++; @endphp
        @endforeach
        </div>
    </div>
@endsection
