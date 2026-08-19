@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
    .bts-compare { color: #e5e7eb; font-family: 'Inter', system-ui, sans-serif; }

    .bts-compare .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: 12px;
    }
    .bts-compare .page-title {
        font-size: 1.5rem; font-weight: 900; color: #f9fafb; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .bts-compare .page-title i { color: #818cf8; font-size: 1.2rem; }
    .bts-compare .page-subtitle { font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; margin-top: 4px; }
    .bts-compare .page-subtitle span { color: #a5b4fc; font-weight: 800; }

    .bts-compare .btn-nav {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 10px; font-size: 11px; font-weight: 700;
        text-decoration: none; border: 1px solid transparent; cursor: pointer;
        transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-compare .btn-nav:hover { filter: brightness(1.2); transform: translateY(-1px); }
    .bts-compare .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }
    .bts-compare .btn-indigo { background: #4f46e5; color: #fff; }

    .bts-compare .compare-card {
        background: #171e33; border: 1px solid #232b4a; border-radius: 16px;
        overflow: hidden;
    }

    .bts-compare .compare-scroll {
        overflow-x: auto; -webkit-overflow-scrolling: touch;
        scrollbar-width: thin; scrollbar-color: #2a3252 transparent;
    }
    .bts-compare .compare-scroll::-webkit-scrollbar { height: 6px; }
    .bts-compare .compare-scroll::-webkit-scrollbar-track { background: transparent; }
    .bts-compare .compare-scroll::-webkit-scrollbar-thumb { background: #2a3252; border-radius: 3px; }

    .bts-compare table.compare-tbl {
        width: 100%; border-collapse: collapse; min-width: 600px;
    }
    .bts-compare table.compare-tbl th,
    .bts-compare table.compare-tbl td {
        padding: 12px 16px; text-align: left; vertical-align: middle;
    }
    .bts-compare table.compare-tbl thead th {
        background: #1a2035; border-bottom: 2px solid #232b4a;
        font-size: 11px; font-weight: 800; color: #a5b4fc;
        text-transform: uppercase; letter-spacing: 0.12em;
        position: sticky; top: 0; z-index: 2;
    }
    .bts-compare table.compare-tbl thead th:first-child {
        min-width: 160px; color: #6b7280; background: #161c30;
    }
    .bts-compare table.compare-tbl thead th .th-inner {
        display: flex; flex-direction: column; align-items: flex-start; gap: 4px;
    }
    .bts-compare table.compare-tbl thead th .th-name {
        font-size: 14px; font-weight: 900; color: #f9fafb;
        text-transform: none; letter-spacing: 0;
    }
    .bts-compare table.compare-tbl thead th .th-sub {
        font-size: 9px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.1em;
    }

    .bts-compare table.compare-tbl tbody tr {
        border-bottom: 1px solid #1e2540;
        transition: background 0.15s;
    }
    .bts-compare table.compare-tbl tbody tr:hover {
        background: rgba(99,102,241,0.04);
    }
    .bts-compare table.compare-tbl tbody tr:last-child { border-bottom: none; }

    .bts-compare table.compare-tbl tbody td.row-header {
        font-size: 10px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.1em;
        background: rgba(255,255,255,0.015); white-space: nowrap;
        border-right: 1px solid #1e2540;
    }
    .bts-compare table.compare-tbl tbody td.row-header .row-icon {
        display: inline-block; width: 14px; text-align: center; margin-right: 6px;
        color: #4b5563; font-size: 10px;
    }
    .bts-compare table.compare-tbl tbody td.row-value {
        font-size: 13px; font-weight: 500; color: #e5e7eb;
    }

    .bts-compare .tower-nama {
        font-size: 15px; font-weight: 900; color: #f9fafb;
    }
    .bts-compare .tower-kode {
        font-size: 11px; font-weight: 700; color: #818cf8;
        font-family: 'JetBrains Mono', monospace;
    }

    .bts-compare .provider-dot {
        display: inline-block; width: 8px; height: 8px;
        border-radius: 50%; margin-right: 6px; vertical-align: middle;
    }

    .bts-compare .coord-text {
        font-family: 'JetBrains Mono', monospace; font-size: 11px; color: #9ca3af;
    }
    .bts-compare .coord-link {
        font-size: 10px; color: #818cf8; text-decoration: none;
        margin-left: 6px; transition: color 0.2s;
    }
    .bts-compare .coord-link:hover { color: #a5b4fc; }

    .bts-compare .badge {
        display: inline-flex; align-items: center; padding: 3px 10px;
        border-radius: 6px; font-size: 11px; font-weight: 700;
    }
    .bts-compare .badge-baik { background: rgba(16,185,129,0.12); color: #34d399; }
    .bts-compare .badge-rusak-ringan { background: rgba(245,158,11,0.12); color: #fbbf24; }
    .bts-compare .badge-rusak-berat { background: rgba(239,68,68,0.12); color: #f87171; }
    .bts-compare .badge-aktif { background: rgba(16,185,129,0.12); color: #34d399; }
    .bts-compare .badge-maintenance { background: rgba(245,158,11,0.12); color: #fbbf24; }
    .bts-compare .badge-tidak-aktif { background: rgba(156,163,175,0.12); color: #9ca3af; }
    .bts-compare .badge-neutral { background: rgba(156,163,175,0.08); color: #6b7280; }

    .bts-compare .empty-val { color: #4b5563; font-style: italic; font-size: 12px; }

    .bts-compare .thumb-photo {
        width: 48px; height: 48px; border-radius: 10px; object-fit: cover;
        border: 1px solid #232b4a;
    }
    .bts-compare .thumb-placeholder {
        width: 48px; height: 48px; border-radius: 10px;
        border: 1px solid #232b4a; display: flex; align-items: center;
        justify-content: center; background: rgba(255,255,255,0.03);
        color: #4b5563; font-size: 14px;
    }

    .bts-compare .count-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 8px; font-size: 13px;
        font-weight: 800; background: rgba(99,102,241,0.1);
        color: #818cf8;
    }
    .bts-compare .count-badge i { font-size: 10px; }

    .bts-compare .mobile-cards { display: none; }

    @media (max-width: 768px) {
        .bts-compare .page-header { flex-direction: column; align-items: flex-start; }
        .bts-compare .page-title { font-size: 1.15rem; }
        .bts-compare .desktop-table { display: none; }
        .bts-compare .mobile-cards { display: block; }

        .bts-compare .m-card {
            background: #171e33; border: 1px solid #232b4a; border-radius: 16px;
            margin-bottom: 16px; overflow: hidden;
        }
        .bts-compare .m-card-header {
            padding: 16px; background: #1a2035;
            border-bottom: 1px solid #232b4a;
            display: flex; justify-content: space-between; align-items: flex-start;
        }
        .bts-compare .m-card-body { padding: 0; }
        .bts-compare .m-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 12px 16px; border-bottom: 1px solid #1e2540;
            gap: 12px;
        }
        .bts-compare .m-row:last-child { border-bottom: none; }
        .bts-compare .m-row-label {
            font-size: 10px; font-weight: 700; color: #6b7280;
            text-transform: uppercase; letter-spacing: 0.1em;
            white-space: nowrap; min-width: 110px;
        }
        .bts-compare .m-row-value {
            font-size: 13px; font-weight: 500; color: #e5e7eb;
            text-align: right; flex: 1;
        }
    }
</style>

<div class="container-fluid py-4 bts-compare">
    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-columns"></i> Perbandingan BTS
            </h1>
            <p class="page-subtitle">Membandingkan <span>{{ count($towers) }}</span> menara</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('bts-towers.index') }}" class="btn-nav btn-ghost">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- EMPTY STATE --}}
    @if(empty($towers) || count($towers) < 2)
        <div class="compare-card">
            <div style="padding:3rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:12px;opacity:0.2;"><i class="fas fa-columns"></i></div>
                <div style="font-size:13px;color:#6b7280;font-weight:700;margin-bottom:6px;">Tidak cukup data untuk perbandingan</div>
                <div style="font-size:11px;color:#4b5563;">Pilih minimal 2 BTS tower untuk memulai perbandingan.</div>
            </div>
        </div>
    @else
        {{-- DESKTOP: TABLE --}}
        <div class="compare-card desktop-table">
            <div class="compare-scroll">
                <table class="compare-tbl">
                    <thead>
                        <tr>
                            <th>Atribut</th>
                            @foreach($towers as $tower)
                                <th>
                                    <div class="th-inner">
                                        <span class="th-name">{{ $tower->nama_bts }}</span>
                                        <span class="th-sub">{{ $tower->kode_bts }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Kode BTS --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-hashtag"></i></span>Kode BTS</td>
                            @foreach($towers as $tower)
                                <td class="row-value"><span class="tower-kode">{{ $tower->kode_bts }}</span></td>
                            @endforeach
                        </tr>

                        {{-- Nama BTS --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-tower-cell"></i></span>Nama BTS</td>
                            @foreach($towers as $tower)
                                <td class="row-value"><span class="tower-nama">{{ $tower->nama_bts }}</span></td>
                            @endforeach
                        </tr>

                        {{-- Provider --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-signal"></i></span>Provider</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    <span class="provider-dot" style="background:{{ $providerColors[$tower->provider] ?? '#95a5a6' }};"></span>{{ $tower->provider }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Nama Perusahaan --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-building"></i></span>Nama Perusahaan</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->nama_perusahaan ?: '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Kecamatan --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-map-marker-alt"></i></span>Kecamatan</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->kecamatan ?: '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Desa --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-location-dot"></i></span>Desa / Kelurahan</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->desa ?: '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Koordinat --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-globe"></i></span>Koordinat</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    @if($tower->latitude && $tower->longitude)
                                        <span class="coord-text">{{ $tower->latitude }}, {{ $tower->longitude }}</span>
                                        <a href="https://www.google.com/maps?q={{ $tower->latitude }},{{ $tower->longitude }}" target="_blank" class="coord-link" title="Buka di Google Maps">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @else
                                        <span class="empty-val">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Tinggi Tower --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-ruler-vertical"></i></span>Tinggi Tower</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->tinggi_tower ? $tower->tinggi_tower . ' m' : '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Tipe Tower --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-layer-group"></i></span>Tipe Tower</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->tipe_tower ?: '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Kondisi --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-heartbeat"></i></span>Kondisi</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    @if($tower->kondisi)
                                        <span class="badge {{ $tower->kondisi === 'Baik' ? 'badge-baik' : ($tower->kondisi === 'Rusak Berat' ? 'badge-rusak-berat' : 'badge-rusak-ringan') }}">
                                            {{ $tower->kondisi }}
                                        </span>
                                    @else
                                        <span class="empty-val">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Status Operasional --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-toggle-on"></i></span>Status</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    @if($tower->status_operasional)
                                        <span class="badge {{ $tower->status_operasional === 'Aktif' ? 'badge-aktif' : ($tower->status_operasional === 'Maintenance' ? 'badge-maintenance' : 'badge-tidak-aktif') }}">
                                            {{ $tower->status_operasional }}
                                        </span>
                                    @else
                                        <span class="empty-val">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Tahun Dibangun --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-calendar"></i></span>Tahun Dibangun</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->tahun_dibangun ?: '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Jumlah Catatan --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-clipboard-list"></i></span>Catatan</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    <span class="count-badge">
                                        <i class="fas fa-file-alt"></i> {{ optional($tower->notes)->count() }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- Radius Cakupan --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-broadcast-tower"></i></span>Radius Cakupan</td>
                            @foreach($towers as $tower)
                                <td class="row-value">{{ $tower->radius_cakupan ? $tower->radius_cakupan . ' km' : '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- Foto --}}
                        <tr>
                            <td class="row-header"><span class="row-icon"><i class="fas fa-camera"></i></span>Foto</td>
                            @foreach($towers as $tower)
                                <td class="row-value">
                                    @if($tower->foto_url)
                                        <img src="{{ $tower->foto_url }}" alt="{{ $tower->nama_bts }}" class="thumb-photo">
                                    @else
                                        <div class="thumb-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE: CARDS --}}
        <div class="mobile-cards">
            @foreach($towers as $tower)
                <div class="m-card">
                    <div class="m-card-header">
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                <span class="badge {{ ($tower->status_operasional ?? '') === 'Aktif' ? 'badge-aktif' : (($tower->status_operasional ?? '') === 'Maintenance' ? 'badge-maintenance' : 'badge-tidak-aktif') }}" style="font-size:9px;padding:2px 8px;">
                                    <i class="fas fa-signal"></i> {{ $tower->status_operasional ?? '-' }}
                                </span>
                            </div>
                            <div class="tower-nama" style="font-size:1.1rem;">{{ $tower->nama_bts }}</div>
                            <div class="tower-kode" style="margin-top:2px;">{{ $tower->kode_bts }}</div>
                        </div>
                        @if($tower->foto_url)
                            <img src="{{ $tower->foto_url }}" alt="{{ $tower->nama_bts }}" class="thumb-photo" style="width:56px;height:56px;">
                        @else
                            <div class="thumb-placeholder" style="width:56px;height:56px;"><i class="fas fa-image"></i></div>
                        @endif
                    </div>
                    <div class="m-card-body">
                        <div class="m-row">
                            <span class="m-row-label">Provider</span>
                            <span class="m-row-value">
                                <span class="provider-dot" style="background:{{ $providerColors[$tower->provider] ?? '#95a5a6' }};"></span>{{ $tower->provider }}
                            </span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Nama Perusahaan</span>
                            <span class="m-row-value">{{ $tower->nama_perusahaan ?: '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Kecamatan</span>
                            <span class="m-row-value">{{ $tower->kecamatan ?: '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Desa</span>
                            <span class="m-row-value">{{ $tower->desa ?: '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Koordinat</span>
                            <span class="m-row-value">
                                @if($tower->latitude && $tower->longitude)
                                    <span class="coord-text">{{ $tower->latitude }}, {{ $tower->longitude }}</span>
                                    <a href="https://www.google.com/maps?q={{ $tower->latitude }},{{ $tower->longitude }}" target="_blank" class="coord-link"><i class="fas fa-external-link-alt"></i></a>
                                @else -
                                @endif
                            </span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Tinggi Tower</span>
                            <span class="m-row-value">{{ $tower->tinggi_tower ? $tower->tinggi_tower . ' m' : '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Tipe Tower</span>
                            <span class="m-row-value">{{ $tower->tipe_tower ?: '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Kondisi</span>
                            <span class="m-row-value">
                                @if($tower->kondisi)
                                    <span class="badge {{ $tower->kondisi === 'Baik' ? 'badge-baik' : ($tower->kondisi === 'Rusak Berat' ? 'badge-rusak-berat' : 'badge-rusak-ringan') }}">{{ $tower->kondisi }}</span>
                                @else -
                                @endif
                            </span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Status</span>
                            <span class="m-row-value">
                                @if($tower->status_operasional)
                                    <span class="badge {{ $tower->status_operasional === 'Aktif' ? 'badge-aktif' : ($tower->status_operasional === 'Maintenance' ? 'badge-maintenance' : 'badge-tidak-aktif') }}">{{ $tower->status_operasional }}</span>
                                @else -
                                @endif
                            </span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Tahun Dibangun</span>
                            <span class="m-row-value">{{ $tower->tahun_dibangun ?: '-' }}</span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Catatan</span>
                            <span class="m-row-value">
                                <span class="count-badge"><i class="fas fa-file-alt"></i> {{ optional($tower->notes)->count() }}</span>
                            </span>
                        </div>
                        <div class="m-row">
                            <span class="m-row-label">Radius Cakupan</span>
                            <span class="m-row-value">{{ $tower->radius_cakupan ? $tower->radius_cakupan . ' km' : '-' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
