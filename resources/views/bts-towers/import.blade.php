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
    .bts-index .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }

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
    .bts-index .section-body { padding: 18px 20px; }

    .bts-index .upload-zone {
        border: 2px dashed #2a3252; border-radius: 14px; padding: 40px 20px;
        text-align: center; cursor: pointer; transition: all 0.25s;
        background: rgba(255,255,255,0.01); position: relative;
    }
    .bts-index .upload-zone:hover,
    .bts-index .upload-zone.dragover {
        border-color: #4f46e5; background: rgba(79,70,229,0.04);
    }
    .bts-index .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer;
    }
    .bts-index .upload-icon {
        width: 56px; height: 56px; border-radius: 16px; margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(79,70,229,0.1); color: #818cf8; font-size: 22px;
    }
    .bts-index .upload-text { font-size: 13px; font-weight: 700; color: #e5e7eb; margin-bottom: 4px; }
    .bts-index .upload-hint { font-size: 11px; color: #6b7280; }
    .bts-index .upload-hint span { color: #818cf8; font-weight: 600; }

    .bts-index .file-preview {
        display: none; align-items: center; gap: 12px; padding: 14px 18px;
        background: rgba(79,70,229,0.06); border: 1px solid rgba(79,70,229,0.15);
        border-radius: 12px; margin-top: 14px;
    }
    .bts-index .file-preview.active { display: flex; }
    .bts-index .file-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: rgba(79,70,229,0.15); color: #818cf8; font-size: 16px;
    }
    .bts-index .file-name { font-size: 12px; font-weight: 700; color: #e5e7eb; flex: 1; }
    .bts-index .file-size { font-size: 10px; color: #6b7280; font-weight: 600; }
    .bts-index .file-remove {
        background: rgba(239,68,68,0.1); color: #f87171; border: none;
        width: 28px; height: 28px; border-radius: 7px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 11px;
    }

    .bts-index .guide-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .bts-index .guide-table thead th {
        text-align: left; padding: 10px 14px; font-size: 9px; font-weight: 700;
        color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em;
        background: rgba(255,255,255,0.02); border-bottom: 1px solid #232b4a;
    }
    .bts-index .guide-table tbody td {
        padding: 10px 14px; border-bottom: 1px solid #1e2540; color: #d1d5db; vertical-align: top;
    }
    .bts-index .guide-table tbody tr:last-child td { border-bottom: none; }
    .bts-index .guide-table .col-num {
        font-family: monospace; font-size: 10px; font-weight: 800;
        color: #818cf8; background: rgba(99,102,241,0.08);
        width: 28px; height: 28px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .bts-index .guide-table .col-name { font-weight: 700; color: #e5e7eb; }

    .bts-index .wajib-badge {
        display: inline-block; padding: 2px 8px; border-radius: 5px;
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        background: rgba(239,68,68,0.1); color: #f87171;
    }
    .bts-index .opsional-badge {
        display: inline-block; padding: 2px 8px; border-radius: 5px;
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        background: rgba(156,163,175,0.1); color: #9ca3af;
    }

    .bts-index .format-chips {
        display: flex; gap: 8px; flex-wrap: wrap;
    }
    .bts-index .format-chip {
        padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
        color: #9ca3af;
    }

    .bts-index .note-card {
        display: flex; gap: 10px; padding: 12px 16px;
        background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.1);
        border-radius: 12px; margin-top: 16px; font-size: 11px; color: #fbbf24; font-weight: 600;
    }
    .bts-index .note-card i { margin-top: 1px; }

    @media (max-width: 768px) {
        .bts-index .page-title { font-size: 1rem; }
        .bts-index .section-body { padding: 14px 16px; }
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
    @if ($errors->any())
        <div class="alert-error-c">
            <i class="fas fa-exclamation-circle"></i> <strong>Terjadi kesalahan:</strong>
            <ul style="margin:6px 0 0 0;padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:2px;">
                <a href="{{ route('bts-towers.index') }}" class="btn-act btn-ghost" style="padding:6px 10px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="page-title">Import Data BTS</h4>
            </div>
            <p class="page-sub">Upload file CSV atau Excel untuk import data BTS secara massal</p>
        </div>
    </div>

    {{-- UPLOAD SECTION --}}
    <div class="section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="fas fa-cloud-upload-alt" style="margin-right:6px;"></i> Upload File</h5>
        </div>
        <div class="section-body">
            <form action="{{ route('bts-towers.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="upload-zone" id="dropZone">
                    <input type="file" name="file" id="fileInput" accept=".csv,.xls,.xlsx">
                    <div class="upload-icon"><i class="fas fa-file-upload"></i></div>
                    <div class="upload-text">Klik atau seret file ke sini</div>
                    <div class="upload-hint">Format: <span>CSV</span>, <span>XLS</span>, <span>XLSX</span> &middot; Maks 5MB</div>
                </div>

                <div class="file-preview" id="filePreview">
                    <div class="file-icon"><i class="fas fa-file"></i></div>
                    <div>
                        <div class="file-name" id="fileName">-</div>
                        <div class="file-size" id="fileSize">-</div>
                    </div>
                    <button type="button" class="file-remove" id="fileRemove"><i class="fas fa-times"></i></button>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:16px;">
                    <a href="{{ route('bts-towers.index') }}" class="btn-act btn-ghost">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn-act btn-indigo" id="submitBtn" disabled>
                        <i class="fas fa-file-import"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- INFO FORMAT --}}
    <div class="section-card">
        <div class="section-header">
            <h5 class="section-title"><i class="fas fa-info-circle" style="margin-right:6px;"></i> Informasi Format</h5>
        </div>
        <div class="section-body">
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:18px;">
                <div>
                    <div style="font-size:9px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:6px;">Format yang diterima</div>
                    <div class="format-chips">
                        <span class="format-chip"><i class="fas fa-file-csv" style="margin-right:4px;"></i> .CSV</span>
                        <span class="format-chip"><i class="fas fa-file-excel" style="margin-right:4px;"></i> .XLS</span>
                        <span class="format-chip"><i class="fas fa-file-excel" style="margin-right:4px;"></i> .XLSX</span>
                    </div>
                </div>
                <div>
                    <div style="font-size:9px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:6px;">Ukuran maksimal</div>
                    <div style="font-size:13px;font-weight:800;color:#f9fafb;">5 MB</div>
                </div>
            </div>

            <div style="font-size:9px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;">
                <i class="fas fa-download" style="margin-right:4px;"></i> Contoh file template
            </div>
            <a href="#" class="btn-act btn-ghost" style="margin-bottom:18px;">
                <i class="fas fa-file-download"></i> Download Template CSV
            </a>

            <div style="font-size:9px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:10px;">
                <i class="fas fa-table" style="margin-right:4px;"></i> Format kolom yang harus diisi
            </div>

            <div style="overflow-x:auto;">
                <table class="guide-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">Kolom</th>
                            <th>Nama Kolom</th>
                            <th style="width:80px;">Keterangan</th>
                            <th>Contoh / Pilihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="col-num">1</span></td>
                            <td><span class="col-name">Nama BTS</span></td>
                            <td><span class="wajib-badge">Wajib</span></td>
                            <td>Tower Bukit Batu, BTS Desa Pinogaluman</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">2</span></td>
                            <td><span class="col-name">Provider</span></td>
                            <td><span class="wajib-badge">Wajib</span></td>
                            <td>Telkomsel, Indosat, XL Axiata, Tri (3), Smartfren, Lainnya</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">3</span></td>
                            <td><span class="col-name">Kecamatan</span></td>
                            <td><span class="wajib-badge">Wajib</span></td>
                            <td>Bolaang Timur, Dumoga Barat, Dumoga Tengah, Dumoga Timur, Dumoga Utara, Passi Barat, Poigar</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">4</span></td>
                            <td><span class="col-name">Desa</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>Pinogaluman, Bango</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">5</span></td>
                            <td><span class="col-name">Alamat</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>Jl. Trans Sulawesi Km 15</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">6</span></td>
                            <td><span class="col-name">Latitude</span></td>
                            <td><span class="wajib-badge">Wajib</span></td>
                            <td>0.6534</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">7</span></td>
                            <td><span class="col-name">Longitude</span></td>
                            <td><span class="wajib-badge">Wajib</span></td>
                            <td>123.5421</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">8</span></td>
                            <td><span class="col-name">Tinggi Tower</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>30 (meter)</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">9</span></td>
                            <td><span class="col-name">Tipe Tower</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>BTS, Repeater, Microwave</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">10</span></td>
                            <td><span class="col-name">Kondisi</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>Baik, Rusak Ringan, Rusak Berat, Perlu Perbaikan</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">11</span></td>
                            <td><span class="col-name">Status</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>Aktif, Maintenance, Tidak Aktif</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">12</span></td>
                            <td><span class="col-name">Tahun Dibangun</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td><span class="col-num">13</span></td>
                            <td><span class="col-name">Keterangan</span></td>
                            <td><span class="opsional-badge">Opsional</span></td>
                            <td>Catatan tambahan mengenai tower</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="note-card">
                <i class="fas fa-exclamation-triangle"></i>
                <div>Pastikan kolom <strong>Latitude</strong> dan <strong>Longitude</strong> menggunakan titik (.) sebagai pemisah desimal, bukan koma. Data dengan koordinat tidak valid akan dilewati.</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileRemove = document.getElementById('fileRemove');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('importForm');

    const allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    const allowedExts = ['.csv', '.xls', '.xlsx'];
    const maxSize = 5 * 1024 * 1024;

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(2) + ' MB';
    }

    function getExtension(name) {
        return '.' + name.split('.').pop().toLowerCase();
    }

    function showFile(file) {
        const ext = getExtension(file.name);
        if (!allowedExts.includes(ext)) {
            alert('Format file tidak valid. Hanya file CSV, XLS, atau XLSX yang diterima.');
            fileInput.value = '';
            return;
        }
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            fileInput.value = '';
            return;
        }
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        filePreview.classList.add('active');
        dropZone.style.display = 'none';
        submitBtn.disabled = false;
    }

    function clearFile() {
        fileInput.value = '';
        filePreview.classList.remove('active');
        dropZone.style.display = '';
        submitBtn.disabled = true;
    }

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) showFile(this.files[0]);
    });

    fileRemove.addEventListener('click', clearFile);

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', function() {
        this.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showFile(e.dataTransfer.files[0]);
        }
    });

    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            return;
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengimport...';
    });
</script>
@endpush
@endsection
