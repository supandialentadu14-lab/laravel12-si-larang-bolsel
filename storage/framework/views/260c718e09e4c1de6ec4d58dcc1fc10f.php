<?php $__env->startSection('default_orientation', 'portrait'); ?>
<?php $__env->startSection('report_class', 'portrait'); ?>


<?php $__env->startSection('title', 'Kwitansi'); ?>
<?php $__env->startSection('back_url', route('reports.kwitansi.list')); ?>

<?php $__env->startSection('extra_buttons'); ?>
    <?php if(session('kwitansi_current_id')): ?>
        <a href="<?php echo e(route('reports.paket.show', session('kwitansi_current_id'))); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition font-bold">
            <i class="fas fa-layer-group"></i> Paket 4 Dokumen
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .doc-kwitansi p { margin: 5px 0; font-size: 14px; color: black; }
    .doc-kwitansi h2 { margin: 5px 0; font-size: 18px; font-weight: bold; color: black; }
    
    @media print {
        @page { size: portrait; margin: 15mm; }
        .doc-kwitansi p { margin: 2px 0 !important; line-height: 1.2 !important; }
        .signature-block { break-inside: avoid !important; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('report_content'); ?>
<div class="doc-kwitansi">
    <?php echo $__env->make('reports.partials.docs.kwitansi', ['data' => $data, 'opd' => $opd], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.report_print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/reports/kwitansi_report.blade.php ENDPATH**/ ?>