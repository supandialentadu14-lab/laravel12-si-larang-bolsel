@if($is_first ?? true)
@include('partials.kop', ['opd' => $opd])

<div style="display: flex; justify-content: space-between; margin-bottom: 20px; line-height: 1.3;">
  <div style="flex: 1;">
    <table style="border: none; border-collapse: collapse; width: 100%;">
      <tr style="border: none;">
        <td style="border: none; width: 80px; padding: 2px 0; font-size: 14px;">Nomor</td>
        <td style="border: none; width: 15px; padding: 2px 0; font-size: 14px;">:</td>
        <td style="border: none; padding: 2px 0; font-size: 14px;" class="font-bold">{{ $data['nomor'] }}</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none; padding: 2px 0; font-size: 14px;">Lampiran</td>
        <td style="border: none; padding: 2px 0; font-size: 14px;">:</td>
        <td style="border: none; padding: 2px 0; font-size: 14px;">-</td>
      </tr>
      <tr style="border: none;">
        <td style="border: none; vertical-align: top; padding: 2px 0; font-size: 14px;">Perihal</td>
        <td style="border: none; vertical-align: top; padding: 2px 0; font-size: 14px;">:</td>
        <td style="border: none; vertical-align: top; padding: 2px 0; font-size: 14px;" class="font-bold">
          Belanja {{ $data['belanja'] }} Pada Keg. {{ $data['kegiatan'] }} <br>
          Sub Keg. {{ $data['sub_kegiatan'] }} Tahun {{ $data['tahun'] }}
        </td>
      </tr>
    </table>
  </div>
  <div style="width: 250px; padding-left: 60px; font-size: 14px;">
    <p style="margin: 0 0 16px 0;">Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
    <p style="margin: 0;">Kepada Yth.</p>
    <p style="margin: 0;" class="font-bold">{{ $data['penyedia']['toko'] ?? '' }}</p>
    <p style="margin: 0;">di-</p>
    <p style="margin: 0; padding-left: 30px;">Tempat</p>
  </div>
</div>

<div class="text-center mb-6">
  <h2 class="font-bold uppercase" style="font-size: 18px; margin-bottom: -10px; text-decoration: underline;">NOTA PESANAN BARANG / BAHAN</h2>
</div>

<p>Dengan hormat,</p>
<p>Untuk keperluan pengadaan {{ $data['belanja'] }} dalam Kegiatan {{ $data['kegiatan'] }}, Sub Kegiatan {{ $data['sub_kegiatan'] }} pada Tahun {{ $data['tahun'] }}, harap dapat diberikan barang/bahan di bawah ini:</p>
@else
  <div class="text-center mb-4">
    <h2 class="font-bold uppercase" style="font-size: 14px; text-decoration: underline;">SAMBUNGAN NOTA PESANAN - {{ $data['nomor'] }}</h2>
  </div>
@endif

<table class="report-table" style="table-layout: fixed; width: 100%;">
  <thead>
    <tr class="text-center font-bold" style="background-color: #f8fafc;">
      <th style="width:30px; font-size:12px;">No</th>
      <th style="font-size:12px;">Jenis Bahan/Alat (Barang)</th>
      <th style="width:70px; font-size:12px;">Kuantitas</th>
      <th style="width:70px; font-size:12px;">Satuan</th>
      <th style="width:110px; font-size:12px;">Harga Satuan</th>
      <th style="width:110px; font-size:12px;">Total</th>
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
        <td class="text-right">
          <div style="display: flex; justify-content: space-between;">
            <span>Rp</span>
            <span>{{ number_format($row['price'] ?? 0, 0, ',', '.') }}</span>
          </div>
        </td>
        <td class="text-right font-bold">
          <div style="display: flex; justify-content: space-between;">
            <span>Rp</span>
            <span>{{ number_format($row['total'] ?? 0, 0, ',', '.') }}</span>
          </div>
        </td>
      </tr>
    @endforeach
    <tr style="background-color: #f8fafc;">
      <td colspan="5" class="text-right font-bold">Jumlah Total</td>
      <td class="text-right font-bold">
        <div style="display: flex; justify-content: space-between;">
          <span>Rp</span>
          <span>{{ number_format($grand, 0, ',', '.') }}</span>
        </div>
      </td>
    </tr>
  </tbody>
</table>

@if($is_last ?? true)
<p style="margin-top: 20px; font-size: 14px;" class="font-bold">Dengan Ketentuan :</p>
<table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 10px;">
  <tr>
    <td style="width: 18px; vertical-align: top; border: none; font-size: 14px;">1.</td>
    <td style="text-align: justify; padding-left: 6px; border: none; font-size: 14px;">
      Pembayaran melalui bendahara pengeluaran {{ \Illuminate\Support\Str::title($opd->nama_opd ?? '') }}.
    </td>
  </tr>
  <tr>
    <td style="width: 18px; vertical-align: top; border: none; font-size: 14px;">2.</td>
    <td style="text-align: justify; padding-left: 6px; border: none; font-size: 14px;">
      Pembayaran dilaksanakan apabila barang-bahan tersebut telah diperiksa oleh Panitia Pemeriksa Barang sesuai dengan kualitas dan kuantitas barang yang diperiksa.
    </td>
  </tr>
</table>

<div class="signature-section">
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 20px; text-align: center; font-size: 12px; line-height: 1.25;" class="signature-block">
  <div>
    <p style="margin: 2px 0;">&nbsp;</p>
    <p style="margin: 2px 0;">Setuju Untuk Melaksanakan Pekerjaan</p>
    <div style="height: 60px;"></div>
    <p class="font-bold underline" style="margin: 2px 0;">{{ $data['penyedia']['pemilik'] ?? '' }}</p>
  </div>
  <div>
    <p style="margin: 2px 0;">Bolaang Uki, {{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</p>
    <p style="margin: 2px 0;">Pejabat Pengadaan</p>
    <div style="height: 60px;"></div>
    <p class="font-bold underline" style="margin: 2px 0;">{{ $data['pejabat']['nama'] ?? '' }}</p>
    <p style="margin: 2px 0;">NIP. {{ $data['pejabat']['nip'] ?? '-' }}</p>
  </div>
</div>

<div class="text-center signature-block" style="margin-top: 40px; font-size: 12px; line-height: 1.25;">
  <p style="margin: 2px 0;">Mengetahui,</p>
  <p style="margin: 2px 0;">Pengguna Anggaran Selaku PPK</p>
  <div style="height: 60px;"></div>
  <p class="font-bold underline" style="margin: 2px 0;">{{ $data['ppk']['nama'] ?? '' }}</p>
  <p style="margin: 2px 0;">NIP. {{ $data['ppk']['nip'] ?? '-' }}</p>
</div>
</div>
@endif
