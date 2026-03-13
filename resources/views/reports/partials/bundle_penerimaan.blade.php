@include('partials.kop', ['opd' => $opd])

<div class="text-center mb-6" style="margin-top: 30px;">
    <h2 class="font-bold" style="font-size: 18px; margin-bottom: 0;">BERITA ACARA PENERIMAAN BARANG/PEKERJAAN</h2>
    <p style="font-size: 14px;">NOMOR: {{ $data['nomor'] ?? '' }}</p>
</div>

<p>{{ $data['tanggal_kata'] ?? '' }}</p>

<table style="border: none; width: 100%; margin: 15px 0;">
    <tr style="border: none;">
        <td style="border: none; width: 120px;">Nama</td>
        <td style="border: none; width: 15px;">:</td>
        <td style="border: none;"><span class="font-bold">{{ $data['pengguna']['nama'] ?? '' }}</span></td>
    </tr>
    <tr style="border: none;">
        <td style="border: none;">NIP</td>
        <td style="border: none;">:</td>
        <td style="border: none;">{{ $data['pengguna']['nip'] ?? '' }}</td>
    </tr>
    <tr style="border: none;">
        <td style="border: none;">Jabatan</td>
        <td style="border: none;">:</td>
        <td style="border: none;">{{ $data['pengguna']['jabatan'] ?? 'Pengurus Barang Pengguna' }}</td>
    </tr>
</table>

<p>Berdasarkan Berita Acara Pemeriksaan Barang Nomor: {{ $data['pemeriksaan_nomor'] ?? '-' }}. Telah menerima barang yang diserahkan oleh Pihak Ketiga sebagai berikut :</p>

<table>
    <thead>
        <tr class="text-center font-bold" style="background-color: #f8fafc;">
            <th style="width:30px">No</th>
            <th>Jenis Bahan/Alat (Barang)</th>
            <th style="width:80px">Kuantitas</th>
            <th style="width:80px">Satuan</th>
            <th style="width:120px">Harga Satuan</th>
            <th style="width:120px">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; @endphp
        @foreach(($data['items'] ?? []) as $row)
        <tr>
            <td class="text-center">{{ $i++ }}</td>
            <td>{{ $row['nama'] ?? '' }}</td>
            <td class="text-center">{{ $row['kuantitas'] ?? '' }}</td>
            <td class="text-center">{{ $row['satuan'] ?? '' }}</td>
            <td class="text-right">Rp {{ number_format((int)($row['harga'] ?? 0), 0, ',', '.') }}</td>
            <td class="text-right font-bold">Rp {{ number_format((int)($row['jumlah'] ?? 0), 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr style="background-color: #f8fafc;">
            <td colspan="5" class="text-right font-bold">Jumlah</td>
            <td class="text-right font-bold" style="font-size: 14px; color: #1e293b;">Rp {{ number_format((int)($data['total'] ?? 0), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="6" class="text-center font-bold italic" style="padding: 10px;">Terbilang : {{ $data['terbilang'] ?? '' }} rupiah</td>
        </tr>
    </tbody>
</table>

<div class="signature-block" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center;">
    <div>
        <div class="font-bold">Yang Menerima,</div>
        <div class="font-bold">Pengurus Barang Pengguna</div>
        <div style="height: 80px;"></div>
        <div class="font-bold underline uppercase">{{ $data['pengguna']['nama'] ?? '' }}</div>
        <div>NIP: {{ $data['pengguna']['nip'] ?? '' }}</div>
    </div>
    <div>
        <div class="font-bold">Mengetahui,</div>
        <div class="font-bold">Pejabat Pembuat Komitmen</div>
        <div style="height: 80px;"></div>
        <div class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</div>
        <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
    </div>
</div>

<div class="text-center signature-block" style="margin-top: 40px;">
    <div class="font-bold">MENGETAHUI,</div>
    <div class="font-bold">PENGGUNA ANGGARAN SELAKU PPK</div>
    <div style="height: 80px;"></div>
    <div class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</div>
    <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
</div>
