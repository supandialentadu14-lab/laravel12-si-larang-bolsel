@if($is_first ?? true)
@include('partials.kop', ['opd' => $opd])

<div class="text-center mb-6" style="margin-top: 10px;">
  <h2 class="font-bold underline uppercase" style="font-size: 16px;">BERITA ACARA PENERIMAAN BARANG/PEKERJAAN</h2>
  <p style="font-size: 14px; margin-top: 1px;">NOMOR: {{ $data['nomor'] ?? '' }}</p>
</div>

<p>{{ $data['tanggal_kata'] ?? '' }}</p>

<table style="border: none; width: 100%; margin: 10px 0; line-height: 1.25;">
  <tr style="border: none;">
    <td style="border: none; width: 120px; padding: 2px 0;">Nama</td>
    <td style="border: none; width: 15px; padding: 2px 0;">:</td>
    <td style="border: none; padding: 2px 0;"><span class="font-bold">{{ $data['pengguna']['nama'] ?? '' }}</span></td>
  </tr>
  <tr style="border: none;">
    <td style="border: none; padding: 2px 0;">NIP</td>
    <td style="border: none; padding: 2px 0;">:</td>
    <td style="border: none; padding: 2px 0;">{{ $data['pengguna']['nip'] ?? '' }}</td>
  </tr>
  <tr style="border: none;">
    <td style="border: none; padding: 2px 0;">Jabatan</td>
    <td style="border: none; padding: 2px 0;">:</td>
    <td style="border: none; padding: 2px 0;">{{ $data['pengguna']['jabatan'] ?? 'Pengurus Barang Pengguna' }}</td>
  </tr>
</table>

<p>Berdasarkan Berita Acara Pemeriksaan Barang Nomor: {{ $data['pemeriksaan_nomor'] ?? '-' }}. Telah menerima barang yang diserahkan oleh Pihak Ketiga sebagai berikut :</p>
@else
  <div class="text-center mb-4">
    <h2 class="font-bold uppercase" style="font-size: 14px; text-decoration: underline;">SAMBUNGAN BAPENERIMAAN - {{ $data['nomor'] }}</h2>
  </div>
@endif

<table class="report-table">
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
      <td class="text-right">
        <div style="display: flex; justify-content: space-between;">
          <span>Rp</span>
          <span>{{ number_format((int)($row['harga'] ?? 0), 0, ',', '.') }}</span>
        </div>
      </td>
      <td class="text-right font-bold">
        <div style="display: flex; justify-content: space-between;">
          <span>Rp</span>
          <span>{{ number_format((int)($row['jumlah'] ?? 0), 0, ',', '.') }}</span>
        </div>
      </td>
    </tr>
    @endforeach
    <tr style="background-color: #f8fafc;">
      <td colspan="5" class="text-right font-bold">Jumlah</td>
      <td class="text-right font-bold">
        <div style="display: flex; justify-content: space-between;">
          <span>Rp</span>
          <span>{{ number_format((int)($data['total'] ?? 0), 0, ',', '.') }}</span>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="6" class="text-center font-bold italic" style="padding: 10px;">Terbilang : {{ $data['terbilang'] ?? '' }} rupiah</td>
    </tr>
  </tbody>
</table>

@if($is_last ?? true)
<div class="signature-section">
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 40px; text-align: center; font-size: 12px; line-height: 1.25;" class="signature-block">
  <div>
    <div class="font-bold">Yang Menerima,</div>
    <div class="font-bold">Pengurus Barang Pengguna</div>
    <div style="height: 60px;"></div>
    <div class="font-bold underline">{{ $data['pengguna']['nama'] ?? '' }}</div>
    <div>NIP: {{ $data['pengguna']['nip'] ?? '' }}</div>
  </div>
  <div>
    <div class="font-bold">Mengetahui,</div>
    <div class="font-bold">Pejabat Pembuat Komitmen</div>
    <div style="height: 60px;"></div>
    <div class="font-bold underline">{{ $data['ppk']['nama'] ?? '' }}</div>
    <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
  </div>
</div>

<div class="text-center signature-block" style="margin-top: 40px; font-size: 12px; line-height: 1.25;">
  <div class="font-bold">Mengetahui,</div>
  <div class="font-bold">Pengguna Anggaran Selaku PPK</div>
  <div style="height: 60px;"></div>
  <div class="font-bold underline">{{ $data['ppk']['nama'] ?? '' }}</div>
  <div>NIP: {{ $data['ppk']['nip'] ?? '' }}</div>
</div>
</div>
@endif
