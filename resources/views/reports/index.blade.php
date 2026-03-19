@extends('layouts.admin')

@section('header', 'Laporan Persediaan Barang Habis Pakai')

@section('content')

  <div class="print:hidden rounded-xl shadow-md border bg-white p-4 mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="flex flex-col sm:flex-row gap-4 flex-1">
        <div class="w-full sm:w-1/4">
          <label class="block text-[10px] font-bold text-indigo-700 uppercase mb-1">From Date</label>
          <input type="date" name="start_date" value="{{ $startDate }}"
            class="w-full rounded-lg border-indigo-200 text-sm bg-white shadow-sm focus:ring-2 focus:ring-orange-300 focus:border-orange-500">
        </div>
        <div class="w-full sm:w-1/4">
          <label class="block text-[10px] font-bold text-indigo-700 uppercase mb-1">To Date</label>
          <input type="date" name="end_date" value="{{ $endDate }}"
            class="w-full rounded-lg border-indigo-200 text-sm bg-white shadow-sm focus:ring-2 focus:ring-orange-300 focus:border-orange-500">
        </div>
      </div>
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('dashboard') }}" class="no-print inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 font-bold hover:bg-gray-100 shadow-sm transition flex-1 sm:flex-none">
          <i class="fas fa-arrow-left"></i>
          Kembali
        </a>
        <button type="submit"
          class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-orange-500 text-white font-bold hover:bg-orange-600 shadow-sm flex-1 sm:flex-none">
          <i class="fas fa-filter"></i>
          Filter
        </button>
        <button type="button" onclick="window.print()"
          class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-black text-white font-bold hover:bg-gray-800 shadow-sm flex-1 sm:flex-none">
          <i class="fas fa-print"></i>
          Print
        </button>
      </div>
    </form>
  </div>

  <style>
    /* ============================= */
    /* HILANGKAN BORDER LUAR    */
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
      font-family: 'Nunito', sans-serif;
    }

    /* ============================= */
    /* TABEL REPORT         */
    /* ============================= */
    .report-table {
      border-collapse: collapse;
      width: 100%;
      table-layout: fixed;
    }

    .report-table th,
    .report-table td {
      border: 1px solid #000;
      /* Border hanya di dalam tabel */
      padding: 5px;
      font-size: 12px;
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
      flex: 0 0 var(--qty-w, 38px);
      text-align: center;
      padding: 2px 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .split-cell .right {
      flex: 1 1 auto;
      text-align: center;
      padding: 2px 8px;
    }

    td.split-col {
      position: relative;
      padding: 0 !important;
      --qty-w: 38px;
    }

    td.split-col::after {
      content: '';
      position: absolute;
      left: var(--qty-w, 38px);
      top: -1px;
      bottom: -1px;
      width: 1px;
      background: #9ca3af;
      pointer-events: none;
    }

    /* ============================= */
    /* PRINT SETTING        */
    /* ============================= */
    @media screen {
      #print-area {
        width: 330mm;
        min-height: 210mm;
        margin: 16px auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        background: #ffffff;
        transform-origin: top center;
      }
      
      /* Scale report based on common screen sizes */
      @media (max-width: 1440px) { #print-area { transform: scale(0.9); margin-top: 0; } }
      @media (max-width: 1366px) { #print-area { transform: scale(0.85); margin-top: -20px; } }
      @media (max-width: 1280px) { #print-area { transform: scale(0.78); margin-top: -40px; } }
      @media (max-width: 1024px) { #print-area { transform: scale(0.65); margin-top: -80px; } }

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
      }

      .signature-block {
        break-inside: avoid;
        page-break-inside: avoid;
      }

      .signature-block * {
        break-inside: avoid;
        page-break-inside: avoid;
      }
    }
  </style>



  <div class="print:hidden pb-10">
    <div id="print-area" class="print-area bg-white p-4 sm:p-10 rounded-lg mx-auto">

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

    <table class="w-full border-collapse text-xs text-center border border-gray-400">

      <thead>
        <tr class="bg-gray-200 font-bold">
          <th rowspan="2" class="border border-gray-400 px-1 py-1" style="width: 38px;">No</th>
          <th rowspan="2" class="border border-gray-400 px-2 py-1 text-left">Nama Barang</th>

          <th colspan="3" class="border border-gray-400 px-2 py-1">SALDO AWAL</th>
          <th colspan="3" class="border border-gray-400 px-2 py-1">MUTASI MASUK</th>
          <th colspan="3" class="border border-gray-400 px-2 py-1">MUTASI KELUAR</th>
          <th colspan="3" class="border border-gray-400 px-2 py-1">SALDO AKHIR</th>
        </tr>

        <tr class="bg-gray-100 text-xs">
          @for ($i = 0; $i < 4; $i++)
            <th class="border border-gray-400 px-2 py-1">Jmlh Barang</th>
            <th class="border border-gray-400 px-2 py-1">Harga Satuan (Rp)</th>
            <th class="border border-gray-400 px-2 py-1">Jumlah (Rp)</th>
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
            <tr class="bg-gray-100 font-bold text-left">
              <td colspan="14" class="border border-gray-400 px-3 py-1">
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
            <td class="border border-gray-400 px-1 py-1 text-center" style="width: 38px;">
              {{ $no++ }}
            </td>

            <td class="border border-gray-400 px-2 py-1 text-left">
              {{ $item['name'] }}
            </td>

            {{-- SALDO AWAL --}}
            <td class="border border-gray-400 p-0 split-col">
              <div class="split-cell">
                <div class="left font-semibold">{{ $saldoAwal }}</div>
                <div class="right">{{ $satuan }}</div>
              </div>
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($harga, 0, ',', '.') }}
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($saldoAwal * $harga, 0, ',', '.') }}
            </td>

            {{-- MASUK --}}
            <td class="border border-gray-400 p-0 split-col">
              <div class="split-cell">
                <div class="left font-bold text-green-600">{{ $masuk }}</div>
                <div class="right">{{ $satuan }}</div>
              </div>
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($harga, 0, ',', '.') }}
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($masuk * $harga, 0, ',', '.') }}
            </td>

            {{-- KELUAR --}}
            <td class="border border-gray-400 p-0 split-col">
              <div class="split-cell">
                <div class="left font-bold text-red-600">{{ $keluar }}</div>
                <div class="right">{{ $satuan }}</div>
              </div>
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($harga, 0, ',', '.') }}
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($keluar * $harga, 0, ',', '.') }}
            </td>

            {{-- SALDO AKHIR --}}
            <td class="border border-gray-400 p-0 split-col">
              <div class="split-cell">
                <div class="left font-bold">{{ $saldoAkhir }}</div>
                <div class="right">{{ $satuan }}</div>
              </div>
            </td>
            <td class="border border-gray-400 px-2 py-1 text-right">
              {{ number_format($harga, 0, ',', '.') }}
            </td>
            <td class="border border-gray-400 px-2 py-1 font-bold text-right">
              {{ number_format($saldoAkhir * $harga, 0, ',', '.') }}
            </td>
          </tr>

        @empty
          <tr>
            <td colspan="14" class="border border-gray-400 py-6 text-gray-400 text-center">
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

        <tr class="bg-gray-200 font-bold text-right">
          <td colspan="13" class="border border-gray-400 px-3 py-2">
            TOTAL NILAI PERSEDIAAN
          </td>
          <td class="border border-gray-400 px-3 py-2 text-right">
            {{ number_format($grandTotal, 0, ',', '.') }}
          </td>
        </tr>

      </tbody>
    </table>
    {{-- TANDA TANGAN --}}

    <div class="mt-8 w-full text-sm signature-block">

      <div class="flex justify-between">

        {{-- KIRI --}}
        <div class="text-center w-1/2">
          <p class="font-semibold">Dibuat Oleh</p>
          <p class="font-semibold">Pengurus Barang</p>

          <div style="height:60px;"></div>

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

          <div style="height:60px;"></div>

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
