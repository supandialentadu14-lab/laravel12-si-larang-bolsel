@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
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
    .bts-index .btn-red { background: rgba(239,68,68,0.1); color: #f87171; border-color: rgba(239,68,68,0.2); }
    .bts-index .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }
    .bts-index .btn-green { background: rgba(16,185,129,0.1); color: #34d399; border-color: rgba(16,185,129,0.2); }

    .bts-index .alert-success-c {
        background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15);
        color: #34d399; border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; font-size: 12px; font-weight: 600;
    }
    .bts-index .alert-error-c {
        background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);
        color: #f87171; border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; font-size: 12px; font-weight: 600;
    }

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

    .bts-index .unread-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #818cf8; flex-shrink: 0;
        box-shadow: 0 0 8px rgba(129,140,248,0.5);
    }
    .bts-index .read-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #374151; flex-shrink: 0;
    }

    .bts-index .alert-card {
        display: flex; gap: 14px; padding: 16px 20px;
        border-bottom: 1px solid #1e2540; transition: background 0.15s;
    }
    .bts-index .alert-card:last-child { border-bottom: none; }
    .bts-index .alert-card:hover { background: rgba(255,255,255,0.01); }

    .bts-index .alert-card.unread {
        background: rgba(79,70,229,0.03);
    }
    .bts-index .alert-card.unread:hover {
        background: rgba(79,70,229,0.05);
    }

    .bts-index .alert-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 14px;
    }
    .bts-index .alert-icon.status_changed {
        background: rgba(99,102,241,0.1); color: #818cf8;
    }
    .bts-index .alert-icon.maintenance_due {
        background: rgba(245,158,11,0.1); color: #fbbf24;
    }
    .bts-index .alert-icon.condition_changed {
        background: rgba(239,68,68,0.1); color: #f87171;
    }

    .bts-index .alert-body { flex: 1; min-width: 0; }
    .bts-index .alert-title { font-size: 13px; font-weight: 700; color: #e5e7eb; margin-bottom: 3px; }
    .bts-index .alert-message { font-size: 12px; color: #9ca3af; line-height: 1.5; }
    .bts-index .alert-time { font-size: 10px; color: #6b7280; font-weight: 600; margin-top: 6px; }

    .bts-index .alert-actions {
        display: flex; gap: 5px; flex-shrink: 0; align-self: center;
    }
    .bts-index .icon-btn {
        width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 7px; border: none; text-decoration: none; font-size: 11px; cursor: pointer;
        transition: all 0.15s;
    }
    .bts-index .icon-info { background: rgba(59,130,246,0.1); color: #60a5fa; }
    .bts-index .icon-danger { background: rgba(239,68,68,0.1); color: #f87171; }
    .bts-index .icon-btn:hover { filter: brightness(1.3); transform: scale(1.05); }

    .bts-index .pagination-wrap { padding: 14px 20px; border-top: 1px solid #1e2540; }

    .bts-index .empty-state {
        text-align: center; padding: 60px 20px; color: #4b5563;
    }
    .bts-index .empty-state i { font-size: 40px; opacity: 0.2; margin-bottom: 12px; display: block; }
    .bts-index .empty-state .empty-title { font-size: 14px; font-weight: 700; color: #6b7280; margin-bottom: 4px; }
    .bts-index .empty-state .empty-sub { font-size: 12px; color: #4b5563; }

    @media (max-width: 768px) {
        .bts-index .page-title { font-size: 1rem; }
        .bts-index .alert-card { padding: 14px 16px; }
        .bts-index .alert-actions { flex-direction: column; }
    }
</style>

<div class="container-fluid py-4 bts-index">
    {{-- FLASH --}}
    @if (session('success'))
        <div class="alert-success-c"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error-c"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:2px;">
                <a href="{{ route('bts-towers.index') }}" class="btn-act btn-ghost" style="padding:6px 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="page-title">Notifikasi BTS</h4>
            </div>
            <p class="page-sub">{{ $alerts->total() }} notifikasi &middot; {{ $alerts->where('read_at', null)->count() }} belum dibaca</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            @if ($alerts->where('read_at', null)->count() > 0)
                <form action="{{ route('bts-towers.alerts-read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-act btn-green" onclick="return confirm('Tandai semua notifikasi sudah dibaca?')">
                        <i class="fas fa-check-double"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ALERTS LIST --}}
    <div class="section-card">
        @forelse ($alerts as $alert)
            @php
                $isUnread = is_null($alert->read_at);
                $typeConfig = match($alert->type) {
                    'status_changed' => ['icon' => 'fas fa-toggle-off', 'css' => 'status_changed'],
                    'maintenance_due' => ['icon' => 'fas fa-wrench', 'css' => 'maintenance_due'],
                    'condition_changed' => ['icon' => 'fas fa-exclamation-triangle', 'css' => 'condition_changed'],
                    default => ['icon' => 'fas fa-bell', 'css' => 'status_changed'],
                };
            @endphp
            <div class="alert-card {{ $isUnread ? 'unread' : '' }}">
                <div class="alert-icon {{ $typeConfig['css'] }}">
                    <i class="{{ $typeConfig['icon'] }}"></i>
                </div>
                <div class="alert-body">
                    <div class="alert-title">{{ $alert->title }}</div>
                    <div class="alert-message">{{ $alert->message }}</div>
                    <div class="alert-time">
                        <i class="far fa-clock" style="margin-right:3px;"></i>
                        {{ $alert->created_at->diffForHumans() }}
                        @if ($isUnread)
                            <span style="margin-left:8px;color:#818cf8;">&bull; Baru</span>
                        @endif
                    </div>
                </div>
                <div class="alert-actions">
                    @if ($isUnread)
                        <form action="{{ route('bts-towers.alerts-read', $alert) }}" method="POST">
                            @csrf
                            <button type="submit" class="icon-btn icon-info" title="Tandai sudah dibaca">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('bts-towers.alerts-delete', $alert) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn icon-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <div class="empty-title">Tidak ada notifikasi</div>
                <div class="empty-sub">Semua notifikasi BTS akan muncul di sini</div>
            </div>
        @endforelse

        @if ($alerts->hasPages())
            <div class="pagination-wrap">
                {{ $alerts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
