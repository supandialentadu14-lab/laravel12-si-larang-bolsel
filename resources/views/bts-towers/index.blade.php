@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<style>
    .bts-index { color: #e5e7eb; font-family: 'Inter', system-ui, sans-serif; }

    .bts-index .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        flex-wrap: wrap; gap: 12px; margin-bottom: 1.25rem;
    }
    .bts-index .page-title { font-size: 1.25rem; font-weight: 900; color: #f9fafb; margin: 0; }
    .bts-index .page-sub { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 2px; }
    .bts-index .btn-act {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px;
        border-radius: 10px; font-size: 11px; font-weight: 700; text-decoration: none;
        border: 1px solid transparent; cursor: pointer; transition: all 0.2s;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-index .btn-act:hover { filter: brightness(1.15); transform: translateY(-1px); }
    .bts-index .btn-indigo { background: #4f46e5; color: #fff; }
    .bts-index .btn-green { background: rgba(16,185,129,0.1); color: #34d399; border-color: rgba(16,185,129,0.2); }
    .bts-index .btn-purple { background: rgba(139,92,246,0.1); color: #a78bfa; border-color: rgba(139,92,246,0.2); }
    .bts-index .btn-red { background: rgba(239,68,68,0.1); color: #f87171; border-color: rgba(239,68,68,0.2); }
    .bts-index .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }

    .bts-index .alert-success-c {
        background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15);
        color: #34d399; border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; font-size: 12px; font-weight: 600;
    }

    .bts-index .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 1.25rem;
    }
    .bts-index .stat-card {
        background: #171e33; border: 1px solid #232b4a; border-radius: 14px;
        padding: 16px 18px; display: flex; align-items: center; gap: 14px;
        transition: border-color 0.2s;
    }
    .bts-index .stat-card:hover { border-color: rgba(99,102,241,0.2); }
    .bts-index .stat-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
    }
    .bts-index .stat-label { font-size: 9px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.12em; }
    .bts-index .stat-value { font-size: 1.35rem; font-weight: 900; color: #f9fafb; line-height: 1; }

    .bts-index .section-card {
        background: #171e33; border: 1px solid #232b4a; border-radius: 16px;
        margin-bottom: 1.25rem; overflow: hidden;
    }
    .bts-index .section-header {
        padding: 14px 20px; border-bottom: 1px solid #232b4a;
        display: flex; justify-content: space-between; align-items: center;
    }
    .bts-index .section-title {
        font-size: 11px; font-weight: 800; color: #a5b4fc;
        text-transform: uppercase; letter-spacing: 0.15em; margin: 0;
    }
    .bts-index .section-body { padding: 18px 20px; }

    .bts-index .filter-grid {
        display: grid; grid-template-columns: 2fr 1.3fr 1.3fr 1.3fr auto; gap: 12px; align-items: end;
    }
    @media (max-width: 900px) { .bts-index .filter-grid { grid-template-columns: 1fr 1fr; } }
    .bts-index .field label {
        display: block; font-size: 9px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px;
    }
    .bts-index .field input,
    .bts-index .field select {
        width: 100%; background: #0f1428; border: 1px solid #2a3252; border-radius: 10px;
        padding: 9px 12px; color: #e5e7eb; font-size: 12px; box-sizing: border-box;
    }
    .bts-index .field input:focus,
    .bts-index .field select:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,0.1); }
    .bts-index .btn-filter {
        background: #1e2745; border: 1px solid #2a3252; color: #a5b4fc;
        border-radius: 10px; padding: 9px 14px; height: 38px; cursor: pointer;
        transition: all 0.2s;
    }
    .bts-index .btn-filter:hover { background: #262f52; }

    .bts-index #bts-map { height: 420px; width: 100%; border-radius: 12px; }

    .bts-marker-tooltip {
        background: rgba(15,18,37,0.92) !important;
        color: #e5e7eb !important;
        border: 1px solid rgba(99,102,241,0.3) !important;
        border-radius: 8px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4) !important;
    }
    .bts-marker-tooltip::before {
        border-right-color: rgba(15,18,37,0.92) !important;
    }
    .bts-marker-tooltip-top::before {
        border-bottom-color: rgba(15,18,37,0.92) !important;
    }

    .bts-index .bulk-bar {
        display: none; padding: 10px 20px; background: rgba(239,68,68,0.06);
        border-bottom: 1px solid rgba(239,68,68,0.1); align-items: center; gap: 10px;
    }
    .bts-index .bulk-bar.active { display: flex; }
    .bts-index .bulk-count { font-size: 11px; color: #f87171; font-weight: 700; }

    .bts-index table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .bts-index thead th {
        text-align: left; padding: 12px 14px; font-size: 9px; font-weight: 700;
        color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em;
        background: rgba(255,255,255,0.02); border-bottom: 1px solid #232b4a;
    }
    .bts-index tbody td {
        padding: 12px 14px; border-bottom: 1px solid #1e2540; color: #d1d5db; vertical-align: middle;
    }
    .bts-index tbody tr:hover { background: rgba(99,102,241,0.04); }
    .bts-index tbody tr:last-child td { border-bottom: none; }
    .bts-index .empty-row { text-align: center; padding: 3rem; color: #4b5563; font-size: 12px; }

    .bts-index .tower-name { font-weight: 700; color: #e5e7eb; }
    .bts-index .tower-sub { font-size: 10px; color: #6b7280; margin-top: 1px; }

    .bts-index .badge-c {
        display: inline-flex; padding: 3px 10px; border-radius: 6px;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-index .badge-green { background: rgba(16,185,129,0.12); color: #34d399; }
    .bts-index .badge-yellow { background: rgba(245,158,11,0.12); color: #fbbf24; }
    .bts-index .badge-red { background: rgba(239,68,68,0.12); color: #f87171; }
    .bts-index .badge-blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
    .bts-index .badge-gray { background: rgba(156,163,175,0.12); color: #9ca3af; }

    .bts-index .provider-pill {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 600; color: #d1d5db;
    }
    .bts-index .provider-dot { width: 7px; height: 7px; border-radius: 50%; }

    .bts-index .action-icons { display: flex; gap: 5px; justify-content: flex-end; }
    .bts-index .icon-btn {
        width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 7px; border: none; text-decoration: none; font-size: 11px; cursor: pointer;
        transition: all 0.15s;
    }
    .bts-index .icon-info { background: rgba(59,130,246,0.1); color: #60a5fa; }
    .bts-index .icon-warning { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .bts-index .icon-dark { background: rgba(156,163,175,0.1); color: #9ca3af; }
    .bts-index .icon-danger { background: rgba(239,68,68,0.1); color: #f87171; }
    .bts-index .icon-btn:hover { filter: brightness(1.3); transform: scale(1.05); }

    .bts-index .pagination-wrap { padding: 14px 20px; border-top: 1px solid #1e2540; }

    .bts-index .cb-custom {
        width: 16px; height: 16px; border-radius: 4px; accent-color: #4f46e5; cursor: pointer;
    }

    @media (max-width: 768px) {
        .bts-index .stats-row { grid-template-columns: repeat(2, 1fr); }
        .bts-index .page-title { font-size: 1rem; }
    }
</style>

<div class="container-fluid py-4 bts-index">
    {{-- FLASH --}}
    @if (session('success'))
        <div class="alert-success-c"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <h4 class="page-title">Peta & Data BTS</h4>
            <p class="page-sub">Kabupaten Bolaang Mongondow Selatan &middot; Dinas Komunikasi dan Informatika</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('bts-towers.report-all', request()->query()) }}" target="_blank" class="btn-act btn-purple">
                <i class="fas fa-file-pdf"></i> Laporan Kab.
            </a>
            <a href="{{ route('bts-towers.export-excel', request()->query()) }}" class="btn-act btn-green">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <a href="{{ route('bts-towers.export-geojson', request()->query()) }}" class="btn-act btn-ghost" style="color:#60a5fa;border-color:rgba(96,165,250,0.2);">
                <i class="fas fa-globe"></i> GeoJSON
            </a>
            <a href="{{ route('bts-towers.export-kml', request()->query()) }}" class="btn-act btn-ghost" style="color:#f472b6;border-color:rgba(244,114,182,0.2);">
                <i class="fas fa-map-marked-alt"></i> KML
            </a>
            <a href="{{ route('bts-towers.import-form') }}" class="btn-act btn-ghost" style="color:#fbbf24;border-color:rgba(251,191,36,0.2);">
                <i class="fas fa-file-import"></i> Import
            </a>
            <a href="{{ route('bts-towers.alerts') }}" class="btn-act btn-ghost" style="color:#f87171;border-color:rgba(248,113,113,0.2);position:relative;">
                <i class="fas fa-bell"></i> Notifikasi
            </a>
            <a href="{{ route('bts-towers.create') }}" class="btn-act btn-indigo">
                <i class="fas fa-plus"></i> Tambah BTS
            </a>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#818cf8;"><i class="fas fa-tower-cell"></i></div>
            <div>
                <div class="stat-label">Total BTS</div>
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399;"><i class="fas fa-signal"></i></div>
            <div>
                <div class="stat-label">Aktif</div>
                <div class="stat-value" style="color:#34d399;">{{ number_format($stats['aktif']) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24;"><i class="fas fa-wrench"></i></div>
            <div>
                <div class="stat-label">Maintenance</div>
                <div class="stat-value" style="color:#fbbf24;">{{ number_format($stats['maintenance']) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(156,163,175,0.12);color:#9ca3af;"><i class="fas fa-power-off"></i></div>
            <div>
                <div class="stat-label">Tidak Aktif</div>
                <div class="stat-value" style="color:#9ca3af;">{{ number_format($stats['tidak_aktif']) }}</div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="section-card">
        <div class="section-body">
            <form method="GET" class="filter-grid">
                <div class="field">
                    <label><i class="fas fa-search" style="margin-right:4px;"></i> Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, kode, atau desa...">
                </div>
                <div class="field">
                    <label><i class="fas fa-map-marker-alt" style="margin-right:4px;"></i> Kecamatan</label>
                    <select name="kecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatanList as $k)
                            <option value="{{ $k }}" @selected(request('kecamatan') == $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label><i class="fas fa-tower-cell" style="margin-right:4px;"></i> Provider</label>
                    <select name="provider">
                        <option value="">Semua Provider</option>
                        @foreach ($providerList as $p)
                            <option value="{{ $p }}" @selected(request('provider') == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label><i class="fas fa-toggle-on" style="margin-right:4px;"></i> Status</label>
                    <select name="status_operasional">
                        <option value="">Semua Status</option>
                        @foreach ($statusList as $s)
                            <option value="{{ $s }}" @selected(request('status_operasional') == $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-filter"><i class="fas fa-filter"></i></button>
            </form>
        </div>
    </div>

    {{-- PETA --}}
    <div class="section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="fas fa-map" style="margin-right:6px;"></i> Peta Sebaran BTS</h5>
            <div style="display:flex;gap:10px;align-items:center;">
                <label style="display:flex;align-items:center;gap:5px;font-size:10px;color:#6b7280;font-weight:600;cursor:pointer;">
                    <input type="checkbox" id="toggleCoverage" style="accent-color:#4f46e5;width:14px;height:14px;"> 
                    <i class="fas fa-broadcast-tower" style="font-size:9px;"></i> Cakupan
                </label>
                <span style="font-size:10px;color:#6b7280;font-weight:600;">{{ $stats['total'] }} titik</span>
            </div>
        </div>
        <div class="section-body" style="padding:12px;">
            <div id="bts-map"></div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="fas fa-list" style="margin-right:6px;"></i> Data BTS</h5>
            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                <span style="font-size:10px;color:#6b7280;font-weight:600;" id="selectedCount"></span>
                <button type="button" class="btn-act btn-red" id="bulkDeleteBtn" style="display:none;padding:5px 12px;font-size:10px;" onclick="submitBulkDelete()">
                    <i class="fas fa-trash"></i> Hapus Terpilih
                </button>
                <button type="button" class="btn-act btn-purple" id="compareBtn" style="display:none;padding:5px 12px;font-size:10px;" onclick="submitCompare()">
                    <i class="fas fa-columns"></i> Bandingkan
                </button>
            </div>
        </div>

        <form id="bulkForm" action="{{ route('bts-towers.bulk-delete') }}" method="POST">
            @csrf
            <div class="bulk-bar" id="bulkBar">
                <span class="bulk-count" id="bulkText">0 dipilih</span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:36px;text-align:center;"><input type="checkbox" class="cb-custom" id="selectAll"></th>
                            <th>Kode</th>
                            <th>Nama BTS</th>
                            <th>Provider</th>
                            <th>Kecamatan</th>
                            <th>Kondisi</th>
                            <th>Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($towers as $tower)
                            @php
                                $kondisiClass = match($tower->kondisi) {
                                    'Baik' => 'badge-green',
                                    'Rusak Berat' => 'badge-red',
                                    'Rusak Ringan', 'Perlu Perbaikan' => 'badge-yellow',
                                    default => 'badge-gray',
                                };
                                $statusClass = match($tower->status_operasional) {
                                    'Aktif' => 'badge-green',
                                    'Maintenance' => 'badge-yellow',
                                    'Tidak Aktif' => 'badge-gray',
                                    default => 'badge-gray',
                                };
                                $providerColors = [
                                    'Telkomsel' => '#e74c3c', 'Indosat' => '#f39c12', 'XL Axiata' => '#3498db',
                                    'Tri (3)' => '#9b59b6', 'Smartfren' => '#2ecc71', 'Lainnya' => '#95a5a6',
                                ];
                                $pc = $providerColors[$tower->provider] ?? '#95a5a6';
                            @endphp
                            <tr>
                                <td style="text-align:center;"><input type="checkbox" class="cb-custom tower-cb" name="tower_ids[]" value="{{ $tower->id }}" data-kode="{{ $tower->kode_bts }}"></td>
                                <td style="font-family:monospace;font-size:11px;color:#818cf8;">{{ $tower->kode_bts }}</td>
                                <td>
                                    <div class="tower-name">{{ $tower->nama_bts }}</div>
                                    @if($tower->desa)
                                        <div class="tower-sub">{{ $tower->desa }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="provider-pill">
                                        <span class="provider-dot" style="background:{{ $pc }};"></span>
                                        {{ $tower->provider }}
                                    </span>
                                </td>
                                <td>{{ $tower->kecamatan }}</td>
                                <td><span class="badge-c {{ $kondisiClass }}">{{ $tower->kondisi ?: '-' }}</span></td>
                                <td><span class="badge-c {{ $statusClass }}">{{ $tower->status_operasional ?: '-' }}</span></td>
                                <td>
                                    <div class="action-icons">
                                        <a href="{{ route('bts-towers.show', $tower) }}" class="icon-btn icon-info" title="Detail"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('bts-towers.edit', $tower) }}" class="icon-btn icon-warning" title="Edit"><i class="fas fa-pen"></i></a>
                                        <a href="{{ route('bts-towers.report', $tower) }}" target="_blank" class="icon-btn icon-dark" title="PDF"><i class="fas fa-file-pdf"></i></a>
                                        <form action="{{ route('bts-towers.destroy', $tower) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="icon-btn icon-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="empty-row"><i class="fas fa-tower-cell" style="font-size:24px;display:block;margin-bottom:8px;opacity:0.3;"></i> Belum ada data BTS.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <form id="compareForm" action="{{ route('bts-towers.compare') }}" method="GET" style="display:none;">
        </form>

        <div class="pagination-wrap">
            {{ $towers->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    const mapPoints = @json($mapPoints);
    const providerColors = {
        'Telkomsel': '#e74c3c', 'Indosat': '#f39c12', 'XL Axiata': '#3498db',
        'Tri (3)': '#9b59b6', 'Smartfren': '#2ecc71', 'Lainnya': '#95a5a6'
    };
    const kecamatanColors = {
        'Pinolosian Timur': '#ef4444',
        'Pinolosian Tengah': '#f59e0b',
        'Pinolosian': '#3b82f6',
        'Bolaang Uki': '#8b5cf6',
        'Helumo': '#10b981',
        'Tomini': '#ec4899',
        'Posigadan': '#06b6d4'
    };
    const statusColors = { 'Aktif': '#34d399', 'Maintenance': '#fbbf24', 'Tidak Aktif': '#9ca3af' };

    const map = L.map('bts-map').setView([0.4317, 123.4817], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19,
    }).addTo(map);

    const markers = [];
    mapPoints.forEach(p => {
        if (!p.latitude || !p.longitude) return;
        const color = kecamatanColors[p.kecamatan] || '#95a5a6';
        const sc = statusColors[p.status_operasional] || '#9ca3af';
        const labelParts = [];
        if (p.nama_bts) labelParts.push(p.nama_bts);
        if (p.desa) labelParts.push(p.desa);
        const labelText = labelParts.join(' • ');
        const icon = L.divIcon({
            className: '',
            html: `<div style="position:relative;width:24px;height:24px;">
                <div style="width:24px;height:24px;border-radius:50%;background:${color};border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>
                <div style="position:absolute;bottom:-2px;right:-2px;width:10px;height:10px;border-radius:50%;background:${sc};border:2px solid #1a1f3a;"></div>
            </div>`,
            iconSize: [24, 24], iconAnchor: [12, 12],
        });
        const marker = L.marker([p.latitude, p.longitude], {icon}).addTo(map);

        marker.bindTooltip(labelText, {
            permanent: false, direction: 'right', offset: [14, 0],
            className: 'bts-marker-tooltip'
        });

        marker.bindPopup(`
            <div style="font-family:system-ui;min-width:160px;">
                <div style="font-weight:800;font-size:13px;margin-bottom:4px;">${p.nama_bts}</div>
                <div style="font-size:11px;color:#6b7280;margin-bottom:2px;">${p.kode_bts}</div>
                <div style="font-size:11px;margin-bottom:2px;">${p.desa ? '<i class="fas fa-location-dot" style="margin-right:3px;font-size:9px;"></i>' + p.desa : ''}</div>
                <div style="font-size:11px;margin-bottom:2px;"><span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:${color};"></span> ${p.provider}</div>
                <div style="font-size:11px;margin-bottom:6px;">${p.kecamatan}</div>
                <a href="/bts-towers/${p.id}" style="font-size:11px;color:#4f46e5;font-weight:600;text-decoration:none;">Lihat detail &rarr;</a>
            </div>
        `);
        markers.push(marker);
    });

    if (markers.length > 0) {
        map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
    }

    // Legend
    const legend = L.control({position: 'bottomright'});
    legend.onAdd = function() {
        const div = L.DomUtil.create('div');
        div.style.cssText = 'background:#171e33;border:1px solid #232b4a;border-radius:10px;padding:10px 12px;font-size:10px;color:#d1d5db;';
        let html = '<div style="font-weight:800;margin-bottom:6px;color:#a5b4fc;">Kecamatan</div>';
        for (const [name, c] of Object.entries(kecamatanColors)) {
            const count = mapPoints.filter(p => p.kecamatan === name).length;
            html += `<div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;"><span style="width:8px;height:8px;border-radius:50%;background:${c};flex-shrink:0;"></span>${name} <span style="color:#6b7280;font-size:9px;">(${count})</span></div>`;
        }
        div.innerHTML = html;
        return div;
    };
    legend.addTo(map);

    // Bulk delete
    const selectAll = document.getElementById('selectAll');
    const cbs = document.querySelectorAll('.tower-cb');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkText = document.getElementById('bulkText');
    const selectedCount = document.getElementById('selectedCount');

    const compareBtn = document.getElementById('compareBtn');

    function updateBulk() {
        const checked = document.querySelectorAll('.tower-cb:checked').length;
        if (checked > 0) {
            bulkDeleteBtn.style.display = 'inline-flex';
            selectedCount.textContent = checked + ' dipilih';
            bulkText.textContent = checked + ' dipilih';
            if (checked >= 2) {
                compareBtn.style.display = 'inline-flex';
            } else {
                compareBtn.style.display = 'none';
            }
        } else {
            bulkDeleteBtn.style.display = 'none';
            compareBtn.style.display = 'none';
            selectedCount.textContent = '';
        }
    }

    selectAll?.addEventListener('change', function() {
        cbs.forEach(cb => { cb.checked = selectAll.checked; });
        updateBulk();
    });
    cbs.forEach(cb => cb.addEventListener('change', updateBulk));

    function submitBulkDelete() {
        const checked = document.querySelectorAll('.tower-cb:checked').length;
        if (checked === 0) return;
        if (confirm('Yakin hapus ' + checked + ' data BTS terpilih?')) {
            document.getElementById('bulkForm').submit();
        }
    }
    // Compare
    function submitCompare() {
        const checked = document.querySelectorAll('.tower-cb:checked');
        if (checked.length < 2) {
            alert('Pilih minimal 2 BTS untuk dibandingkan.');
            return;
        }
        if (checked.length > 5) {
            alert('Maksimal 5 BTS untuk dibandingkan.');
            return;
        }
        const form = document.getElementById('compareForm');
        form.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tower_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });
        form.submit();
    }

    // Coverage circles
    let coverageCircles = [];
    const toggleCoverageEl = document.getElementById('toggleCoverage');
    const mapPointsFull = @json($mapPoints);
    
    function toggleCoverageCircles() {
        if (toggleCoverageEl.checked) {
            mapPointsFull.forEach(p => {
                if (!p.latitude || !p.longitude) return;
                const radius = parseFloat(p.coverage_radius) || 2;
                const circle = L.circle([p.latitude, p.longitude], {
                    radius: radius * 1000,
                    color: providerColors[p.provider] || '#95a5a6',
                    fillColor: providerColors[p.provider] || '#95a5a6',
                    fillOpacity: 0.08,
                    weight: 1,
                    opacity: 0.3,
                }).addTo(map);
                circle._towerId = p.id;
                coverageCircles.push(circle);
            });
        } else {
            coverageCircles.forEach(c => map.removeLayer(c));
            coverageCircles = [];
        }
    }

    toggleCoverageEl?.addEventListener('change', toggleCoverageCircles);
</script>
@endpush
@endsection
