<div class="text-center font-bold text-2xl mb-6 uppercase italic">KWITANSI</div>

<div class="border-kwt">
    <table style="width: 100%;">
        <tr>
            <td style="width: 200px;" class="italic">TAHUN ANGGARAN</td>
            <td style="width: 20px;" class="text-center">:</td>
            <td>{{ $data['tahun'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="italic">KODE REKENING</td>
            <td class="text-center">:</td>
            <td>{{ $data['rekening'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="italic">NO. KWT</td>
            <td class="text-center">:</td>
            <td>{{ $data['nomor_kwt'] ?? '' }}</td>
        </tr>
    </table>

    <table class="border-t-kwt" style="width: 100%;">
        <tr>
            <td style="width: 200px; vertical-align: top;" class="italic">Sudah Terima Dari</td>
            <td style="width: 20px; vertical-align: top;" class="text-center">:</td>
            <td class="italic">Bendahara Pengeluaran {{ $data['opd_nama'] ?? ($opd->nama_opd ?? '') }} Kabupaten Bolaang Mongondow Selatan</td>
        </tr>
        <tr>
            <td style="vertical-align: top;" class="italic">Banyaknya Uang</td>
            <td style="vertical-align: top;" class="text-center">:</td>
            <td class="italic font-bold">{{ $data['terbilang'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;" class="italic">Untuk Pembayaran</td>
            <td style="vertical-align: top;" class="text-center">:</td>
            <td class="italic">{{ $data['pembayaran_uraian'] ?? '' }}</td>
        </tr>
    </table>

    <div class="border-t-kwt flex font-bold italic" style="display: flex; border-top: 1px solid black;">
        <div class="border-r-kwt" style="padding: 10px 15px; width: 100px; border-right: 1px solid black; font-size: 18px;">Rp</div>
        <div style="padding: 10px 15px; font-size: 18px;">{{ number_format($data['jumlah'] ?? 0, 0, ',', '.') }}</div>
    </div>

    <div class="signature-block" style="padding: 30px;">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 30px;">
            <div class="text-right">{{ $data['lokasi_tanggal'] ?? '' }}</div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center; margin-bottom: 40px;">
            <div>
                <div class="italic font-bold">PPTK</div>
                <div style="height: 80px;"></div>
                <div class="font-bold underline uppercase">{{ $data['pejabat']['pptk'] ?? '' }}</div>
                <div style="font-size: 12px;">NIP. {{ $data['pptk_nip'] ?? '-' }}</div>
            </div>
            <div>
                <div class="italic font-bold">Bendahara Pengeluaran,</div>
                <div style="height: 80px;"></div>
                <div class="font-bold underline uppercase">{{ $data['pejabat']['bendahara'] ?? '' }}</div>
                <div style="font-size: 12px;">NIP. {{ $data['bendahara_nip'] ?? '-' }}</div>
            </div>
            <div>
                <div class="italic font-bold">Yang Menerima,</div>
                <div class="italic font-bold">Pihak Ketiga</div>
                <div style="height: 60px;"></div>
                <div class="font-bold underline uppercase">{{ $data['pejabat']['pihak_ketiga'] ?? '' }}</div>
            </div>
        </div>

        <div class="text-center" style="margin-top: 40px;">
            <div class="italic font-bold">Mengetahui,</div>
            <div class="italic font-bold">Pengguna Anggaran</div>
            <div style="height: 80px;"></div>
            <div class="font-bold underline uppercase">{{ $data['pejabat']['pengguna'] ?? '' }}</div>
            <div style="font-size: 12px;">NIP. {{ $data['ppk_nip'] ?? '-' }}</div>
        </div>
    </div>
</div>
