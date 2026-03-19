<div class="text-center font-bold text-2xl mb-4 uppercase italic">KWITANSI</div>

<div style="border: 1.5px solid black; color: black; background: white;">
  {{-- Bagian Atas: Metadata --}}
  <div style="padding: 10px 15px; border-bottom: 1.5px solid black;">
    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
      <tr>
        <td style="width: 140px; padding: 2px 0;" class="italic uppercase">TAHUN ANGGARAN</td>
        <td style="width: 15px; padding: 2px 0;">:</td>
        <td style="padding: 2px 0;">{{ $data['tahun'] ?? '' }}</td>
      </tr>
      <tr>
        <td style="padding: 2px 0;" class="italic uppercase">KODE REKENING</td>
        <td style="padding: 2px 0;">:</td>
        <td style="padding: 2px 0;">{{ $data['rekening'] ?? '' }}</td>
      </tr>
      <tr>
        <td style="padding: 2px 0;" class="italic uppercase">NO. KWT</td>
        <td style="padding: 2px 0;">:</td>
        <td style="padding: 2px 0;">{{ $data['nomor_kwt'] ?? '' }}</td>
      </tr>
    </table>
  </div>

  {{-- Bagian Tengah: Deskripsi --}}
  <div style="padding: 15px; border-bottom: 1.5px solid black;">
    <table style="width: 100%; border-collapse: collapse; font-size: 13px; line-height: 1.6;">
      <tr>
        <td style="width: 140px; vertical-align: top;" class="italic">Sudah Terima Dari</td>
        <td style="width: 15px; vertical-align: top;">:</td>
        <td style="vertical-align: top;" class="italic">
          Bendahara Pengeluaran {{ \Illuminate\Support\Str::title($data['opd_nama'] ?? ($opd->nama_opd ?? '')) }} Kabupaten Bolaang Mongondow Selatan
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top;" class="italic">Banyaknya Uang</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;" class="text-left font-bold italic">
           {{ $data['terbilang'] ?? '' }}
        </td>
      </tr>
      <tr>
        <td style="vertical-align: top;" class="italic">Untuk Pembayaran</td>
        <td style="vertical-align: top;">:</td>
        <td style="vertical-align: top;" class="italic">
          {{ $data['pembayaran_uraian'] ?? '' }}
        </td>
      </tr>
    </table>
  </div>

  {{-- Bagian Uang (RP) --}}
  <div style="padding: 10px 15px; border-bottom: 1.5px solid black;">
    <div style="border: 1.5px solid black; width: 200px; padding: 5px 10px; display: flex; font-weight: bold; font-style: italic; font-size: 14px;">
      <span style="margin-right: 15px;">Rp</span>
      <span>{{ number_format($data['jumlah'] ?? 0, 0, ',', '.') }},-</span>
    </div>
  </div>

  {{-- Bagian Tanda Tangan --}}
  <div class="signature-block" style="padding: 20px; font-size: 11px;">
    <div style="text-align: right; margin-bottom: 10px;" class="italic">
      Bolaang Uki, &nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: center; table-layout: fixed; font-size: 11px;">
      <tr>
        <td style="vertical-align: top;" class="font-bold italic">Pejabat Pelaksana Teknis Kegiatan</td>
        <td style="vertical-align: top;" class="font-bold italic">Bendahara Pengeluaran,</td>
        <td style="vertical-align: top;" class="font-bold italic">Yang Menerima,<br>Pihak Ketiga</td>
      </tr>
      <tr>
        <td colspan="3" style="height: 70px;"></td>
      </tr>
      <tr>
        <td class="font-bold underline">
          {{ $data['pejabat']['pptk'] ?? ($nota['pptk']['nama'] ?? '') }}
        </td>
        <td class="font-bold underline">
          {{ $data['pejabat']['bendahara'] ?? ($nota['bendahara']['nama'] ?? '') }}
        </td>
        <td class="font-bold underline">
          {{ $data['pejabat']['pihak_ketiga'] ?? ($nota['penyedia']['pemilik'] ?? ($nota['penyedia']['toko'] ?? '')) }}
        </td>
      </tr>
      <tr>
        <td style="font-size: 10px;">
          NIP. {{ $data['pptk_nip'] ?? ($nota['pptk']['nip'] ?? '') }}
        </td>
        <td style="font-size: 10px;">
          NIP. {{ $data['bendahara_nip'] ?? ($nota['bendahara']['nip'] ?? '') }}
        </td>
        <td></td>
      </tr>
    </table>

    <div style="text-align: center; margin-top: 30px;">
      <div class="font-bold italic">Mengetahui,</div>
      <div class="font-bold italic">Pengguna Anggaran</div>
      <div style="height: 60px;"></div>
      <div class="font-bold underline">
        {{ $data['pejabat']['pengguna'] ?? ($nota['ppk']['nama'] ?? '') }}
      </div>
      <div style="font-size: 10px;">
        NIP. {{ $data['ppk_nip'] ?? ($nota['ppk']['nip'] ?? '') }}
      </div>
    </div>
  </div>
</div>
