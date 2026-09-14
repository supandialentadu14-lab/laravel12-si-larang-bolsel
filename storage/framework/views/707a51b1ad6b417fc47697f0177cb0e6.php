<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{
  selectedProductId: '<?php echo e(old('product_id', '')); ?>',
  type: '<?php echo e(old('type', 'in')); ?>',
  quantity: <?php echo e(old('quantity', 0)); ?>,
  products: <?php echo \Illuminate\Support\Js::from($products)->toHtml() ?>,
  
  get currentProduct() {
    return this.products.find(p => p.id == this.selectedProductId);
  },
  
  get availableStock() {
    return this.currentProduct ? this.currentProduct.stock : 0;
  },
  
  get isInvalid() {
    if (this.type === 'out' && this.selectedProductId) {
      return this.quantity > this.availableStock;
    }
    return false;
  },

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
      <h1 class="text-2xl font-black text-app-main transition-colors uppercase tracking-tight">Transaksi Baru</h1>
      <p class="text-[10px] font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Mutasi Masuk, Keluar & Saldo Awal</p>
    </div>
    <a href="<?php echo e(route('stock.index')); ?>" class="btn-icon-mini bg-app-surface text-app-muted border border-app-main shadow-sm flex items-center justify-center transition-colors">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm transition-colors">
    <form action="<?php echo e(route('stock.store')); ?>" method="POST" class="space-y-5" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
      <?php echo csrf_field(); ?>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Barang</label>
        <div class="relative">
          <select name="product_id" x-model="selectedProductId" class="mobile-input appearance-none bg-app-surface" required>
            <option value="">Pilih Barang</option>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?> (Stok: <?php echo e($product->stock); ?> <?php echo e($product->unit); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
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

      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Jenis</label>
          <div class="relative">
            <select name="type" x-model="type" class="mobile-input appearance-none bg-app-surface" required>
              <option value="in">Masuk</option>
              <option value="saldo">Saldo Awal</option>
              <option value="out">Keluar</option>
            </select>
            <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
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
          <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Jumlah</label>
          <input type="number" name="quantity" x-model.number="quantity" inputmode="numeric" min="1" 
            class="mobile-input" 
            :class="isInvalid ? 'border-rose-500 bg-rose-50 dark:bg-rose-500/10 text-rose-600' : 'bg-app-surface'" required>
          <template x-if="isInvalid">
            <p class="text-[9px] font-black text-rose-600 mt-1 ml-4 uppercase tracking-tighter">Stok tidak mencukupi (Tersedia: <span x-text="availableStock"></span>)</p>
          </template>
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

      <div class="space-y-4">
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Tanggal Transaksi</label>
          <div class="relative">
            <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-app-muted text-xs pointer-events-none"></i>
            <input type="date" name="date" value="<?php echo e(old('date', now()->format('Y-m-d'))); ?>" class="mobile-input pl-12 bg-app-surface" required>
          </div>
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
          <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Nomor Surat / Referensi (Opsional)</label>
          <div class="relative">
            <i class="fas fa-file-invoice absolute left-5 top-1/2 -translate-y-1/2 text-app-muted text-xs pointer-events-none"></i>
            <input type="text" name="nosur" value="<?php echo e(old('nosur')); ?>" placeholder="Contoh: 001/BAPB/..." class="mobile-input pl-12 font-mono bg-app-surface">
          </div>
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
        <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4 transition-colors">Keterangan (Opsional)</label>
        <textarea name="notes" rows="3" class="mobile-input bg-app-surface leading-relaxed"><?php echo e(old('notes')); ?></textarea>
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
        <button type="submit" :disabled="isInvalid" :class="isInvalid ? 'opacity-50 grayscale' : ''" class="btn-primary-mobile">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/stock/create.blade.php ENDPATH**/ ?>