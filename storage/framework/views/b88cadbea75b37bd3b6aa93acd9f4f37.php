<?php $__env->startSection('title', 'Paket 4 Dokumen'); ?>
<?php $__env->startSection('back_url', route('reports.nota.list')); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Styling khusus paket dokumen */
    .bundle-sheet { 
        break-after: page; 
        page-break-after: always; 
        margin-bottom: 30px;
        position: relative;
    }
    .bundle-sheet:last-child { 
        break-after: auto; 
        page-break-after: auto; 
        margin-bottom: 0;
    }

    /* Override layout paper width to match bundle sheets */
    .report-paper { 
        max-width: none !important; 
        width: 100% !important; 
        background: transparent !important; 
        box-shadow: none !important; 
        padding: 0 !important;
    }

    .bundle-content {
        background: white;
        width: 215mm;
        min-height: 330mm;
        margin: 0 auto 30px auto;
        padding: 10mm 15mm;
        box-sizing: border-box;
    }

    @media print {
        .bundle-content { 
            margin: 0 !important; 
            padding: 10mm 15mm !important; 
            box-shadow: none !important;
            width: 100% !important;
        }
        @page { size: 215mm 330mm portrait; margin: 0; }
        .no-print { display: none !important; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report_content'); ?>
<div id="bundle-container">
    
    <div class="bundle-sheet doc-nota">
        <div class="bundle-content">
            <?php echo $__env->make('reports.partials.docs.nota_pesanan', ['data' => $nota, 'opd' => $opd, 'is_first' => true, 'is_last' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    <div class="bundle-sheet doc-pemeriksaan">
        <div class="bundle-content">
            <?php if($pemeriksaan): ?>
                <?php echo $__env->make('reports.partials.docs.pemeriksaan', ['data' => $pemeriksaan, 'opd' => $opd, 'is_first' => true, 'is_last' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-file-invoice text-4xl mb-4"></i>
                    <p class="font-bold">BAP Pemeriksaan Belum Dibuat</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bundle-sheet doc-penerimaan">
        <div class="bundle-content">
            <?php if($penerimaan): ?>
                <?php echo $__env->make('reports.partials.docs.penerimaan', ['data' => $penerimaan, 'opd' => $opd, 'is_first' => true, 'is_last' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-file-import text-4xl mb-4"></i>
                    <p class="font-bold">BAP Penerimaan Belum Dibuat</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bundle-sheet doc-kwitansi">
        <div class="bundle-content">
            <?php if($kwitansi): ?>
                <?php echo $__env->make('reports.partials.docs.kwitansi', ['data' => $kwitansi, 'opd' => $opd, 'is_first' => true, 'is_last' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center p-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400">
                    <i class="fas fa-receipt text-4xl mb-4"></i>
                    <p class="font-bold">Kwitansi Belum Dibuat</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Logic for signature breaking if needed in multiple sheets
    document.fonts.ready.then(function () {
        var bundle = document.getElementById('bundle-container');
        if (!bundle) return;
        var pageH = 1247; // F4: 330mm at 96dpi
        var sigs = bundle.querySelectorAll('.signature-section');
        sigs.forEach(function(el) {
            // Logic is a bit complex for multi-page bundle, 
            // but the layouts usually handle it via break-inside: avoid
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.report_print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/reports/dokumen_paket_report.blade.php ENDPATH**/ ?>