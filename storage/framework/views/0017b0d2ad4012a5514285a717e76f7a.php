<?php $__env->startSection('content'); ?>
<div class="space-y-6">
  <div class="flex items-center justify-between px-2">
    <div>
      <h1 class="text-2xl font-black text-slate-800 transition-colors uppercase tracking-tight">Tambah Barang</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Input Data Barang Baru</p>
    </div>
    <a href="<?php echo e(route('products.index')); ?>" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-colors">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm transition-colors">
    <form action="<?php echo e(route('products.store')); ?>" method="POST" class="space-y-5">
      <?php echo csrf_field(); ?>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Nama Barang</label>
        <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
          oninvalid="this.setCustomValidity('Kolom Nama Barang harus diisi')" 
          oninput="this.setCustomValidity('')"
          class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors" required>
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
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Stok Minimum</label>
        <input type="number" name="min_stock" min="0" value="<?php echo e(old('min_stock')); ?>" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">
        <?php $__errorArgs = ['min_stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Harga Satuan</label>
          <input type="number" name="price" step="0.01" value="<?php echo e(old('price')); ?>" 
            oninvalid="this.setCustomValidity('Kolom Harga Satuan harus diisi')" 
            oninput="this.setCustomValidity('')"
            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors" required>
          <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Satuan</label>
          <select name="unit" 
            oninvalid="this.setCustomValidity('Kolom Satuan harus dipilih')" 
            oninput="this.setCustomValidity('')"
            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none transition-colors" required>
            <option value="">Pilih Satuan</option>
            <?php $__currentLoopData = ['pcs','bks','lbr','botol','buah','box','pak','rim','kg','galon','paket','liter']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($u); ?>" <?php echo e(old('unit') === $u ? 'selected' : ''); ?>><?php echo e(strtoupper($u)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Jenis Belanja</label>
        <select name="category_id" 
          oninvalid="this.setCustomValidity('Kolom Jenis Belanja harus dipilih')" 
          oninput="this.setCustomValidity('')"
          class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none transition-colors" required>
          <option value="">Pilih Jenis Belanja</option>
          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cat->id); ?>" <?php echo e((string)old('category_id') === (string)$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>


      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Keterangan (Opsional)</label>
        <textarea name="description" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors"><?php echo e(old('description')); ?></textarea>
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
        <a href="<?php echo e(route('products.index')); ?>" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-colors">Batal</a>
        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100 active:scale-[0.98] transition-all">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/products/create.blade.php ENDPATH**/ ?>