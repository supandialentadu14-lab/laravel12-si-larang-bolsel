<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan BTS - {{ $btsTower->kode_bts }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #1a1a2e; line-height: 1.5; background: #fff; }

        .header { border-bottom: 3px solid #1a1a2e; padding-bottom: 12px; margin-bottom: 16px; }
        .header-top { display: table; width: 100%; margin-bottom: 8px; }
        .header-logo { display: table-cell; width: 50px; vertical-align: top; }
        .header-logo .logo-circle {
            width: 42px; height: 42px; border-radius: 50%; background: #1a1a2e;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px; font-weight: 900; text-align: center;
            line-height: 42px;
        }
        .header-text { display: table-cell; vertical-align: top; padding-left: 12px; }
        .header-text h1 { font-size: 15px; font-weight: 900; color: #1a1a2e; letter-spacing: 0.5px; text-transform: uppercase; }
        .header-text h2 { font-size: 11px; font-weight: 600; color: #4a5568; margin-top: 1px; }
        .header-text h3 { font-size: 10px; font-weight: 700; color: #2d3748; margin-top: 2px; text-transform: uppercase; letter-spacing: 1px; }
        .header-badge { display: table-cell; width: 120px; vertical-align: top; text-align: right; }
        .badge-box {
            display: inline-block; padding: 6px 14px; border-radius: 6px;
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-aktif { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-tidak-aktif { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        .badge-maintenance { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        .meta-line { font-size: 9px; color: #718096; margin-top: 6px; }
        .meta-line strong { color: #2d3748; }

        .section-title {
            font-size: 10px; font-weight: 800; color: #fff; text-transform: uppercase;
            letter-spacing: 1px; padding: 5px 10px; margin: 14px 0 8px; border-radius: 4px;
        }
        .st-dark { background: #1a1a2e; }
        .st-blue { background: #2b6cb0; }
        .st-green { background: #276749; }

        table.detail { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        table.detail th {
            text-align: left; padding: 6px 10px; font-size: 9px; font-weight: 700;
            color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px;
            width: 28%; background: #f7fafc; border: 1px solid #e2e8f0; vertical-align: top;
        }
        table.detail td {
            padding: 6px 10px; border: 1px solid #e2e8f0; color: #1a202c; font-size: 11px;
        }
        table.detail tr:nth-child(even) td { background: #f7fafc; }

        .status-pill {
            display: inline-block; padding: 2px 10px; border-radius: 10px;
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .pill-aktif { background: #c6f6d5; color: #22543d; }
        .pill-tidak-aktif { background: #e2e8f0; color: #4a5568; }
        .pill-maintenance { background: #fefcbf; color: #744210; }
        .pill-baik { background: #c6f6d5; color: #22543d; }
        .pill-rusak-ringan { background: #fefcbf; color: #744210; }
        .pill-rusak-berat { background: #fed7d7; color: #9b2c2c; }
        .pill-perlu { background: #feebc8; color: #7b341e; }

        .map-container { text-align: center; margin: 12px 0; }
        .map-img { width: 100%; max-width: 480px; border: 1px solid #cbd5e0; border-radius: 4px; }
        .map-caption { font-size: 8px; color: #a0aec0; margin-top: 4px; }

        .info-grid { display: table; width: 100%; margin-bottom: 10px; }
        .info-card {
            display: table-cell; width: 25%; padding: 8px 10px; text-align: center;
            border: 1px solid #e2e8f0; vertical-align: top;
        }
        .info-card:first-child { border-radius: 4px 0 0 4px; }
        .info-card:last-child { border-radius: 0 4px 4px 0; }
        .info-card .ic-label { font-size: 8px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        .info-card .ic-value { font-size: 13px; font-weight: 800; color: #1a202c; margin-top: 2px; }
        .info-card .ic-sub { font-size: 8px; color: #a0aec0; }

        .footer {
            margin-top: 20px; padding-top: 10px; border-top: 2px solid #1a1a2e;
            font-size: 8px; color: #a0aec0; text-align: center;
        }
        .footer strong { color: #4a5568; }

        .notes-box {
            background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px;
            padding: 8px 10px; margin-top: 8px; font-size: 10px; color: #4a5568;
        }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-top">
            <div class="header-logo">
                <div class="logo-circle">BTS</div>
            </div>
            <div class="header-text">
                <h1>Laporan Data BTS</h1>
                <h2>Kabupaten Bolaang Mongondow Selatan</h2>
                <h3>Dinas Komunikasi dan Informatika</h3>
            </div>
            <div class="header-badge">
                @if(($btsTower->status_operasional ?? '') === 'Aktif')
                    <span class="badge-box badge-aktif">&#9679; Aktif</span>
                @elseif(($btsTower->status_operasional ?? '') === 'Maintenance')
                    <span class="badge-box badge-maintenance">&#9679; Maintenance</span>
                @else
                    <span class="badge-box badge-tidak-aktif">&#9679; Tidak Aktif</span>
                @endif
            </div>
        </div>
        <div class="meta-line">
            <strong>Kode:</strong> {{ $btsTower->kode_bts }} &nbsp;&middot;&nbsp;
            <strong>Provider:</strong> {{ $btsTower->provider }} &nbsp;&middot;&nbsp;
            <strong>Dicetak:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WITA
        </div>
    </div>

    {{-- RINGKASAN CEPAT --}}
    <div class="info-grid">
        <div class="info-card">
            <div class="ic-label">Kecamatan</div>
            <div class="ic-value">{{ $btsTower->kecamatan }}</div>
        </div>
        <div class="info-card">
            <div class="ic-label">Tinggi Tower</div>
            <div class="ic-value">{{ $btsTower->tinggi_tower ? $btsTower->tinggi_tower . ' m' : '-' }}</div>
        </div>
        <div class="info-card">
            <div class="ic-label">Kondisi</div>
            <div class="ic-value">
                @if($btsTower->kondisi)
                    <span class="status-pill pill-{{ str_replace(' ', '-', strtolower($btsTower->kondisi)) }}">{{ $btsTower->kondisi }}</span>
                @else -
                @endif
            </div>
        </div>
        <div class="info-card">
            <div class="ic-label">Tahun</div>
            <div class="ic-value">{{ $btsTower->tahun_dibangun ?: '-' }}</div>
        </div>
    </div>

    {{-- INFORMASI UMUM --}}
    <div class="section-title st-dark">Informasi Umum</div>
    <table class="detail">
        <tr><th>Kode BTS</th><td>{{ $btsTower->kode_bts }}</td></tr>
        <tr><th>Nama BTS</th><td>{{ $btsTower->nama_bts }}</td></tr>
        <tr><th>Provider</th><td>{{ $btsTower->provider }}</td></tr>
        <tr><th>Kecamatan</th><td>{{ $btsTower->kecamatan }}</td></tr>
        <tr><th>Desa / Kelurahan</th><td>{{ $btsTower->desa ?: '-' }}</td></tr>
        <tr><th>Alamat Lengkap</th><td>{{ $btsTower->alamat ?: '-' }}</td></tr>
        <tr><th>Koordinat</th><td>{{ $btsTower->latitude }}, {{ $btsTower->longitude }}</td></tr>
    </table>

    {{-- SPESIFIKASI --}}
    <div class="section-title st-blue">Spesifikasi Tower</div>
    <table class="detail">
        <tr>
            <th>Tinggi Tower</th>
            <td>{{ $btsTower->tinggi_tower ? $btsTower->tinggi_tower . ' meter' : '-' }}</td>
        </tr>
        <tr>
            <th>Tipe Tower</th>
            <td>{{ $btsTower->tipe_tower ?: '-' }}</td>
        </tr>
        <tr>
            <th>Tahun Dibangun</th>
            <td>{{ $btsTower->tahun_dibangun ?: '-' }}</td>
        </tr>
        <tr>
            <th>Kondisi Fisik</th>
            <td>
                @if($btsTower->kondisi)
                    <span class="status-pill pill-{{ str_replace(' ', '-', strtolower($btsTower->kondisi)) }}">{{ $btsTower->kondisi }}</span>
                @else -
                @endif
            </td>
        </tr>
        <tr>
            <th>Status Operasional</th>
            <td>
                @if($btsTower->status_operasional)
                    <span class="status-pill pill-{{ str_replace(' ', '-', strtolower($btsTower->status_operasional)) }}">{{ $btsTower->status_operasional }}</span>
                @else -
                @endif
            </td>
        </tr>
    </table>

    {{-- KETERANGAN --}}
    @if($btsTower->keterangan)
        <div class="section-title st-green">Keterangan</div>
        <div class="notes-box">{!! nl2br(e($btsTower->keterangan)) !!}</div>
    @endif

    {{-- PETA --}}
    @if ($mapImage)
        <div class="section-title st-dark">Peta Lokasi</div>
        <div class="map-container">
            <img src="{{ $mapImage }}" class="map-img" alt="Peta Lokasi BTS">
            <div class="map-caption">Peta lokasi menunjukkan posisi BTS berdasarkan koordinat GPS</div>
        </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <strong>SIMPATI</strong> &mdash; Sistem Informasi Manajemen Persediaan dan Telekomunikasi<br>
        Kabupaten Bolaang Mongondow Selatan, Provinsi Sulawesi Utara<br>
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>
