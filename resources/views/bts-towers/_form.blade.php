@php
    $isEdit = isset($btsTower);
    $action = $isEdit ? route('bts-towers.update', $btsTower) : route('bts-towers.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Nama BTS</label>
        <input type="text" name="nama_bts" value="{{ old('nama_bts', $btsTower->nama_bts ?? '') }}" required
            class="mobile-input appearance-none bg-app-surface" placeholder="Nama BTS">
        @error('nama_bts')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Kode BTS</label>
        <input type="text" name="kode_bts" value="{{ old('kode_bts', $btsTower->kode_bts ?? '') }}"
            class="mobile-input appearance-none bg-app-surface font-mono text-[11px] font-black" required
            placeholder="Otomatis jika kosong">
        @error('kode_bts')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Provider</label>
        <select name="provider" required class="mobile-input appearance-none bg-app-surface">
            @foreach ($providerList as $provider)
                <option value="{{ $provider }}" {{ old('provider', $btsTower->provider ?? '') == $provider ? 'selected' : '' }}>{{ $provider }}</option>
            @endforeach
        </select>
        @error('provider')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Kecamatan</label>
        <select name="kecamatan" required class="mobile-input appearance-none bg-app-surface">
            <option value="">-- Pilih Kecamatan --</option>
            @foreach ($kecamatanList as $kecamatan)
                <option value="{{ $kecamatan }}" {{ old('kecamatan', $btsTower->kecamatan ?? '') === $kecamatan ? 'selected' : '' }}>{{ $kecamatan }}</option>
            @endforeach
        </select>
        @error('kecamatan')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Desa / Kelurahan</label>
        <input type="text" name="desa" value="{{ old('desa', $btsTower->desa ?? '') }}"
            class="mobile-input" placeholder="Opsional">
        @error('desa')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Alamat Lengkap</label>
        <textarea name="alamat" rows="2" class="mobile-input">{{ old('alamat', $btsTower->alamat ?? '') }}</textarea>
        @error('alamat')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Latitude</label>
            <input type="number" step="any" name="latitude" value="{{ old('latitude', $btsTower->latitude ?? '') }}"
                class="mobile-input bg-app-surface" required placeholder="-1.234567">
            @error('latitude')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Longitude</label>
            <input type="number" step="any" name="longitude" value="{{ old('longitude', $btsTower->longitude ?? '') }}"
                class="mobile-input bg-app-surface" required placeholder="123.456789">
            @error('longitude')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Tinggi Tower (meter)</label>
            <input type="number" step="0.01" name="tinggi_tower" value="{{ old('tinggi_tower', $btsTower->tinggi_tower ?? '') }}"
                class="mobile-input bg-app-surface" placeholder="Opsional">
        </div>
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Tipe Tower</label>
            <select name="tipe_tower" class="mobile-input appearance-none bg-app-surface">
                <option value="">-- Pilih Tipe --</option>
                @foreach ($tipeTowerList as $tipe)
                    <option value="{{ $tipe }}" {{ old('tipe_tower', $btsTower->tipe_tower ?? '') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Kondisi Fisik</label>
            <select name="kondisi" class="mobile-input appearance-none bg-app-surface">
                <option value="">-- Pilih Kondisi --</option>
                @foreach ($kondisiList as $kondisi)
                    <option value="{{ $kondisi }}" {{ old('kondisi', $btsTower->kondisi ?? '') === $kondisi ? 'selected' : '' }}>{{ $kondisi }}</option>
                @endforeach
            </select>
        </div>
        <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Status Operasional</label>
            <select name="status_operasional" class="mobile-input appearance-none bg-app-surface">
                <option value="">-- Pilih Status --</option>
                @foreach ($statusList as $status)
                    <option value="{{ $status }}" {{ old('status_operasional', $btsTower->status_operasional ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Tahun Dibangun</label>
        <input type="number" min="1990" max="{{ date('Y') + 1 }}" name="tahun_dibangun" value="{{ old('tahun_dibangun', $btsTower->tahun_dibangun ?? '') }}"
            class="mobile-input bg-app-surface" placeholder="Opsional">
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Foto BTS</label>
        <input type="file" name="foto" accept="image/*" class="mobile-input">
        @if(isset($btsTower) && $btsTower->foto)
            <div class="mt-2">
                <img src="{{ $btsTower->foto_url }}" alt="Foto BTS" class="max-h-32 rounded-lg border border-slate-600">
            </div>
        @endif
        @error('foto')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Keterangan</label>
        <textarea name="keterangan" rows="3" class="mobile-input">{{ old('keterangan', $btsTower->keterangan ?? '') }}</textarea>
    </div>