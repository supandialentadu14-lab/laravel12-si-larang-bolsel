<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap BTS - Kab. Bolaang Mongondow Selatan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 10px; color: #1a1a2e; line-height: 1.5; }

        .header { text-align: center; border-bottom: 2px solid #1a1a2e; padding-bottom: 10px; margin-bottom: 12px; }
        .header h1 { font-size: 14px; font-weight: bold; color: #1a1a2e; text-transform: uppercase; letter-spacing: 0.5px; }
        .header h2 { font-size: 11px; color: #4a5568; margin-top: 1px; }
        .header h3 { font-size: 10px; font-weight: bold; color: #2d3748; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .header .meta { font-size: 8px; color: #718096; margin-top: 6px; }
        .header .meta strong { color: #2d3748; }
        .header .filters { font-size: 8px; color: #a0aec0; margin-top: 3px; }

        .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .stat-table td {
            text-align: center; padding: 8px 6px; border: 1px solid #cbd5e0;
            width: 25%; vertical-align: top;
        }
        .stat-table .st-label { font-size: 7px; color: #718096; text-transform: uppercase; letter-weight: bold; }
        .stat-table .st-value { font-size: 16px; font-weight: bold; color: #1a202c; }
        .stat-table .st-sub { font-size: 7px; color: #a0aec0; }
        .stat-table .st-green { color: #276749; }
        .stat-table .st-yellow { color: #744210; }
        .stat-table .st-gray { color: #718096; }

        .section-title {
            font-size: 9px; font-weight: bold; color: #fff; text-transform: uppercase;
            letter-spacing: 0.5px; padding: 3px 8px; margin: 10px 0 6px;
        }
        .st-dark { background: #1a1a2e; }
        .st-blue { background: #2b6cb0; }

        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .summary-table th {
            text-align: left; padding: 4px 6px; font-size: 8px; font-weight: bold;
            color: #fff; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .summary-table th.sg1 { background: #2d3748; }
        .summary-table th.sg2 { background: #276749; }
        .summary-table th.sg3 { background: #2b6cb0; }
        .summary-table td { padding: 3px 6px; border: 1px solid #e2e8f0; font-size: 9px; }
        .summary-table tr:nth-child(even) td { background: #f7fafc; }

        .kec-title {
            background: #1a1a2e; color: #fff; padding: 4px 8px; margin-top: 10px; margin-bottom: 0;
            font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .kec-count { float: right; font-size: 8px; color: #a0aec0; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        table.data th {
            text-align: left; padding: 4px 6px; font-size: 8px; font-weight: bold;
            color: #fff; text-transform: uppercase; background: #4a5568; border: 1px solid #cbd5e0;
        }
        table.data td { padding: 4px 6px; border: 1px solid #e2e8f0; font-size: 9px; }
        table.data tbody tr:nth-child(even) td { background: #f7fafc; }
        table.data td.photo-cell { text-align: center; vertical-align: middle; }
        table.data td.photo-cell img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e0; }

        .footer {
            margin-top: 12px; padding-top: 6px; border-top: 1px solid #cbd5e0;
            font-size: 7px; color: #a0aec0; text-align: center;
        }
        .footer strong { color: #4a5568; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data BTS</h1>
        <h2>Kabupaten Bolaang Mongondow Selatan</h2>
        <h3>Dinas Komunikasi dan Informatika</h3>
        <div class="meta">
            Dicetak: <strong><?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WITA</strong>
            | Total: <strong><?php echo e($towers->count()); ?> BTS</strong>
        </div>
        <?php if($filterInfo['kecamatan'] || $filterInfo['provider'] || $filterInfo['status_operasional']): ?>
            <div class="filters">
                Filter:
                <?php if($filterInfo['kecamatan']): ?> Kec. <?php echo e($filterInfo['kecamatan']); ?> <?php endif; ?>
                <?php if($filterInfo['provider']): ?> | <?php echo e($filterInfo['provider']); ?> <?php endif; ?>
                <?php if($filterInfo['status_operasional']): ?> | <?php echo e($filterInfo['status_operasional']); ?> <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
        $total = $towers->count();
        $aktif = $towers->where('status_operasional', 'Aktif')->count();
        $maintenance = $towers->where('status_operasional', 'Maintenance')->count();
        $tidakAktif = $towers->where('status_operasional', 'Tidak Aktif')->count();
    ?>

    <table class="stat-table">
        <tr>
            <td>
                <div class="st-label">Total BTS</div>
                <div class="st-value"><?php echo e($total); ?></div>
                <div class="st-sub">seluruh kabupaten</div>
            </td>
            <td>
                <div class="st-label">Aktif</div>
                <div class="st-value st-green"><?php echo e($aktif); ?></div>
                <div class="st-sub"><?php echo e($total > 0 ? round(($aktif/$total)*100) : 0); ?>%</div>
            </td>
            <td>
                <div class="st-label">Maintenance</div>
                <div class="st-value st-yellow"><?php echo e($maintenance); ?></div>
                <div class="st-sub"><?php echo e($total > 0 ? round(($maintenance/$total)*100) : 0); ?>%</div>
            </td>
            <td>
                <div class="st-label">Tidak Aktif</div>
                <div class="st-value st-gray"><?php echo e($tidakAktif); ?></div>
                <div class="st-sub"><?php echo e($total > 0 ? round(($tidakAktif/$total)*100) : 0); ?>%</div>
            </td>
        </tr>
    </table>

    <?php if($mapImage): ?>
        <div class="section-title st-blue">Peta Sebaran BTS</div>
        <div style="text-align:center;margin-bottom:10px;">
            <img src="<?php echo e($mapImage); ?>" style="width:100%;max-width:700px;border:1px solid #cbd5e0;border-radius:4px;" alt="Peta Sebaran BTS">
        </div>
    <?php endif; ?>

    <div class="section-title st-dark">Rekapitulasi</div>

    <table class="summary-table">
        <thead>
            <tr>
                <th class="sg1" width="33%">Status Operasional</th>
                <th class="sg2" width="33%">Kondisi Fisik</th>
                <th class="sg3" width="34%">Provider</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sKeys = array_values($rekapStatus->keys()->toArray());
                $sVals = array_values($rekapStatus->values()->toArray());
                $kKeys = array_values($rekapKondisi->keys()->toArray());
                $kVals = array_values($rekapKondisi->values()->toArray());
                $pKeys = array_values($rekapProvider->keys()->toArray());
                $pVals = array_values($rekapProvider->values()->toArray());
                $maxRows = max(count($sKeys), count($kKeys), count($pKeys));
            ?>
            <?php for($i = 0; $i < $maxRows; $i++): ?>
                <tr>
                    <td><?php echo e($sKeys[$i] ?? ''); ?></td>
                    <td><?php echo e($kKeys[$i] ?? ''); ?></td>
                    <td><?php echo e($pKeys[$i] ?? ''); ?></td>
                </tr>
                <tr>
                    <td style="text-align:right;font-weight:bold;">
                        <?php if(isset($sVals[$i])): ?><?php echo e($sVals[$i]); ?> <span style="font-size:7px;color:#718096;">(<?php echo e($total > 0 ? round(($sVals[$i]/$total)*100) : 0); ?>%)</span><?php endif; ?>
                    </td>
                    <td style="text-align:right;font-weight:bold;">
                        <?php if(isset($kVals[$i])): ?><?php echo e($kVals[$i]); ?> <span style="font-size:7px;color:#718096;">(<?php echo e($total > 0 ? round(($kVals[$i]/$total)*100) : 0); ?>%)</span><?php endif; ?>
                    </td>
                    <td style="text-align:right;font-weight:bold;">
                        <?php if(isset($pVals[$i])): ?><?php echo e($pVals[$i]); ?> <span style="font-size:7px;color:#718096;">(<?php echo e($total > 0 ? round(($pVals[$i]/$total)*100) : 0); ?>%)</span><?php endif; ?>
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <?php $runningNo = 0; ?>
    <?php $__empty_1 = true; $__currentLoopData = $towersByKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="kec-title">Kecamatan <?php echo e($kecamatan); ?> <span class="kec-count"><?php echo e($items->count()); ?> BTS</span></div>
        <table class="data">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="12%">Desa</th>
                    <th width="24%">Titik Koordinat</th>
                    <th width="10%">Provider</th>
                    <th width="25%">Nama Perusahaan</th>
                    <th width="10%">Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $runningNo++; ?>
                    <tr>
                        <td style="text-align:center;"><?php echo e($runningNo); ?></td>
                        <td><?php echo e($t->desa ?: '-'); ?></td>
                        <td><?php echo e($t->latitude); ?>, <?php echo e($t->longitude); ?></td>
                        <td><?php echo e($t->provider ?: '-'); ?></td>
                        <td><?php echo e($t->nama_perusahaan ?? '-'); ?></td>
                        <td class="photo-cell">
                            <?php if($towerPhotos[$t->id] ?? null): ?>
                                <img src="<?php echo e($towerPhotos[$t->id]); ?>" alt="Foto BTS">
                            <?php else: ?>
                                <span style="color:#a0aec0;font-size:7px;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p style="text-align:center; margin-top:20px; color:#a0aec0;">Belum ada data BTS.</p>
    <?php endif; ?>

    <div class="footer">
        Data Bidang Komunikasi dan Persandian Dinas Komunikasi dan Informatika Kabupaten Bolaang Mongondow Selatan
    </div>

</body>
</html>
<?php /**PATH D:\SI-LARANG\resources\views/bts-towers/pdf-all.blade.php ENDPATH**/ ?>