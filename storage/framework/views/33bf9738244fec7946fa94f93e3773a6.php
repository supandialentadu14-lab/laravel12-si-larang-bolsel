<?php $__env->startSection('default_orientation', 'portrait'); ?>
<?php $__env->startSection('report_class', 'portrait'); ?>


<?php $__env->startSection('title', 'Nota Pesanan'); ?>
<?php $__env->startSection('back_url', route('reports.nota.list')); ?>

<?php $__env->startSection('extra_buttons'); ?>
    <?php if(session('nota_current_id')): ?>
        <a href="<?php echo e(route('reports.paket.show', session('nota_current_id'))); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_styles'); ?>
<style>
    /* Screen preview: match print margins */
    #nota-paper {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .report-paper {
        padding-top: 8mm !important;
        padding-bottom: 8mm !important;
    }

    @media print {
        @page { size: 215mm 330mm portrait; margin: 8mm 15mm; }
        .doc-nota p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .doc-nota table td, .doc-nota table th { padding: 3px 6px !important; }
        .signature-section {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            break-before: avoid !important;
            page-break-before: avoid !important;
        }
        .signature-block { break-inside: avoid !important; page-break-inside: avoid !important; }
        #paper-scale { transform: none !important; }
    }
</style>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('report_content'); ?>
<div class="doc-nota" id="nota-paper">
    <?php echo $__env->make('reports.partials.docs.nota_pesanan', ['data' => $data, 'opd' => $opd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Signatures are handled purely via CSS break-inside: avoid
    // No JS margin manipulation needed — it caused signatures to be pushed off-screen
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.report_print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/reports/nota_pesanan_report.blade.php ENDPATH**/ ?>