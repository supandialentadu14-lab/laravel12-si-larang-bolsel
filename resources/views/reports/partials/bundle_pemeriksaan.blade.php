@include('partials.kop', ['opd' => $opd])

<div class="text-center mb-6" style="margin-top: 30px;">
  <h2 class="font-bold" style="font-size: 18px; margin-bottom: 0;">BERITA ACARA PEMERIKSAAN BARANG/PEKERJAAN</h2>
  <p style="font-size: 14px;">NOMOR: {{ $data['nomor'] ?? '' }}</p>
</div>

<p>{{ $data['tanggal_kata'] ?? ('Pada hari ' . \Carbon\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l') . ' tanggal ' . \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y')) }}, kami yang bertanda tangan di bawah ini:</p>

<table style="border: none; width: 100%; margin: 15px 0;">
  <tr style="border: none;">
    <td style="border: none; width: 120px;">Nama</td>
    <td style="border: none; width: 15px;">:</td>
    <td style="border: none;"><span class="font-bold">{{ $data['ppk']['nama'] ?? '' }}</span></td>
  </tr>
  <tr style="border: none;">
    <td style="border: none;">Jabatan</td>
    <td style="border: none;">:</td>
    <td style="border: none;">Pejabat Pembuat Komitmen</td>
  </tr>
  <tr style="border: none;">
    <td style="border: none;">Alamat</td>
    <td style="border: none;">:</td>
    <td style="border: none;">{{ $data['ppk']['alamat'] ?? '' }}</td>
  </tr>
</table>

<p>Menerangkan dengan benar bahwa Pihak Pertama telah menyerahkan pekerjaan : <span class="font-bold">{{ $data['nota']['belanja'] ?? '' }}</span></p>

<table style="border: none; width: 100%; margin: 15px 0;">
  <tr style="border: none;">
    <td style="border: none; width: 150px;">Nama Penyedia Jasa</td>
    <td style="border: none; width: 15px;">:</td>
    <td style="border: none;" class="font-bold">{{ $data['nota']['penyedia']['toko'] ?? '' }}</td>
  </tr>
  <tr style="border: none;">
    <td style="border: none; vertical-align: top;">Alamat</td>
    <td style="border: none; vertical-align: top;">:</td>
    <td style="border: none;">{{ $data['nota']['penyedia']['alamat'] ?? '' }}</td>
  </tr>
</table>

<p>Sebagai realisasi Nota Pesanan Nomor : {{ $data['nota']['nomor'] ?? '-' }} tanggal {{ \Carbon\Carbon::parse($data['nota']['tanggal'] ?? now())->locale('id')->translatedFormat('d F Y') }}, dengan jumlah/jenis daftar barang terlampir dan berkesimpulan bahwa barang/pekerjaan dapat diterima sesuai mestinya:</p>

<table>
  <thead>
    <tr class="text-center font-bold" style="background-color: #f8fafc;">
      <th style="width:30px">No</th>
      <th>Jenis Bahan/Alat (Barang)</th>
      <th style="width:80px">Kuantitas</th>
      <th style="width:80px">Satuan</th>
      <th style="width:120px">Harga Satuan</th>
      <th style="width:120px">Total</th>
      <th style="width:100px">Keterangan</th>
    </tr>
  </thead>
  <tbody>
    @php $total = 0; @endphp
    @foreach ($data['items'] as $i => $item)
      @php $total += (int)($item['jumlah'] ?? 0); @endphp
      <tr>
        <td class="text-center">{{ $i + 1 }}</td>
        <td>{{ $item['nama'] }}</td>
        <td class="text-center">{{ $item['kuantitas'] }}</td>
        <td class="text-center">{{ $item['satuan'] ?? '-' }}</td>
        <td class="text-right">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
        <td class="text-right font-bold">Rp {{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}</td>
        <td></td>
      </tr>
    @endforeach
    <tr style="background-color: #f8fafc;">
      <td colspan="5" class="text-right font-bold">Jumlah Total</td>
      <td class="text-right font-bold" style="font-size: 14px; color: #1e293b;">Rp {{ number_format($total, 0, ',', '.') }}</td>
      <td></td>
    </tr>
    <tr>
      <td colspan="7" class="text-center font-bold italic" style="padding: 10px;">Terbilang : {{ \Illuminate\Support\Str::upper($data['terbilang'] ?? '') }} rupiah</td>
    </tr>
  </tbody>
</table>

<div style="margin-top: 20px;">
  <p>1. Barang Baik (V)</p>
  <p>2. Barang Tidak Baik (X)</p>
</div>

<div class="signature-block" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center;">
  <div>
    <p>Penyedia</p>
    <div style="height: 80px;"></div>
    <p class="font-bold underline uppercase">{{ $data['nota']['penyedia']['toko'] ?? '' }}</p>
  </div>
  <div>
    <p>Pejabat Pembuat Komitmen</p>
    <div style="height: 80px;"></div>
    <p class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</p>
    <p>NIP. {{ $data['ppk']['nip'] ?? '' }}</p>
  </div>
</div>

<div class="text-center signature-block" style="margin-top: 40px;">
  <p>MENGETAHUI,</p>
  <p>PENGGUNA ANGGARAN SELAKU PPK</p>
  <div style="height: 80px;"></div>
  <p class="font-bold underline uppercase">{{ $data['ppk']['nama'] ?? '' }}</p>
  <p>NIP. {{ $data['ppk']['nip'] ?? '' }}</p>
</div>

