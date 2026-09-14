<?php $__env->startSection('default_orientation', 'portrait'); ?>
<?php $__env->startSection('report_class', 'portrait'); ?>


<?php $__env->startSection('title', 'Cetak Berita Acara Stock Opname'); ?>
<?php $__env->startSection('back_url', route('reports.opname.list')); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .report-paper { max-width: 210mm !important; }
    @media print {
        @page { size: 210mm 330mm; margin: 10mm 15mm; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report_content'); ?>
<div id="print-area">
  <div class="mb-4">
    <?php echo $__env->make('partials.kop', ['opd' => $opd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </div>

  <div class="text-center mb-4">
    <h2 class="font-bold text-lg">BERITA ACARA</h2>
    <h2 class="font-bold text-lg underline uppercase">HASIL STOCK OPNAME PERSEDIAAN BARANG HABIS PAKAI</h2>
    <p class="text-sm">NO: <?php echo e($data['nomor'] ?? ''); ?></p>
  </div>

  <p class="mb-3 text-sm">
    <?php echo e($data['pembuka'] ?? 'Pada hari ini ' . \Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('l d F Y') . ', bertempat di ' . \Illuminate\Support\Str::title(($opd->nama_opd ?? null) ?: ($data['tempat'] ?? '-')) . ' Kabupaten Bolaang Mongondow Selatan, yang bertanda tangan dibawah ini:'); ?>

  </p>

  <div class="mb-4">
    <table class="w-full text-sm">
        <tr>
          <td class="w-28 align-top">Nama</td>
          <td class="w-4 align-top">:</td>
          <td class="align-top"><span class="font-bold"><?php echo e($data['pihak_kedua']['nama']); ?></span></td>
        </tr>
        <tr>
          <td class="align-top">NIP</td>
          <td class="align-top">:</td>
          <td class="align-top"><?php echo e($data['pihak_kedua']['nip'] ?? '-'); ?></td>
        </tr>
        <tr>
          <td class="align-top">Jabatan</td>
          <td class="align-top">:</td>
          <td class="align-top"><?php echo e($data['pihak_kedua']['jabatan']); ?></td>
        </tr>
    </table>
    <p class="mt-2 text-sm">Sebagai pengurus barang pengguna berdasarkan Surat Keputusan Bupati Bolaang
      Mongondow Selatan Nomor: <?php echo e($opd->pengurus_sk ?? '27 Tahun 2026'); ?> telah melaksanakan Stock Opname
      Persediaan Barang Habis Pakai per 
      <?php echo e(\Illuminate\Support\Carbon::parse($data['tanggal'])->locale('id')->translatedFormat('d F Y')); ?>,
      dengan hasil sebagai berikut</p>
  </div>

  <div class="mb-6">
    <table class="w-full text-[10px] border-collapse" style="table-layout: fixed; border: 1px solid black;">
      <colgroup>
        <col style="width: 30px;">
        <col>
        <col style="width: 55px;">
        <col style="width: 50px;">
        <col style="width: 90px;">
        <col style="width: 100px;">
        <col style="width: 20px;">
        <col style="width: 20px;">
        <col style="width: 20px;">
      </colgroup>
      <thead>
        <tr class="text-center font-bold">
          <th class="border border-black p-1" rowspan="2">No</th>
          <th class="border border-black p-1" rowspan="2">Nama Jenis Persediaan Barang</th>
          <th class="border border-black p-1" rowspan="2">Kwantitas</th>
          <th class="border border-black p-1" rowspan="2">Satuan</th>
          <th class="border border-black p-1" rowspan="2">Harga Satuan (Rp)</th>
          <th class="border border-black p-1" rowspan="2">Jumlah Harga (Rp)</th>
          <th class="border border-black p-1" colspan="3">Kondisi</th>
        </tr>
        <tr class="text-center font-bold text-[8px]">
          <th class="border border-black p-1">B</th>
          <th class="border border-black p-1">RR</th>
          <th class="border border-black p-1">RB</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; ?>
        <?php $__currentLoopData = $data['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php $total += (int)($item['jumlah'] ?? 0); ?>
          <tr>
            <td class="border border-black p-1 text-center"><?php echo e($i + 1); ?></td>
            <td class="border border-black p-1"><?php echo e($item['nama']); ?></td>
            <td class="border border-black p-1 text-center"><?php echo e($item['kuantitas']); ?></td>
            <td class="border border-black p-1 text-center"><?php echo e($item['satuan'] ?? '-'); ?></td>
            <td class="border border-black p-1 text-right"><?php echo e(number_format($item['harga'] ?? 0, 0, ',', '.')); ?></td>
            <td class="border border-black p-1 text-right"><?php echo e(number_format($item['jumlah'] ?? 0, 0, ',', '.')); ?></td>
            <td class="border border-black p-1 text-center"><?php echo e(isset($item['kondisi']) && $item['kondisi'] === 'B' ? 'V' : ''); ?></td>
            <td class="border border-black p-1 text-center"><?php echo e(isset($item['kondisi']) && $item['kondisi'] === 'RR' ? 'V' : ''); ?></td>
            <td class="border border-black p-1 text-center"><?php echo e(isset($item['kondisi']) && $item['kondisi'] === 'RB' ? 'V' : ''); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr class="font-bold">
          <td colspan="5" class="border border-black p-1 text-right">Jumlah</td>
          <td class="border border-black p-1 text-right"><?php echo e(number_format($total, 0, ',', '.')); ?></td>
          <td colspan="3" class="border border-black p-1"></td>
        </tr>
      </tbody>
    </table>
  </div>

  <p class="mb-2 text-sm">Demikian Berita Acara Stock Opname Persediaan Barang Habis Pakai ini dibuat untuk diperlukan sebagaimana mestinya.</p>
  
  <div class="grid grid-cols-2 gap-5 mt-2" style="page-break-inside: avoid;">
    <div class="text-center">
      <p class="mb-1">&nbsp;</p>
      <p class="mb-1 uppercase font-bold text-xs">Pengurus Barang Pengguna</p>
      <div class="h-20"></div>
      <p class="font-bold underline"><?php echo e($opd->pengurus_nama ?? ($data['pihak_kedua']['nama'] ?? '')); ?></p>
      <p class="text-xs">NIP. <?php echo e($opd->pengurus_nip ?? ($data['pihak_kedua']['nip'] ?? '-')); ?></p>
    </div>
    <div class="text-center">
      <p class="mb-1 uppercase font-bold text-xs">Mengetahui</p>
      <p class="mb-1 uppercase font-bold text-xs"><?php echo e($opd->kepala_jabatan ?? ('Kepala ' . \Illuminate\Support\Str::title($opd->nama_opd ?? 'Dinas Komunikasi dan Informatika'))); ?></p>
      <div class="h-20"></div>
      <p class="font-bold underline"><?php echo e($opd->kepala_nama ?? ($data['pihak_pertama']['nama'] ?? '')); ?></p>
      <p class="text-xs">NIP. <?php echo e($opd->kepala_nip ?? ($data['pihak_pertama']['nip'] ?? '-')); ?></p>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.report_print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/reports/opname_report.blade.php ENDPATH**/ ?>