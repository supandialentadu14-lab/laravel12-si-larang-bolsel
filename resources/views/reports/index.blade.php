@extends('layouts.admin')

@section('header', 'Laporan Persediaan Barang Habis Pakai')

@section('content')

    <div class="print:hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 mb-8 sticky top-0 z-10 transition-all duration-300">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-6">
            <div class="flex flex-col sm:flex-row gap-5 flex-1">
                <div class="w-full sm:w-1/3 group">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-1"></i> Dari Tanggal
                    </label>
                    <div class="relative">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-600 text-sm bg-white dark:bg-slate-700 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 dark:text-slate-200 h-11 px-4">
                    </div>
                </div>
                <div class="w-full sm:w-1/3 group">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-calendar-check mr-1"></i> Sampai Tanggal
                    </label>
                    <div class="relative">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-600 text-sm bg-white dark:bg-slate-700 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 dark:text-slate-200 h-11 px-4">
                    </div>
                </div>
                <div class="w-full sm:w-1/3 group">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-tags mr-1"></i> Filter Kategori
                    </label>
                    <div class="relative">
                        <select name="category_id" class="w-full rounded-xl border-slate-200 dark:border-slate-600 text-sm bg-white dark:bg-slate-700 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 dark:text-slate-200 h-11 px-4 appearance-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
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
                    <button type="button" @click="window.print()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-sm hover:bg-slate-900 shadow-lg shadow-slate-100 transition-all flex-1 sm:flex-none">
                        <i class="far fa-print text-slate-400"></i>
                        Cetak
                    </button>
                    <a href="{{ route('reports.export_persediaan', ['start_date' => $startDate, 'end_date' => $endDate, 'category_id' => $categoryId]) }}"
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

    <style>
        /* ============================= */
        /* HILANGKAN BORDER LUAR        */
        /* ============================= */
        html,
        body {
            margin: 0;
            padding: 0;
            border: none;
        }

        .print-area {
            border: none !important;
            box-shadow: none !important;
        }

        /* ============================= */
        /* TABEL REPORT                 */
        /* ============================= */
        .report-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #1e293b !important;
            padding: 5px;
            font-size: 11px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal !important;
            overflow: hidden;
        }

        .theme-dark .report-table th, 
        .theme-dark .report-table td {
            border-color: #475569 !important;
        }

        .report-table thead {
            display: table-header-group;
        }

        .report-table tr {
            page-break-inside: avoid;
        }

        .split-cell {
            position: relative;
            display: flex;
            width: 100%;
            height: 100%;
        }

        .split-cell .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            white-space: nowrap !important;
        }

        .split-cell .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            white-space: nowrap !important;
        }

        td.split-col {
            position: relative;
            padding: 0 !important;
            vertical-align: top;
        }

        td.split-col::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 1px;
            background: #1e293b;
            pointer-events: none;
        }

        .theme-dark td.split-col::after {
            background: #475569;
        }

        /* ============================= */
        /* PRINT SETTING                */
        /* ============================= */
        @media screen {
            #print-area {
                width: 330mm;
                min-height: 210mm;
                margin: 16px auto;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                background-color: #ffffff !important;
                color: #1e293b !important;
                transition: all 0.3s ease;
            }

            .theme-dark #print-area {
                background-color: #1e293b !important;
                color: #f1f5f9 !important;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            }

            .theme-dark html, .theme-dark body {
                background-color: #020617 !important;
            }

            .report-table {
                width: 100%;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: static !important;
                width: auto !important;
                overflow: visible !important;
                border: none !important;
                background-color: #ffffff !important;
                color: #000000 !important;
            }

            .print-area thead, .print-area tbody, .print-area th, .print-area td {
                border-color: #000000 !important;
                color: #000000 !important;
                background-color: #ffffff !important;
            }

            .print\:hidden {
                display: none !important;
            }

            @page {
                size: 330mm 210mm;
                margin: 12mm;
            }

            body {
                margin: 0;
                background-color: #ffffff !important;
            }

            td.split-col::after {
                background: #000000 !important;
            }
        }
    </style>



    <div class="overflow-x-auto print:overflow-visible pb-4 custom-scrollbar">
        <div id="print-area" class="print-area p-4 sm:p-8 rounded-lg mx-auto" style="min-width: 330mm;">

        <div class="border-b-2 border-black pb-4 mb-4">
            <div class="text-center mb-4">
                <h1 class="text-xl font-bold uppercase">
                    Laporan Persediaan Barang Habis Pakai
                </h1>
                <h5 class="text-sm font-semibold mt-1">
                    Per {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                </h5>
            </div>

            <div class="mb-2 text-md">
                <table class="info-table">
                    <tr>
                        <td width="140"><strong>SKPD</strong></td>
                        <td width="10">:</td>
                        <td>{{ $master['opd']['nama'] ?? null ?: $opd->nama_opd ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kabupaten</strong></td>
                        <td>:</td>
                        <td>Bolaang Mongondow Selatan</td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="report-table text-xs text-center border border-gray-400">
            <colgroup>
                <col style="width:3%">  <!-- No -->
                <col style="width:17%"> <!-- Nama Barang -->
                <col style="width:6%">  <!-- Jmlh -->
                <col style="width:7%">  <!-- Harga -->
                <col style="width:7%">  <!-- Jumlah Harga -->
                <col style="width:6%">  <!-- Masuk Jmlh -->
                <col style="width:7%">  <!-- Harga -->
                <col style="width:7%">  <!-- Jumlah -->
                <col style="width:6%">  <!-- Keluar Jml -->
                <col style="width:7%">  <!-- Harga -->
                <col style="width:7%">  <!-- Jumlah -->
                <col style="width:6%">  <!-- Akhir Jml -->
                <col style="width:7%">  <!-- Harga -->
                <col style="width:7%">  <!-- Jumlah -->
            </colgroup>
            <thead>
                <tr class="bg-white font-bold">
                    <th rowspan="2" class="border border-black px-2 py-2">No</th>
                    <th rowspan="2" class="border border-black px-2 py-2 text-left">Nama Barang</th>

                    <th colspan="3" class="border border-black px-2 py-2">SALDO AWAL</th>
                    <th colspan="3" class="border border-black px-2 py-2">MUTASI MASUK</th>
                    <th colspan="3" class="border border-black px-2 py-2">MUTASI KELUAR</th>
                    <th colspan="3" class="border border-black px-2 py-2">SALDO AKHIR</th>
                </tr>

                <tr class="bg-white text-xs">
                    @for ($i = 0; $i < 4; $i++)
                        <th class="border border-black px-2 py-2">Jmlh Barang</th>
                        <th class="border border-black px-2 py-2">Harga Satuan (Rp)</th>
                        <th class="border border-black px-2 py-2">Jumlah (Rp)</th>
                    @endfor
                </tr>
            </thead>

            <tbody>

                @php
                    $no = 1;
                    $saldo = [];
                    $lastSaldoPerProduct = [];
                    $lastDate = null;
                @endphp

                @forelse($reportData as $item)
                    @php
                        $currentDate = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                    @endphp

                    {{-- HEADER TANGGAL --}}
                    @if ($lastDate != $currentDate)
                        <tr class="bg-white font-bold text-left">
                            <td colspan="14" class="border border-black px-3 py-2">
                                Tanggal :
                                {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        @php $lastDate = $currentDate; @endphp
                    @endif

                    @php
                        $productId = $item['product_id'];
                        $harga = $item['harga'];
                        $satuan = $item['satuan'] ?? '';

                        if (!isset($saldo[$productId])) {
                            $saldo[$productId] = 0;
                        }

                        $saldoAwal = $saldo[$productId];
                        $masuk = $item['masuk'];
                        $keluar = $item['keluar'];

                        $saldoAkhir = $saldoAwal + $masuk - $keluar;
                        $saldo[$productId] = $saldoAkhir;

                        $lastSaldoPerProduct[$productId] = [
                            'saldo' => $saldoAkhir,
                            'harga' => $harga,
                        ];
                    @endphp

                    <tr>
                        <td class="border border-black px-2 py-2 text-center">
                            {{ $no++ }}
                        </td>

                        <td class="border border-black px-2 py-2 text-left">
                            {{ $item['name'] }}
                        </td>

                        {{-- SALDO AWAL --}}
                        <td class="border border-black p-0 split-col">
                            <div class="split-cell">
                                <div class="left font-semibold">{{ $saldoAwal }}</div>
                                <div class="right">{{ $satuan }}</div>
                            </div>
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($harga, 0, ',', '.') }}
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($saldoAwal * $harga, 0, ',', '.') }}
                        </td>

                        {{-- MASUK --}}
                        <td class="border border-black p-0 split-col">
                            <div class="split-cell">
                                <div class="left font-bold text-green-600">{{ $masuk }}</div>
                                <div class="right">{{ $satuan }}</div>
                            </div>
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($harga, 0, ',', '.') }}
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($masuk * $harga, 0, ',', '.') }}
                        </td>

                        {{-- KELUAR --}}
                        <td class="border border-black p-0 split-col">
                            <div class="split-cell">
                                <div class="left font-bold text-red-600">{{ $keluar }}</div>
                                <div class="right">{{ $satuan }}</div>
                            </div>
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($harga, 0, ',', '.') }}
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($keluar * $harga, 0, ',', '.') }}
                        </td>

                        {{-- SALDO AKHIR --}}
                        <td class="border border-black p-0 split-col">
                            <div class="split-cell">
                                <div class="left font-bold">{{ $saldoAkhir }}</div>
                                <div class="right">{{ $satuan }}</div>
                            </div>
                        </td>
                        <td class="text-right border border-black px-2 py-2">
                            {{ number_format($harga, 0, ',', '.') }}
                        </td>
                        <td class="text-right border border-black px-2 py-2 font-bold">
                            {{ number_format($saldoAkhir * $harga, 0, ',', '.') }}
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="14" class="border border-black py-6 text-gray-400 text-center">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse

                {{-- GRAND TOTAL HANYA DARI SALDO TERAKHIR PER PRODUK --}}
                @php
                    $grandTotal = 0;
                    foreach ($lastSaldoPerProduct as $data) {
                        $grandTotal += $data['saldo'] * $data['harga'];
                    }
                @endphp

                <tr class="bg-white font-bold text-right">
                    <td colspan="13" class="border border-black px-3 py-3">
                        TOTAL NILAI PERSEDIAAN
                    </td>
                    <td class="text-right border border-black px-3 py-3">
                        {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>

            </tbody>
        </table>
        {{-- TANDA TANGAN --}}

        <div class="mt-16 w-full text-sm">

            <div class="flex justify-between">

                {{-- KIRI --}}
                <div class="text-center w-1/2">
                    <p class="font-semibold">Dibuat Oleh</p>
                    <p class="font-semibold">Pengurus Barang</p>

                    <div style="height:90px;"></div>

                    <p class="font-bold underline">
                        {{ $opd->pengurus_nama ?? '' }}
                    </p>
                    <p class="font-semibold">
                        NIP. {{ $opd->pengurus_nip ?? '' }}
                    </p>

                </div>

                {{-- KANAN --}}
                <div class="text-center w-1/2">
                    <p class="font-semibold">Mengetahui</p>
                    <p class="font-semibold">Kepala Dinas</p>

                    <div style="height:90px;"></div>

                    <p class="font-bold underline">
                        {{ $opd->kepala_nama ?? '' }}
                    </p>
                    <p class="font-semibold">
                        NIP. {{ $opd->kepala_nip ?? '' }}
                    </p>
                </div>

            </div>

        </div>

    </div>
    </div> <!-- End overflow-x-auto wrapper -->

@endsection
