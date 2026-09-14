<?php $__env->startSection('content'); ?>
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah Jenis Belanja</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Input Kategori Inventaris</p>
    </div>
    <a href="<?php echo e(route('categories.index')); ?>" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
    <form action="<?php echo e(route('categories.store')); ?>" method="POST" class="space-y-5">
      <?php echo csrf_field(); ?>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Kategori</label>
        <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
          oninvalid="this.setCustomValidity('Kolom Nama Kategori harus diisi')" 
          oninput="this.setCustomValidity('')"
          class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
        <textarea name="description" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none"><?php echo e(old('description')); ?></textarea>
        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="grid grid-cols-2 gap-3 pt-2">
        <a href="<?php echo e(route('categories.index')); ?>" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/categories/create.blade.php ENDPATH**/ ?>