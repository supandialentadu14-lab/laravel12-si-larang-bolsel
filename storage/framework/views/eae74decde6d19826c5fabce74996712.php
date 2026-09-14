<?php $__env->startSection('title', 'Cetak Kartu Persediaan Tahunan'); ?>
<?php $__env->startSection('report_class', 'landscape'); ?>
<?php $__env->startSection('report_size', '330mm 215mm landscape'); ?>
<?php $__env->startSection('report_width', '330mm'); ?>
<?php $__env->startSection('report_height', '215mm'); ?>
<?php $__env->startSection('back_url', route('dashboard')); ?>

<?php $__env->startSection('extra_styles'); ?>
    <style>
        /* Specific tweaks for Kartu Tahunan */
        .report-table {
            min-width: 100% !important;
            /* Fit within the paper container */
            font-family: 'Inter', sans-serif !important;
        }

        .report-table th,
        .report-table td {
            border: 1px solid black;
            padding: 1px 3px !important;
            font-size: 8px !important;
            color: black;
            white-space: nowrap !important;
            line-height: 1.1;
        }





        /* Allow wrapping for long text columns */
        .col-wrap {
            white-space: normal !important;
            overflow-wrap: break-word;
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }

            .info-table,
            .info-table td,
            .signature-table,
            .signature-table td {
                border: none !important;
            }

            .info-table td {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }

        .info-table,
        .info-table td,
        .signature-table,
        .signature-table td {
            border: none !important;
        }

        .info-table td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>


<?php $__env->stopSection(); ?>



