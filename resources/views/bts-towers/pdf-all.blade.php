<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap BTS - Kab. Bolaang Mongondow Selatan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 9px; color: #1a1a2e; line-height: 1.4; }

        .header { text-align: center; border-bottom: 2px solid #1a1a2e; padding-bottom: 10px; margin-bottom: 12px; }
        .header h1 { font-size: 13px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; }
        .header h2 { font-size: 10px; color: #4a5568; margin-top: 1px; }
        .header h3 { font-size: 9px; font-weight: bold; color: #2d3748; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .header .meta { font-size: 7px; color: #718096; margin-top: 6px; }
        .header .meta strong { color: #2d3748; }
        .header .filters { font-size: 7px; color: #a0aec0; margin-top: 3px; }

        .section-title {
            font-size: 8px; font-weight: bold; color: #fff; text-transform: uppercase;
            letter-spacing: 0.5px; padding: 3px 8px; margin: 10px 0 6px;
        }
        .st-dark { background: #1a1a2e; }
        .st-blue { background: #2b6cb0; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .summary-table th {
            text-align: left; padding: 4px 6px; font-size: 7px; font-weight: bold;
            color: #fff; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .summary-table th.sg1 { background: #2d3748; }
        .summary-table th.sg2 { background: #276749; }
        .summary-table th.sg3 { background: #2b6cb0; }
        .summary-table td { padding: 3px 6px; border: 1px solid #e2e8f0; font-size: 8px; }
        .summary-table tr:nth-child(even) td { background: #f7fafc; }

        .kec-title {
            background: #1a1a2e; color: #fff; padding: 4px 8px; margin-top: 10px; margin-bottom: 0;
            font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .kec-count { float: right; font-size: 7px; color: #a0aec0; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        table.data th {
            text-align: left; padding: 4px 6px; font-size: 7px; font-weight: bold;
            color: #fff; text-transform: uppercase; background: #4a5568; border: 1px solid #cbd5e0;
        }
        table.data td { padding: 4px 6px; border: 1px solid #e2e8f0; font-size: 8px; }
        table.data tbody tr:nth-child(even) td { background: #f7fafc; }

        .footer {
            margin-top: 12px; padding-top: 6px; border-top: 1px solid #cbd5e0;
            font-size: 7px; color: #a0aec0; text-align: center;
        }
        .footer strong { color: #4a5568; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data BTS</h1>
        <h2>Kabupaten Bolaang Mongondow Selatan</h2>
        <h3>Dinas Komunikasi dan Informatika</h3>
        <div class="meta">
            Dicetak: <strong>{{ now()->translatedFormat('d F Y, H:i') }} WITA</strong>
            | Total: <strong>{{ $towers->count() }} BTS</strong>
        </div>
        @if($filterInfo['kecamatan'] || $filterInfo['provider'] || $filterInfo['status_operasional'])
            <div class="filters">
                Filter:
                @if($filterInfo['kecamatan']) Kec. {{ $filterInfo['kecamatan'] }} @endif
                @if($filterInfo['provider']) | {{ $filterInfo['provider'] }} @endif
                @if($filterInfo['status_operasional']) | {{ $filterInfo['status_operasional'] }} @endif
            </div>
        @endif
    </div>

    @forelse ($towersByKecamatan as $kecamatan => $items)
        <div class="kec-title">Kecamatan {{ $kecamatan }} <span class="kec-count">{{ $items->count() }} BTS</span></div>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Desa</th>
                    <th width="30%">Titik Koordinat</th>
                    <th width="15%">Provider</th>
                    <th width="35%">Nama Perusahaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $t)
                    <tr>
                        <td style="text-align:center;">{{ $t->no_urut }}</td>
                        <td>{{ $t->desa ?: '-' }}</td>
                        <td>{{ $t->latitude }}, {{ $t->longitude }}</td>
                        <td>{{ $t->provider ?: '-' }}</td>
                        <td>{{ $t->nama_perusahaan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="text-align:center; margin-top:20px; color:#a0aec0;">Belum ada data BTS.</p>
    @endforelse

    <div class="footer">
        <strong>SIMPATI</strong> | Sistem Informasi Manajemen Persediaan dan Telekomunikasi<br>
        Kabupaten Bolaang Mongondow Selatan, Provinsi Sulawesi Utara<br>
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>