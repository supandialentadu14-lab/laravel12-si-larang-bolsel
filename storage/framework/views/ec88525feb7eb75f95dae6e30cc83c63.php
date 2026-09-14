<?php $__env->startSection('content'); ?>
<style>
    .bts-form-page { color: #e5e7eb; font-family: 'Inter', system-ui, sans-serif; }
    .bts-form-page .page-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        flex-wrap: wrap; gap: 12px; margin-bottom: 1.25rem;
    }
    .bts-form-page .page-title { font-size: 1.25rem; font-weight: 900; color: #f9fafb; margin: 0; }
    .bts-form-page .page-sub { font-size: 10px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.15em; margin-top: 2px; }
    .bts-form-page .btn-act {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px;
        border-radius: 10px; font-size: 11px; font-weight: 700; text-decoration: none;
        border: 1px solid transparent; cursor: pointer; transition: all 0.2s;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .bts-form-page .btn-act:hover { filter: brightness(1.15); transform: translateY(-1px); }
    .bts-form-page .btn-indigo { background: #4f46e5; color: #fff; }
    .bts-form-page .btn-ghost { background: rgba(255,255,255,0.05); color: #9ca3af; border-color: rgba(255,255,255,0.08); }
    .bts-form-page .alert-error {
        background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);
        color: #f87171; border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; font-size: 12px; font-weight: 600;
    }
    .bts-form-page .alert-error ul { margin: 0; padding-left: 18px; }
</style>

<div class="container-fluid py-4 bts-form-page">
    <div class="page-header">
        <div>
            <h4 class="page-title"><i class="fas fa-plus-circle" style="color:#818cf8;margin-right:8px;"></i> Tambah Data BTS</h4>
            <p class="page-sub">Masukkan data BTS baru ke dalam sistem</p>
        </div>
        <a href="<?php echo e(route('bts-towers.index')); ?>" class="btn-act btn-ghost">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert-error">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('bts-towers.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('bts-towers._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div style="display:flex;gap:10px;margin-top:1.25rem;">
            <button type="submit" class="btn-act btn-indigo" style="padding:11px 28px;">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo e(route('bts-towers.index')); ?>" class="btn-act btn-ghost" style="padding:11px 28px;">
                Batal
            </a>
        </div>
    </form>
</div>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<?php echo $__env->make('bts-towers._map-picker-script', ['lat' => old('latitude'), 'lng' => old('longitude')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/bts-towers/create.blade.php ENDPATH**/ ?>