<?php $__env->startSection('extra_buttons'); ?>
    <form method="GET" action="<?php echo e(route('reports.kartu.tahunan')); ?>" class="flex flex-col gap-3 w-full">
        <div class="flex flex-col gap-1 w-full text-left">
            <label class="text-[10px] uppercase font-bold text-slate-400">Pilih Barang</label>
            <select name="product_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
                <option value="">-- Semua Barang --</option>
                <?php $__currentLoopData = $allProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->id); ?>" <?php echo e(($product && $product->id == $p->id) ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="grid grid-cols-2 gap-2 w-full text-left">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase font-bold text-slate-400">Dari</label>
                <input type="date" name="start_date" value="<?php echo e($startDate); ?>"
                    class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] uppercase font-bold text-slate-400">Sampai</label>
                <input type="date" name="end_date" value="<?php echo e($endDate); ?>"
                    class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs outline-none bg-white text-slate-700 shadow-sm focus:border-indigo-500">
            </div>
        </div>

        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/10 active:scale-[0.98]">
            Filter Laporan
        </button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report_content'); ?>
    
    <div class="text-center mb-4 pb-2">
        <h2 class="text-xl font-bold uppercase underline">KARTU PERSEDIAAN TAHUNAN</h2>
        <h5 class="text-xs font-semibold mt-1">Per Periode
            <?php echo e(\Carbon\Carbon::parse($startDate)->translatedFormat('d F Y')); ?> -
            <?php echo e(\Carbon\Carbon::parse($endDate)->translatedFormat('d F Y')); ?></h5>
    </div>

    <table class="info-table mb-4">
        <tr>
            <td width="140"><strong>SKPD</strong></td>
            <td width="10">:</td>
            <td><strong><?php echo e($opd->nama_opd ?? '-'); ?></strong></td>
        </tr>
        <tr>
            <td><strong>Kabupaten</strong></td>
            <td>:</td>
            <td><strong>Bolaang Mongondow Selatan</strong></td>
        </tr>
    </table>

    <?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productId => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="product-section mb-4 <?php echo e(!$loop->last ? 'border-b border-dashed border-gray-200 pb-3' : ''); ?>">
            <table class="info-table mb-2">
                <tr>
                    <td width="140"><strong>Nama Barang</strong></td>
                    <td width="10">:</td>
                    <td><strong><?php echo e($data['product']->name); ?></strong></td>
                </tr>
                <tr>
                    <td><strong>Satuan</strong></td>
                    <td>:</td>
                    <td><strong><?php echo e($data['product']->satuan ?? '-'); ?></strong></td>
                </tr>
            </table>

            <table class="report-table text-center w-full">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2" style="width: 30px;">No</th>
                        <th rowspan="2" style="width: 75px;">Tanggal</th>
                        <th rowspan="2" class="col-wrap" style="width: 140px;">Nomor Surat Dasar Penerimaan / Pengeluaran</th>

                        <th rowspan="2" class="col-wrap" style="width: 120px;">Uraian</th>
                        <th colspan="3">Barang-Barang</th>
                        <th rowspan="2" style="width: 65px;">Harga Satuan (Rp)</th>
                        <th colspan="3">Jumlah Harga (Rp)</th>
                        <th rowspan="2" style="width: 30px;">Ket.</th>
                    </tr>
                    <tr class="text-center text-[8.5px]">
                        <th style="width: 35px; white-space: nowrap;">Masuk</th>
                        <th style="width: 35px; white-space: nowrap;">Keluar</th>
                        <th style="width: 35px; white-space: nowrap;">Sisa</th>

                        <th style="width: 75px; white-space: nowrap;">Masuk</th>
                        <th style="width: 75px; white-space: nowrap;">Keluar</th>
                        <th style="width: 85px; white-space: nowrap;">Sisa</th>
                    </tr>
                </thead>


                <tbody>
                    <?php $rowNo = 1; ?>
                    <?php $__currentLoopData = $data['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr align="center" style="font-size: 9px;">
                            <td><?php echo e($rowNo++); ?></td>
                            <td><?php echo e($row['date'] ? \Carbon\Carbon::parse($row['date'])->translatedFormat('d F Y') : '-'); ?></td>
                            <td align="left" class="col-wrap"><?php echo e($row['nosur'] ?? '-'); ?></td>
                            <td align="left" class="uppercase font-semibold col-wrap"><?php echo e($data['product']->name); ?></td>
                            <td><?php echo e($row['masuk'] ? number_format($row['masuk'], 0, ',', '.') : '-'); ?></td>
                            <td><?php echo e($row['keluar'] ? number_format($row['keluar'], 0, ',', '.') : '-'); ?></td>
                            <td class="font-bold"><?php echo e(number_format($row['saldo'], 0, ',', '.')); ?></td>
                            <td align="right"><?php echo e(number_format($row['harga'] ?? 0, 0, ',', '.')); ?></td>
                            <td align="right">
                                <?php echo e($row['masuk'] ? number_format($row['masuk'] * ($row['harga'] ?? 0), 0, ',', '.') : '-'); ?></td>
                            <td align="right">
                                <?php echo e($row['keluar'] ? number_format($row['keluar'] * ($row['harga'] ?? 0), 0, ',', '.') : '-'); ?></td>
                            <td align="right" class="font-bold">
                                <?php echo e(number_format($row['saldo'] * ($row['harga'] ?? 0), 0, ',', '.')); ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="text-[10px]">
                        <td colspan="6" align="center"><strong>Saldo Per
                                <?php echo e(\Carbon\Carbon::parse($endDate)->translatedFormat('d F Y')); ?></strong></td>
                        <td align="center">
                            <strong><?php echo e($data['saldo_akhir'] == 0 ? 'Nihil' : number_format($data['saldo_akhir'], 0, ',', '.')); ?></strong>
                        </td>
                        <td colspan="3"></td>
                        <td align="right">
                            <strong><?php echo e($data['saldo_akhir'] == 0 ? 'Nihil' : number_format($data['saldo_akhir'] * (count($data['rows']) > 0 ? ($data['rows'][count($data['rows']) - 1]['harga'] ?? 0) : 0), 0, ',', '.')); ?></strong>
                        </td>
                        <td></td>
                    </tr>
                </tbody>

            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-10 text-gray-400">
            Tidak ada data transaksi untuk filter ini.
        </div>
    <?php endif; ?>

    
    <div class="mt-8 pb-4" style="page-break-inside: avoid;">
        <table class="w-full text-center signature-table" style="font-size: 13px; line-height: 1.2;">

            <tr>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Mengetahui,<br>Pengurus Barang</div>
                    <strong><u><?php echo e($opd->pengurus_nama ?? ''); ?></u></strong><br>
                    <div>NIP. <?php echo e($opd->pengurus_nip ?? ''); ?></div>
                </td>
                <td width="50%" align="center">
                    <div style="margin-bottom: 50px;" class="font-semibold">Mengesahkan,<br>Kepala Dinas</div>
                    <strong><u><?php echo e($opd->kepala_nama ?? ''); ?></u></strong><br>
                    <div>NIP. <?php echo e($opd->kepala_nip ?? ''); ?></div>
                </td>
            </tr>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.report_print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/reports/kartu_tahunan.blade.php ENDPATH**/ ?>