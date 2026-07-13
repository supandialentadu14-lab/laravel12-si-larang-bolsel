@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<style>
    .bts-form-page { color: #e5e7eb; font-family: 'Inter', system-ui, sans-serif; }
    .bts-form-page .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        flex-wrap: wrap; gap: 12px; margin-bottom: 1.25rem;
    }
    .bts-form-page .page-title { font-size: 1.25rem; font-weight: 900; color: #f9fafb; margin: 0; }
    .bts-form-page .page-sub { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 2px; }
    .bts-form-page .btn-act {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px;
        border-radius: 10px; font-size: 11px; font-weight: 700; text-decoration: none;
        border: 1px solid transparent; cursor: pointer; transition: all 0.2s;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-form-page .btn-act:hover { filter: brightness(1.15); transform: translateY(-1px); }
    .bts-form-page .btn-indigo { background: #4f46e5; color: #fff; }
    .bts-form-page .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }
    .bts-form-page .alert-error {
        background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);
        color: #f87171; border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; font-size: 12px; font-weight: 600;
    }
    .bts-form-page .alert-error ul { margin: 0; padding-left: 18px; }
</style>

<div class="container-fluid py-4 bts-form-page">
    <div class="page-header">
        <div>
            <h4 class="page-title"><i class="fas fa-pen" style="color:#fbbf24;margin-right:8px;"></i> Edit Data BTS</h4>
            <p class="page-sub">{{ $btsTower->nama_bts }} &middot; {{ $btsTower->kode_bts }}</p>
        </div>
        <a href="{{ route('bts-towers.show', $btsTower) }}" class="btn-act btn-ghost">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bts-towers.update', $btsTower) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('bts-towers._form')
        <div style="display:flex;gap:10px;margin-top:1.25rem;">
            <button type="submit" class="btn-act btn-indigo" style="padding:11px 28px;">
                <i class="fas fa-save"></i> Perbarui
            </button>
            <a href="{{ route('bts-towers.show', $btsTower) }}" class="btn-act btn-ghost" style="padding:11px 28px;">
                Batal
            </a>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
@include('bts-towers._map-picker-script', ['lat' => old('latitude', $btsTower->latitude), 'lng' => old('longitude', $btsTower->longitude)])
@endpush
@endsection
