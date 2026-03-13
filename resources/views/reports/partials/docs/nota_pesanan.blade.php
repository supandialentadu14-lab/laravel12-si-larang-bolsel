@include('partials.kop', ['opd' => $opd])

<div class="text-right mb-4">
    <p>Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
</div>

<table style="border: none; width: 100%; margin-bottom: 16px; line-height: 1.25;">
    <tr style="border: none;">
        <td style="border: none; width: 100px; padding: 2px 0;">Nomor</td>
        <td style="border: none; width: 15px; padding: 2px 0;">:</td>
        <td style="border: none; padding: 2px 0;" class="font-bold">{{ $data['nomor'] }}</td>
        <td style="border: none; width: 50px; padding: 2px 0;"></td>
        <td style="border: none; padding: 2px 0;">Kepada Yth.</td>
    </tr>
    <tr style="border: none;">
        <td style="border: none; padding: 2px 0;">Lampiran</td>
        <td style="border: none; padding: 2px 0;">:</td>
        <td style="border: none; padding: 2px 0;">-</td>
        <td style="border: none; padding: 2px 0;"></td>
        <td style="border: none; padding: 2px 0;" class="font-bold">{{ $data['penyedia']['toko'] ?? '' }}</td>
    </tr>
    <tr style="border: none;">
        <td style="border: none; vertical-align: top; padding: 2px 0;">Perihal</td>
        <td style="border: none; vertical-align: top; padding: 2px 0;">:</td>
        <td style="border: none; vertical-align: top; padding: 2px 0;" class="font-bold">
            Belanja {{ $data['belanja'] }} Pada Keg. {{ $data['kegiatan'] }} Sub Keg. {{ $data['sub_kegiatan'] }} Tahun {{ $data['tahun'] }}
        </td>
        <td style="border: none; padding: 2px 0;"></td>
        <td style="border: none; vertical-align: top; padding: 2px 0;">di-<br><span style="padding-left: 20px;">Tempat</span></td>
    </tr>
</table>

<div class="text-center mb-6">
    <h2 class="font-bold uppercase" style="font-size: 18px;">NOTA PESANAN BARANG / BAHAN</h2>
</div>

<p>Dengan hormat,</p>
<p>Untuk keperluan pengadaan {{ $data['belanja'] }} dalam Kegiatan {{ $data['kegiatan'] }}, Sub Kegiatan {{ $data['sub_kegiatan'] }} pada Tahun {{ $data['tahun'] }}, harap dapat diberikan barang/bahan di bawah ini:</p>

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
        @php $grand = 0; @endphp
        @foreach ($data['items'] as $i => $row)
            @php $grand += $row['total']; @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row['name'] }}</td>
                <td class="text-center">{{ $row['qty'] }}</td>
                <td class="text-center">{{ $row['unit'] }}</td>
                <td class="text-right">Rp {{ number_format($row['price'], 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr style="background-color: #f8fafc;">
            <td colspan="5" class="text-right font-bold">Jumlah Total</td>
            <td class="text-right font-bold" style="font-size: 14px; color: #1e293b;">Rp {{ number_format($grand, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<p style="margin-top: 20px;" class="font-bold">Dengan Ketentuan :</p>
<div style="padding-left: 20px;">
    <p>1. Pembayaran melalui bendahara pengeluaran {{ $opd->nama_opd ?? '' }}.</p>
    <p>2. Pembayaran dilaksanakan apabila barang-bahan tersebut telah diperiksa oleh Panitia Pemeriksa Barang sesuai dengan kualitas dan kuantitas barang yang diperiksa.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center; font-size: 12px; line-height: 1.25;" class="signature-block">
    <div>
        <p style="margin: 2px 0;">&nbsp;</p>
        <p style="margin: 2px 0;">Setuju Untuk Melaksanakan Pekerjaan</p>
        <div style="height: 80px;"></div>
        <p class="font-bold underline uppercase" style="margin: 2px 0;">{{ $data['penyedia']['pemilik'] ?? '' }}</p>
    </div>
    <div>
        <p style="margin: 2px 0;">Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
        <p style="margin: 2px 0;">Pejabat Pengadaan</p>
        <div style="height: 80px;"></div>
        <p class="font-bold underline uppercase" style="margin: 2px 0;">{{ $data['pejabat']['nama'] ?? '' }}</p>
        <p style="margin: 2px 0;">NIP. {{ $data['pejabat']['nip'] ?? '-' }}</p>
    </div>
</div>

<div class="text-center signature-block" style="margin-top: 40px; font-size: 12px; line-height: 1.25;">
    <p style="margin: 2px 0;">MENGETAHUI,</p>
    <p style="margin: 2px 0;">PENGGUNA ANGGARAN SELAKU PPK</p>
    <div style="height: 80px;"></div>
    <p class="font-bold underline uppercase" style="margin: 2px 0;">{{ $data['ppk']['nama'] ?? '' }}</p>
    <p style="margin: 2px 0;">NIP. {{ $data['ppk']['nip'] ?? '-' }}</p>
</div>
