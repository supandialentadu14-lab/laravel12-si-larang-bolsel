@php $tower = $btsTower ?? null; @endphp

<style>
    .bts-form { color: #e5e7eb; }

    .bts-form .section-card {
        background: #171e33;
        border: 1px solid #2a3252;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .bts-form .section-header {
        background: #1f2745;
        padding: 14px 20px;
        border-bottom: 1px solid #2a3252;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #f3f4f6;
    }
    .bts-form .section-header i {
        color: #818cf8;
        width: 18px;
        text-align: center;
    }
    .bts-form .section-body {
        padding: 22px 20px;
    }

    .bts-form .bts-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 16px;
    }
    .bts-form .c-12 { grid-column: span 12; }
    .bts-form .c-6  { grid-column: span 6; }
    .bts-form .c-4  { grid-column: span 4; }
    .bts-form .c-3  { grid-column: span 3; }
    @media (max-width: 768px) {
        .bts-form .c-6, .bts-form .c-4, .bts-form .c-3 { grid-column: span 12; }
    }

    .bts-form .field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 6px;
    }
    .bts-form .field .req { color: #f87171; }

    .bts-form .field input[type="text"],
    .bts-form .field input[type="number"],
    .bts-form .field input[type="file"],
    .bts-form .field select,
    .bts-form .field textarea {
        width: 100%;
        background: #0f1428;
        border: 1px solid #323b5c;
        border-radius: 8px;
        padding: 10px 12px;
        color: #f3f4f6;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color .15s;
    }
    .bts-form .field input:focus,
    .bts-form .field select:focus,
    .bts-form .field textarea:focus {
        outline: none;
        border-color: #818cf8;
    }
    .bts-form .field textarea { resize: vertical; min-height: 70px; }

    .bts-form .field select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 36px;
    }

    .bts-form .field .hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }

    .bts-form .unit-wrap { position: relative; }
    .bts-form .unit-wrap input { padding-right: 34px; }
    .bts-form .unit-wrap span {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        color: #9ca3af; font-size: 13px;
    }

    .bts-form .map-hint {
        font-size: 13px;
        color: #9ca3af;
        margin-bottom: 14px;
    }
    .bts-form #picker-map {
        border-radius: 10px;
        border: 1px solid #323b5c;
        margin-top: 14px;
    }

    .bts-form .paste-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .bts-form .paste-row .field { flex: 1; min-width: 240px; margin-bottom: 0; }
    .bts-form .btn-apply {
        background: #4f46e5;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
        height: 42px;
    }
    .bts-form .btn-apply:hover { background: #4338ca; }

    .bts-form .paste-feedback {
        font-size: 12px;
        margin-top: 8px;
        min-height: 16px;
    }
    .bts-form .paste-feedback.ok { color: #4ade80; }
    .bts-form .paste-feedback.err { color: #f87171; }

    .bts-form .photo-preview {
        max-height: 110px;
        border-radius: 8px;
        border: 1px solid #323b5c;
        margin-top: 10px;
    }
</style>

<div class="bts-form">

    {{-- SECTION 1: Informasi Umum --}}
    <div class="section-card">
        <div class="section-header"><i class="fa fa-info-circle"></i> Informasi Umum</div>
        <div class="section-body">
            <div class="bts-grid">
                @if ($tower)
                    <div class="c-4 field">
                        <label>Kode BTS</label>
                        <input type="text" value="{{ $tower->kode_bts }}" disabled style="opacity:.7; cursor:not-allowed;">
                        <div class="hint">Kode dibuat otomatis, tidak dapat diubah</div>
                    </div>
                @else
                    <div class="c-4 field">
                        <label>Kode BTS</label>
                        <input type="text" value="Dibuat otomatis setelah disimpan" disabled style="opacity:.6; cursor:not-allowed; font-style:italic;">
                        <div class="hint">Format: BTS-BOLSEL-{{ date('Y') }}-XXX</div>
                    </div>
                @endif
                <div class="c-6 field">
                    <label>Nama BTS <span class="req">*</span></label>
                    <input type="text" id="nama_bts_input" name="nama_bts" value="{{ old('nama_bts', $tower->nama_bts ?? '') }}" data-auto="{{ $tower ? 'false' : 'true' }}" required placeholder="Otomatis mengikuti alamat / bisa diketik manual">
                    <div class="hint">Otomatis menyesuaikan Kecamatan/Desa/Alamat yang diisi. Ketik manual untuk menimpa.</div>
                </div>

                <div class="c-4 field">
                    <label>Provider <span class="req">*</span></label>
                    <select name="provider" required>
                        @foreach ($providerList as $p)
                            <option value="{{ $p }}" @selected(old('provider', $tower->provider ?? '') == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="c-4 field">
                    <label>Kecamatan <span class="req">*</span></label>
                    <select name="kecamatan" id="kecamatan_select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatanList as $k)
                            <option value="{{ $k }}" @selected(old('kecamatan', $tower->kecamatan ?? '') == $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="c-4 field">
                    <label>Desa/Kelurahan</label>
                    <input type="text" name="desa" id="desa_input" value="{{ old('desa', $tower->desa ?? '') }}" placeholder="Opsional">
                </div>

                <div class="c-12 field">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat_textarea" rows="2" placeholder="Jalan, patokan, atau alamat detail lokasi tower">{{ old('alamat', $tower->alamat ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const namaInput = document.getElementById('nama_bts_input');
            const kecamatanSelect = document.getElementById('kecamatan_select');
            const desaInput = document.getElementById('desa_input');
            const alamatTextarea = document.getElementById('alamat_textarea');

            function generateNamaBts() {
                if (namaInput.dataset.auto !== 'true') return;

                const kecamatan = kecamatanSelect.value;
                const desa = desaInput.value.trim();
                const alamat = alamatTextarea.value.trim();

                if (!kecamatan && !desa && !alamat) {
                    namaInput.value = '';
                    return;
                }

                let parts = ['BTS'];
                if (desa) {
                    parts.push(desa);
                } else if (kecamatan) {
                    parts.push(kecamatan);
                }
                if (alamat) {
                    const ringkas = alamat.length > 40 ? alamat.substring(0, 40).trim() + '...' : alamat;
                    parts.push('- ' + ringkas);
                }

                namaInput.value = parts.join(' ');
            }

            // Kalau user mengetik manual di kotak Nama BTS, matikan mode auto supaya tidak tertimpa lagi
            namaInput.addEventListener('input', function () {
                namaInput.dataset.auto = 'false';
            });

            kecamatanSelect.addEventListener('change', generateNamaBts);
            desaInput.addEventListener('input', generateNamaBts);
            alamatTextarea.addEventListener('input', generateNamaBts);
        })();
    </script>
    <div class="section-card">
        <div class="section-header"><i class="fa fa-map-marker-alt"></i> Titik Lokasi</div>
        <div class="section-body">

            {{-- Paste dari Google Maps --}}
            <div class="paste-row">
                <div class="field">
                    <label>Tempel Koordinat dari Google Maps</label>
                    <input type="text" id="paste-coord" placeholder="Contoh: 0.431700, 123.481700">
                </div>
                <button type="button" class="btn-apply" id="btn-apply-paste"><i class="fa fa-check"></i> Terapkan</button>
            </div>
            <div class="paste-feedback" id="paste-feedback"></div>
            <p class="map-hint">
                Bisa tempel 2 format: <strong>desimal</strong> (contoh: 0.4317, 123.4817) atau <strong>format Google Maps</strong> saat klik nama tempat (contoh: 0°26'16.9"N 124°18'57.4"E).
                Atau, langsung klik pada peta di bawah ini / edit manual kotak Latitude &amp; Longitude.
            </p>

            <div id="picker-map" style="height: 380px;"></div>

            <div class="bts-grid" style="margin-top:16px;">
                <div class="c-6 field">
                    <label>Latitude <span class="req">*</span></label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $tower->latitude ?? '') }}" required placeholder="0.4317000">
                </div>
                <div class="c-6 field">
                    <label>Longitude <span class="req">*</span></label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $tower->longitude ?? '') }}" required placeholder="123.4817000">
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: Spesifikasi Tower --}}
    <div class="section-card">
        <div class="section-header"><i class="fa fa-broadcast-tower"></i> Spesifikasi Tower</div>
        <div class="section-body">
            <div class="bts-grid">
                <div class="c-3 field">
                    <label>Tinggi Tower</label>
                    <div class="unit-wrap">
                        <input type="number" step="0.01" name="tinggi_tower" value="{{ old('tinggi_tower', $tower->tinggi_tower ?? '') }}" placeholder="0">
                        <span>meter</span>
                    </div>
                </div>
                <div class="c-3 field">
                    <label>Tahun Dibangun</label>
                    <input type="number" name="tahun_dibangun" value="{{ old('tahun_dibangun', $tower->tahun_dibangun ?? '') }}" min="1990" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
                </div>
                <div class="c-3 field">
                    <label>Tipe Tower</label>
                    <input type="text" name="tipe_tower" list="tipe-tower-options" value="{{ old('tipe_tower', $tower->tipe_tower ?? '') }}" placeholder="Pilih dari daftar atau ketik manual">
                    <datalist id="tipe-tower-options">
                        @foreach ($tipeTowerList as $t)
                            <option value="{{ $t }}">
                        @endforeach
                    </datalist>
                    <div class="hint">Opsional. Boleh ketik tipe lain di luar daftar.</div>
                </div>
                <div class="c-3 field">
                    <label>Kondisi Fisik</label>
                    <select name="kondisi">
                        <option value="">-- Opsional --</option>
                        @foreach ($kondisiList as $k)
                            <option value="{{ $k }}" @selected(old('kondisi', $tower->kondisi ?? '') == $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="c-4 field">
                    <label>Status Operasional</label>
                    <select name="status_operasional">
                        <option value="">-- Opsional --</option>
                        @foreach ($statusList as $s)
                            <option value="{{ $s }}" @selected(old('status_operasional', $tower->status_operasional ?? '') == $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="c-3 field">
                    <label>Radius Cakupan</label>
                    <div class="unit-wrap">
                        <input type="number" step="0.1" name="coverage_radius" value="{{ old('coverage_radius', $tower->coverage_radius ?? '') }}" placeholder="0" min="0" max="50">
                        <span>km</span>
                    </div>
                    <div class="hint">Estimasi jangkauan sinyal dari tower (opsional)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: Foto & Keterangan --}}
    <div class="section-card">
        <div class="section-header"><i class="fa fa-camera"></i> Foto & Keterangan Tambahan</div>
        <div class="section-body">
            <div class="bts-grid">
                <div class="c-6 field">
                    <label>Foto BTS</label>
                    <input type="file" name="foto" accept="image/*">
                    <div class="hint">Format JPG/PNG, maksimal 2MB</div>
                    @if (!empty($tower?->foto))
                        <img src="{{ $tower->foto_url }}" class="photo-preview">
                    @endif
                </div>
                <div class="c-6 field">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="4" placeholder="Catatan tambahan, misalnya akses jalan, kondisi khusus, dsb">{{ old('keterangan', $tower->keterangan ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

</div>
