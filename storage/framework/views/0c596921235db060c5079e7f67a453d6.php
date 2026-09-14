<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{
  formatNosur(el, dateStr) {
    if (!el || !dateStr) return;
    let val = (el.value || '').trim();
    if (/^\\d+$/.test(val) && !val.includes('/')) {
      const romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
      const dateVal = new Date(dateStr);
      if (!isNaN(dateVal.getTime())) {
        const month = dateVal.getMonth() + 1;
        const year = dateVal.getFullYear();
        el.value = `${val}/BAPB/<?php echo e($singkatanOpd ?? 'DISKOMINFO'); ?>/${romans[month]}/${year}`;
        el.dispatchEvent(new Event('input'));
      }
    }
  }
}">
  <div class="flex items-center justify-between px-2">
    <div>
      <h1 class="text-2xl font-black text-app-main tracking-tight uppercase">Edit Transaksi</h1>
      <p class="text-[10px] font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Mutasi Masuk & Keluar</p>
    </div>
    <a href="<?php echo e(route('stock.index')); ?>" class="btn-icon-mini bg-app-surface border border-app-main shadow-sm flex items-center justify-center text-app-muted">
      <i class="fas fa-arrow-left text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
    <form action="<?php echo e(route('stock.update', $transaction->id)); ?>" method="POST" class="space-y-5" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>

      <?php $isAutomatic = ($transaction->notes === 'Otomatis dari Kwitansi'); ?>
      
      <?php if($isAutomatic): ?>
        <div class="mb-4 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-3">
          <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
          <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-widest">
                Transaksi ini tidak dapat diubah karena dibuat otomatis dari Kwitansi. Silahkan ubah Nota Pesanan, Berita Acara Serah Terima Barang, atau Kwitansi untuk mengubah transaksi ini.
              </p>
            </div>
          <?php endif; ?>

          <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Barang</label>
            <div class="relative">
              <select name="product_id" class="mobile-input appearance-none bg-app-surface <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" required <?php if($isAutomatic): ?> readonly style="pointer-events: none;" <?php endif; ?>>
                <option value="">Pilih Barang</option>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($product->id); ?>" <?php echo e((string)old('product_id', $transaction->product_id) === (string)$product->id ? 'selected' : ''); ?>><?php echo e($product->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
              <?php if($isAutomatic): ?> <input type="hidden" name="product_id" value="<?php echo e($transaction->product_id); ?>"> <?php endif; ?>
            </div>
            <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Jenis</label>
              <div class="relative">
                <select name="type" class="mobile-input appearance-none bg-app-surface <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" required <?php if($isAutomatic): ?> readonly style="pointer-events: none;" <?php endif; ?>>
                  <option value="in" <?php echo e(old('type', $transaction->type) === 'in' ? 'selected' : ''); ?>>Masuk</option>
                  <option value="saldo" <?php echo e(old('type', $transaction->type) === 'saldo' ? 'selected' : ''); ?>>Saldo Awal</option>
                  <option value="out" <?php echo e(old('type', $transaction->type) === 'out' ? 'selected' : ''); ?>>Keluar</option>
                </select>
                <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
                <?php if($isAutomatic): ?> <input type="hidden" name="type" value="<?php echo e($transaction->type); ?>"> <?php endif; ?>
              </div>
              <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Jumlah</label>
              <input type="number" name="quantity" inputmode="numeric" min="1" value="<?php echo e(old('quantity', $transaction->quantity)); ?>" class="mobile-input bg-app-surface <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" required <?php if($isAutomatic): ?> readonly <?php endif; ?>>
              <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Tanggal</label>
              <input type="date" name="date" value="<?php echo e(old('date', $transaction->date ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d'))); ?>" class="mobile-input bg-app-surface <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" required <?php if($isAutomatic): ?> readonly <?php endif; ?>>
              <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="space-y-1.5">
              <div class="flex items-center justify-between ml-4">
                <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest">No. Surat <?php echo e($isAutomatic ? '' : '(Opsional)'); ?></label>
                <?php if($transaction->nosur): ?>
                  <a href="<?php echo e(route('reports.penerimaan.list', ['search' => $transaction->nosur])); ?>" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors flex items-center gap-1.5">
                    <i class="fas fa-external-link-alt text-[7px]"></i>
                    BASTB
                  </a>
                <?php endif; ?>
              </div>
              <input type="text" name="nosur" value="<?php echo e(old('nosur', $transaction->nosur)); ?>" class="mobile-input bg-app-surface font-mono tracking-tight <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" <?php if($isAutomatic): ?> readonly <?php endif; ?>>
              <?php $__errorArgs = ['nosur'];
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
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
            <textarea name="notes" rows="3" class="mobile-input bg-app-surface leading-relaxed <?php echo e($isAutomatic ? 'opacity-60 cursor-not-allowed' : ''); ?>" <?php if($isAutomatic): ?> readonly <?php endif; ?>><?php echo e(old('notes', $transaction->notes)); ?></textarea>
            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2">
            <a href="<?php echo e(route('stock.index')); ?>" class="btn-ghost-mobile">Batal</a>
            <button type="submit" class="btn-primary-mobile">Simpan</button>
          </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/stock/edit.blade.php ENDPATH**/ ?>