@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@php
    $towerPhotos = $btsTower->photos;
@endphp
<style>
    .bts-show { color: #e5e7eb; font-family: 'Inter', system-ui, sans-serif; }

    .bts-show .hero-card {
        background: linear-gradient(135deg, #1a1f3a 0%, #1e2a4a 50%, #1a2040 100%);
        border: 1px solid rgba(99,102,241,0.15);
        border-radius: 1.5rem; overflow: hidden; margin-bottom: 1.5rem;
        position: relative;
    }
    .bts-show .hero-card::before {
        content: ''; position: absolute; top: 0; right: 0;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .bts-show .hero-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 1.5rem 1.5rem 0; flex-wrap: wrap; gap: 12px; position: relative; z-index: 1;
    }
    .bts-show .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 9999px; font-size: 10px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.1em;
    }
    .bts-show .hero-badge.aktif { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
    .bts-show .hero-badge.tidak-aktif { background: rgba(156,163,175,0.15); color: #9ca3af; border: 1px solid rgba(156,163,175,0.2); }
    .bts-show .hero-badge.maintenance { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
    .bts-show .hero-body { padding: 1.25rem 1.5rem; position: relative; z-index: 1; }
    .bts-show .hero-title { font-size: 1.5rem; font-weight: 900; color: #f9fafb; margin: 0 0 4px; }
    .bts-show .hero-sub { font-size: 0.75rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; }
    .bts-show .hero-sub span { color: #a5b4fc; }

    .bts-show .stats-row {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;
        padding: 0 1.5rem 1.5rem; position: relative; z-index: 1;
    }
    .bts-show .stat-pill {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-radius: 12px;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
    }
    .bts-show .stat-pill .stat-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 14px;
    }
    .bts-show .stat-pill .stat-label { font-size: 9px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; }
    .bts-show .stat-pill .stat-value { font-size: 13px; color: #e5e7eb; font-weight: 800; }

    .bts-show .hero-actions {
        display: flex; gap: 8px; flex-wrap: wrap; padding: 0 1.5rem 1.25rem; position: relative; z-index: 1;
    }
    .bts-show .btn-act {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px; border-radius: 10px; font-size: 11px; font-weight: 700;
        text-decoration: none; border: 1px solid transparent; cursor: pointer;
        transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-show .btn-act:hover { filter: brightness(1.2); transform: translateY(-1px); }
    .bts-show .btn-indigo { background: #4f46e5; color: #fff; }
    .bts-show .btn-amber { background: #78350f; color: #fde68a; border-color: rgba(251,191,36,0.2); }
    .bts-show .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }
    .bts-show .btn-red { background: rgba(239,68,68,0.1); color: #f87171; border-color: rgba(239,68,68,0.15); }
    .bts-show .btn-green { background: rgba(16,185,129,0.1); color: #34d399; border-color: rgba(16,185,129,0.15); }
    .bts-show .btn-purple { background: rgba(139,92,246,0.1); color: #a78bfa; border-color: rgba(139,92,246,0.15); }

    .bts-show .tabs-nav {
        display: flex; gap: 2px; background: #131825; border-radius: 12px; padding: 4px;
        margin-bottom: 1.25rem; border: 1px solid #1e2540;
    }
    .bts-show .tab-btn {
        flex: 1; padding: 10px 16px; border-radius: 10px; font-size: 11px; font-weight: 700;
        text-align: center; cursor: pointer; border: none; background: transparent;
        color: #6b7280; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .bts-show .tab-btn:hover { color: #a5b4fc; }
    .bts-show .tab-btn.active { background: #1e2745; color: #a5b4fc; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
    .bts-show .tab-panel { display: none; }
    .bts-show .tab-panel.active { display: block; }

    .bts-show .card {
        background: #171e33; border: 1px solid #232b4a; border-radius: 16px; overflow: hidden;
    }
    .bts-show .card-header {
        padding: 14px 20px; border-bottom: 1px solid #232b4a;
        display: flex; justify-content: space-between; align-items: center;
    }
    .bts-show .card-header h5 {
        margin: 0; font-size: 11px; font-weight: 800; color: #a5b4fc;
        text-transform: uppercase; letter-spacing: 0.15em;
    }
    .bts-show .card-body { padding: 20px; }

    .bts-show table.detail-tbl { width: 100%; border-collapse: collapse; }
    .bts-show table.detail-tbl tr { border-bottom: 1px solid #1e2540; }
    .bts-show table.detail-tbl tr:last-child { border-bottom: none; }
    .bts-show table.detail-tbl th {
        text-align: left; padding: 10px 0; font-size: 10px; font-weight: 700;
        color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em; width: 38%; vertical-align: top;
    }
    .bts-show table.detail-tbl td { padding: 10px 0; font-size: 13px; color: #e5e7eb; font-weight: 500; }

    .bts-show .map-box { height: 450px; border-radius: 12px; border: 1px solid #232b4a; }

    .bts-show .note-card {
        padding: 14px 16px; border-radius: 12px; margin-bottom: 10px;
        background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);
        transition: border-color 0.2s;
    }
    .bts-show .note-card:hover { border-color: rgba(99,102,241,0.2); }
    .bts-show .note-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
    .bts-show .note-title { font-size: 13px; font-weight: 700; color: #e5e7eb; }
    .bts-show .note-meta { font-size: 10px; color: #6b7280; margin-bottom: 6px; }
    .bts-show .note-body { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    .bts-show .note-type-badge {
        display: inline-flex; padding: 2px 8px; border-radius: 6px;
        font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .bts-show .note-type-badge.catatan { background: rgba(99,102,241,0.12); color: #818cf8; }
    .bts-show .note-type-badge.perawatan { background: rgba(16,185,129,0.12); color: #34d399; }
    .bts-show .note-type-badge.kerusakan { background: rgba(239,68,68,0.12); color: #f87171; }
    .bts-show .note-type-badge.inspeksi { background: rgba(245,158,11,0.12); color: #fbbf24; }

    .bts-show .nearby-row {
        display: flex; align-items: center; gap: 12px; padding: 10px 0;
        border-bottom: 1px solid #1e2540;
    }
    .bts-show .nearby-row:last-child { border-bottom: none; }
    .bts-show .nearby-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .bts-show .nearby-info { flex: 1; }
    .bts-show .nearby-name { font-size: 12px; font-weight: 700; color: #e5e7eb; }
    .bts-show .nearby-sub { font-size: 10px; color: #6b7280; }
    .bts-show .nearby-dist { font-size: 11px; font-weight: 800; color: #a5b4fc; white-space: nowrap; }

    .bts-show .modal-dark {
        background: #131825; border: 1px solid #232b4a; border-radius: 16px; color: #e5e7eb;
    }
    .bts-show .modal-dark .modal-header { border-color: #232b4a; }
    .bts-show .modal-dark .modal-footer { border-color: #232b4a; }
    .bts-show .form-control-dark {
        background: #1a2035; border: 1px solid #2a3252; color: #e5e7eb; border-radius: 10px;
    }
    .bts-show .form-control-dark:focus { border-color: #4f46e5; box-shadow: 0 0 0 2px rgba(79,70,229,0.15); }
    .bts-show .form-control-dark::placeholder { color: #4b5563; }
    .bts-show select.form-control-dark { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }

    .bts-show .empty-state { text-align: center; padding: 2.5rem 1rem; }
    .bts-show .empty-icon { font-size: 2rem; margin-bottom: 8px; opacity: 0.3; }
    .bts-show .empty-text { font-size: 11px; color: #4b5563; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }

    .bts-show .photo-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
    .bts-show .photo-grid img { width: 100%; max-height: 350px; object-fit: cover; border-radius: 12px; border: 1px solid #232b4a; }

    @media (max-width: 768px) {
        .bts-show .hero-top { padding: 1rem 1rem 0; }
        .bts-show .hero-body { padding: 1rem; }
        .bts-show .stats-row { padding: 0 1rem 1rem; grid-template-columns: repeat(2, 1fr); }
        .bts-show .hero-actions { padding: 0 1rem 1rem; }
        .bts-show .hero-title { font-size: 1.15rem; }
        .bts-show .map-box { height: 300px; }
    }
</style>

<div class="container-fluid py-4 bts-show">
    {{-- HERO CARD --}}
    <div class="hero-card">
        <div class="hero-top">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
                    <span class="hero-badge {{ strtolower(str_replace(' ', '-', $btsTower->status_operasional ?? 'aktif')) }}">
                        <i class="fas fa-signal"></i> {{ $btsTower->status_operasional ?? 'Aktif' }}
                    </span>
                    @if($btsTower->kondisi)
                        <span style="font-size:10px;color:#6b7280;font-weight:600;">
                            Kondisi: <span style="color:{{ $btsTower->kondisi === 'Baik' ? '#34d399' : ($btsTower->kondisi === 'Rusak Berat' ? '#f87171' : '#fbbf24') }}">{{ $btsTower->kondisi }}</span>
                        </span>
                    @endif
                </div>
                <h4 class="hero-title">{{ $btsTower->nama_bts }}</h4>
                <p class="hero-sub"><span>{{ $btsTower->kode_bts }}</span> &middot; {{ $btsTower->provider }}</p>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-pill">
                <div class="stat-icon" style="background:rgba(99,102,241,0.12);color:#818cf8;"><i class="fas fa-location-dot"></i></div>
                <div>
                    <div class="stat-label">Kecamatan</div>
                    <div class="stat-value">{{ $btsTower->kecamatan }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24;"><i class="fas fa-tower-cell"></i></div>
                <div>
                    <div class="stat-label">Tipe Tower</div>
                    <div class="stat-value">{{ $btsTower->tipe_tower ?: '-' }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399;"><i class="fas fa-ruler-vertical"></i></div>
                <div>
                    <div class="stat-label">Tinggi</div>
                    <div class="stat-value">{{ $btsTower->tinggi_tower ? $btsTower->tinggi_tower . ' m' : '-' }}</div>
                </div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon" style="background:rgba(139,92,246,0.12);color:#a78bfa;"><i class="fas fa-calendar"></i></div>
                <div>
                    <div class="stat-label">Tahun</div>
                    <div class="stat-value">{{ $btsTower->tahun_dibangun ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="hero-actions">
            <a href="{{ route('bts-towers.report', $btsTower) }}" target="_blank" class="btn-act btn-indigo">
                <i class="fas fa-file-pdf"></i> Laporan PDF
            </a>
            <a href="{{ route('bts-towers.edit', $btsTower) }}" class="btn-act btn-amber">
                <i class="fas fa-pen"></i> Edit
            </a>
            <form action="{{ route('bts-towers.toggle-status', $btsTower) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-act btn-green">
                    <i class="fas fa-sync-alt"></i> Toggle Status
                </button>
            </form>
            <a href="{{ route('bts-towers.index') }}" class="btn-act btn-ghost">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- TABS --}}
    <div class="tabs-nav" id="btsTabs">
        <button class="tab-btn active" data-tab="detail"><i class="fas fa-info-circle"></i> Detail</button>
        <button class="tab-btn" data-tab="peta"><i class="fas fa-map"></i> Peta</button>
        <button class="tab-btn" data-tab="catatan"><i class="fas fa-clipboard-list"></i> Catatan & Perawatan</button>
        <button class="tab-btn" data-tab="terdekat"><i class="fas fa-tower-cell"></i> BTS Terdekat</button>
    </div>

    {{-- TAB: DETAIL --}}
    <div class="tab-panel active" id="tab-detail">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            {{-- Foto --}}
            <div class="card">
                <div class="card-header">
                    <h5>Foto BTS</h5>
                    <button type="button" class="btn-act btn-purple" onclick="document.getElementById('addPhotoModal').style.display='flex'" style="font-size:10px;padding:5px 10px;">
                        <i class="fas fa-camera"></i> Tambah Foto
                    </button>
                </div>
                <div class="card-body">
                    @if($btsTower->foto || $towerPhotos->count() > 0)
                        <div class="photo-grid">
                            @if($btsTower->foto)
                                <img src="{{ $btsTower->foto_url }}" alt="{{ $btsTower->nama_bts }}">
                            @endif
                            @foreach($towerPhotos as $photo)
                                <div style="position:relative;">
                                    <img src="{{ $photo->url }}" alt="{{ $photo->caption ?? $btsTower->nama_bts }}">
                                    @if($photo->caption)
                                        <div style="position:absolute;bottom:8px;left:8px;background:rgba(0,0,0,0.7);color:#fff;font-size:10px;padding:3px 8px;border-radius:6px;">{{ $photo->caption }}</div>
                                    @endif
                                    <form action="{{ route('bts-towers.delete-photo', $photo) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')" style="position:absolute;top:8px;right:8px;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:rgba(239,68,68,0.8);color:#fff;border:none;border-radius:6px;width:24px;height:24px;cursor:pointer;font-size:11px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-image"></i></div>
                            <div class="empty-text">Belum ada foto</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Data Lengkap --}}
            <div class="card">
                <div class="card-header"><h5>Data Lengkap</h5></div>
                <div class="card-body">
                    <table class="detail-tbl">
                        <tr><th>Kode BTS</th><td>{{ $btsTower->kode_bts }}</td></tr>
                        <tr><th>Provider</th><td>
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:{{ $providerColors[$btsTower->provider] ?? '#95a5a6' }};"></span>
                                {{ $btsTower->provider }}
                            </span>
                        </td></tr>
                        <tr><th>Kecamatan</th><td>{{ $btsTower->kecamatan }}</td></tr>
                        <tr><th>Desa / Kelurahan</th><td>{{ $btsTower->desa ?: '-' }}</td></tr>
                        <tr><th>Alamat Lengkap</th><td>{{ $btsTower->alamat ?: '-' }}</td></tr>
                        <tr><th>Koordinat</th><td>
                            <span style="font-family:monospace;font-size:12px;">{{ $btsTower->latitude }}, {{ $btsTower->longitude }}</span>
                            <a href="https://www.google.com/maps?q={{ $btsTower->latitude }},{{ $btsTower->longitude }}" target="_blank" style="font-size:10px;color:#818cf8;margin-left:6px;text-decoration:none;">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td></tr>
                        <tr><th>Tinggi Tower</th><td>{{ $btsTower->tinggi_tower ? $btsTower->tinggi_tower . ' meter' : '-' }}</td></tr>
                        <tr><th>Tipe Tower</th><td>{{ $btsTower->tipe_tower ?: '-' }}</td></tr>
                        <tr><th>Tahun Dibangun</th><td>{{ $btsTower->tahun_dibangun ?: '-' }}</td></tr>
                        <tr><th>Kondisi Fisik</th><td>
                            @if($btsTower->kondisi)
                                <span style="display:inline-flex;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;
                                    background:{{ $btsTower->kondisi === 'Baik' ? 'rgba(16,185,129,0.12)' : ($btsTower->kondisi === 'Rusak Berat' ? 'rgba(239,68,68,0.12)' : 'rgba(245,158,11,0.12)') }};
                                    color:{{ $btsTower->kondisi === 'Baik' ? '#34d399' : ($btsTower->kondisi === 'Rusak Berat' ? '#f87171' : '#fbbf24') }};">
                                    {{ $btsTower->kondisi }}
                                </span>
                            @else -
                            @endif
                        </td></tr>
                        <tr><th>Status Operasional</th><td>
                            @if($btsTower->status_operasional)
                                <span style="display:inline-flex;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:700;
                                    background:{{ $btsTower->status_operasional === 'Aktif' ? 'rgba(16,185,129,0.12)' : ($btsTower->status_operasional === 'Maintenance' ? 'rgba(245,158,11,0.12)' : 'rgba(156,163,175,0.12)') }};
                                    color:{{ $btsTower->status_operasional === 'Aktif' ? '#34d399' : ($btsTower->status_operasional === 'Maintenance' ? '#fbbf24' : '#9ca3af') }};">
                                    {{ $btsTower->status_operasional }}
                                </span>
                            @else -
                            @endif
                        </td></tr>
                        <tr><th>Keterangan</th><td>{{ $btsTower->keterangan ?: '-' }}</td></tr>
                        <tr><th>Radius Cakupan</th><td>
                            @if($btsTower->coverage_radius)
                                <span style="color:#818cf8;font-weight:700;">{{ $btsTower->coverage_radius }} km</span>
                            @else <span style="color:#4b5563;">-</span> @endif
                        </td></tr>
                        <tr><th>Dibuat</th><td style="font-size:11px;color:#6b7280;">{{ $btsTower->created_at?->translatedFormat('d M Y, H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: PETA --}}
    <div class="tab-panel" id="tab-peta">
        <div class="card">
            <div class="card-header"><h5>Lokasi di Peta</h5></div>
            <div class="card-body" style="padding:12px;">
                <div id="detail-map" class="map-box"></div>
            </div>
        </div>
    </div>

    {{-- TAB: CATATAN --}}
    <div class="tab-panel" id="tab-catatan">
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <h5>Tambah Catatan / Perawatan</h5>
                <button type="button" class="btn-act btn-purple" onclick="document.getElementById('addNoteModal').style.display='flex'" style="font-size:10px;padding:6px 12px;">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        @forelse($btsTower->notes as $note)
            <div class="note-card">
                <div class="note-header">
                    <div>
                        <span class="note-type-badge {{ $note->type }}">{{ ucfirst($note->type) }}</span>
                        <span class="note-title" style="margin-left:8px;">{{ $note->judul }}</span>
                    </div>
                    <form action="{{ route('bts-towers.destroy-note', $note) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#4b5563;cursor:pointer;font-size:12px;padding:4px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                <div class="note-meta">
                    {{ $note->tanggal?->translatedFormat('d M Y') }} &middot; Oleh: {{ $note->user?->name ?? 'Sistem' }}
                    @if($note->teknisi) &middot; Teknisi: {{ $note->teknisi }} @endif
                    @if($note->biaya) &middot; Biaya: Rp {{ number_format((float) str_replace(['.',' ',','], '', $note->biaya), 0, ',', '.') }} @endif
                </div>
                <div class="note-body">{{ $note->isi }}</div>
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-clipboard"></i></div>
                        <div class="empty-text">Belum ada catatan</div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- TAB: BTS TERDEKAT --}}
    <div class="tab-panel" id="tab-terdekat">
        <div class="card">
            <div class="card-header"><h5>10 BTS Terdekat</h5></div>
            <div class="card-body">
                @forelse($nearbyTowers as $near)
                    <div class="nearby-row">
                        <span class="nearby-dot" style="background:{{ $providerColors[$near->provider] ?? '#95a5a6' }};"></span>
                        <div class="nearby-info">
                            <div class="nearby-name">{{ $near->nama_bts }}</div>
                            <div class="nearby-sub">{{ $near->provider }} &middot; {{ $near->kecamatan }} &middot; {{ $near->status_operasional ?? 'N/A' }}</div>
                        </div>
                        <div class="nearby-dist">{{ number_format($near->distance, 1) }} km</div>
                        <a href="{{ route('bts-towers.show', $near) }}" style="color:#818cf8;font-size:12px;text-decoration:none;margin-left:8px;">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-tower-cell"></i></div>
                        <div class="empty-text">Tidak ada BTS lain</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Tambah Foto --}}
<div id="addPhotoModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:#131825;border:1px solid #232b4a;border-radius:16px;color:#e5e7eb;width:90%;max-width:540px;max-height:90vh;overflow-y:auto;">
        <form action="{{ route('bts-towers.add-photos', $btsTower) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding:16px 20px;border-bottom:1px solid #232b4a;display:flex;justify-content:space-between;align-items:center;">
                <h6 style="font-size:13px;font-weight:800;color:#e5e7eb;margin:0;"><i class="fas fa-camera" style="margin-right:6px;"></i> Tambah Foto</h6>
                <button type="button" onclick="document.getElementById('addPhotoModal').style.display='none'" style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:18px;">&times;</button>
            </div>
            <div style="padding:20px;">
                <div style="margin-bottom:12px;">
                    <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Pilih Foto (maks 10 file)</label>
                    <input type="file" name="photos[]" multiple accept="image/*" required style="width:100%;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:10px 12px;font-size:13px;">
                    <div style="font-size:10px;color:#6b7280;margin-top:4px;">Format JPG/PNG, maks 2MB per file</div>
                </div>
            </div>
            <div style="padding:16px 20px;border-top:1px solid #232b4a;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" onclick="document.getElementById('addPhotoModal').style.display='none'" style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;font-size:11px;font-weight:700;background:rgba(255,255,255,0.05);color:#9ca3af;border:1px solid rgba(255,255,255,0.08);cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;">Batal</button>
                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;font-size:11px;font-weight:700;background:#4f46e5;color:#fff;border:none;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Tambah Catatan --}}
<div id="addNoteModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);" onclick="if(event.target===this)this.style.display='none'">
    <div style="background:#131825;border:1px solid #232b4a;border-radius:16px;color:#e5e7eb;width:90%;max-width:640px;max-height:90vh;overflow-y:auto;">
            <form action="{{ route('bts-towers.add-note', $btsTower) }}" method="POST">
                @csrf
                <div style="padding:16px 20px;border-bottom:1px solid #232b4a;display:flex;justify-content:space-between;align-items:center;">
                    <h6 style="font-size:13px;font-weight:800;color:#e5e7eb;margin:0;">Tambah Catatan / Perawatan</h6>
                    <button type="button" onclick="document.getElementById('addNoteModal').style.display='none'" style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:18px;">&times;</button>
                </div>
                <div style="padding:20px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Tipe</label>
                            <select name="type" required style="width:100%;height:38px;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:0 12px;font-size:13px;">
                                <option value="catatan">Catatan</option>
                                <option value="perawatan">Perawatan</option>
                                <option value="kerusakan">Kerusakan</option>
                                <option value="inspeksi">Inspeksi</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ now()->toDateString() }}" style="width:100%;height:38px;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:0 12px;font-size:13px;">
                        </div>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Judul</label>
                        <input type="text" name="judul" placeholder="Judul catatan..." required style="width:100%;height:38px;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:0 12px;font-size:13px;">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Isi Catatan</label>
                        <textarea name="isi" rows="4" placeholder="Deskripsi detail..." required style="width:100%;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:12px;font-size:13px;resize:vertical;"></textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Teknisi</label>
                            <input type="text" name="teknisi" placeholder="Nama teknisi..." style="width:100%;height:38px;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:0 12px;font-size:13px;">
                        </div>
                        <div>
                            <label style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:4px;">Biaya (Rp)</label>
                            <input type="text" name="biaya" placeholder="0" style="width:100%;height:38px;background:#1a2035;border:1px solid #2a3252;color:#e5e7eb;border-radius:10px;padding:0 12px;font-size:13px;">
                        </div>
                    </div>
                </div>
                <div style="padding:16px 20px;border-top:1px solid #232b4a;display:flex;justify-content:flex-end;gap:8px;">
                    <button type="button" onclick="document.getElementById('addNoteModal').style.display='none'" style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;font-size:11px;font-weight:700;background:rgba(255,255,255,0.05);color:#9ca3af;border:1px solid rgba(255,255,255,0.08);cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;">Batal</button>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;font-size:11px;font-weight:700;background:#4f46e5;color:#fff;border:none;cursor:pointer;text-transform:uppercase;letter-spacing:0.05em;"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            if (btn.dataset.tab === 'peta' && window._detailMap) {
                setTimeout(() => window._detailMap.invalidateSize(), 200);
            }
        });
    });

    const lat = {{ $btsTower->latitude }};
    const lng = {{ $btsTower->longitude }};
    const towerName = @json($btsTower->nama_bts);
    const provider = @json($btsTower->provider);
    const providerColors = @json($providerColors);
    let mapInitialized = false;

    function initDetailMap() {
        if (mapInitialized) return;
        mapInitialized = true;

        const detailMap = L.map('detail-map').setView([lat, lng], 16);
        window._detailMap = detailMap;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(detailMap);

        const color = providerColors[provider] || '#95a5a6';
        const icon = L.divIcon({
            className: '',
            html: `<div style="width:28px;height:28px;border-radius:50%;background:${color};border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;"><i class="fas fa-tower-cell" style="color:#fff;font-size:11px;"></i></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
        });
        L.marker([lat, lng], {icon}).addTo(detailMap).bindPopup(`<b>${towerName}</b><br><small>${provider}</small>`).openPopup();

        const coverageRadius = {{ $btsTower->coverage_radius ?? 'null' }};
        if (coverageRadius && coverageRadius > 0) {
            L.circle([lat, lng], {
                radius: coverageRadius * 1000,
                color: color,
                fillColor: color,
                fillOpacity: 0.08,
                weight: 1,
                opacity: 0.3,
            }).addTo(detailMap);
        }

        const nearby = @json($nearbyTowers);
        nearby.forEach(t => {
            const c = providerColors[t.provider] || '#95a5a6';
            const ni = L.divIcon({
                className: '',
                html: `<div style="width:18px;height:18px;border-radius:50%;background:${c};border:2px solid rgba(255,255,255,0.6);opacity:0.7;"></div>`,
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            });
            L.marker([parseFloat(t.latitude), parseFloat(t.longitude)], {icon: ni}).addTo(detailMap)
                .bindPopup(`<b>${t.nama_bts}</b><br><small>${t.provider} &middot; ${parseFloat(nearby.find(n=>n.id===t.id)?.distance||0).toFixed(1)} km</small>`);
        });

        setTimeout(() => detailMap.invalidateSize(), 300);
    }

    document.querySelector('.tab-btn[data-tab="peta"]').addEventListener('click', initDetailMap);
</script>
@endpush
@endsection
