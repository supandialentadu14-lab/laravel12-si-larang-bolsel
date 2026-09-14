<?php $__env->startSection('content'); ?>
  <div class="space-y-6 pb-24">
    
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-app-main uppercase tracking-tight">Pemeriksaan</h1>
        <p class="text-[10px] font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Buat BAP Pemeriksaan Baru</p>
      </div>
      <a href="<?php echo e(route('reports.pemeriksaan.list')); ?>" class="w-10 h-10 rounded-2xl bg-app-surface border border-app-main shadow-sm flex items-center justify-center text-app-muted">
        <i class="fas fa-times text-xs"></i>
      </a>
    </div>

    <form action="<?php echo e(route('reports.pemeriksaan.save')); ?>" method="POST" class="space-y-6">
      <?php echo csrf_field(); ?>
      
      
      <div class="bg-app-surface rounded-[2.5rem] p-6 border border-app-main shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-app-main pb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-link text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-app-main uppercase tracking-widest">Hubungkan Dokumen</h3>
        </div>
 
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Nota Pesanan Referensi</label>
            <select name="nota_nomor" 
              oninvalid="this.setCustomValidity('Nota Pesanan harus dipilih')" 
              oninput="this.setCustomValidity('')"
              class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
              <option value="">-- Pilih Nota Pesanan --</option>
              <?php $__currentLoopData = $notaDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($n['nomor']); ?>" <?php echo e(($data['nota_nomor'] ?? '') === ($n['nomor'] ?? '') ? 'selected' : ''); ?>>
                  <?php echo e($n['nomor']); ?> • <?php echo e(\Carbon\Carbon::parse($n['tanggal'] ?? now())->translatedFormat('d F Y')); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['nota_nomor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="flex items-start gap-2 mt-3 px-2">
              <i class="fas fa-info-circle text-indigo-400 text-[10px] mt-0.5"></i>
              <p class="text-[10px] text-app-muted font-bold italic leading-relaxed uppercase tracking-tighter">
                Data barang dan penyedia akan ditarik otomatis dari Nota Pesanan yang dipilih.
              </p>
            </div>
          </div>
        </div>
      </div>

      
      <div class="bg-app-surface rounded-[2.5rem] p-6 border border-app-main shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-app-main pb-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <i class="fas fa-file-signature text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-app-main uppercase tracking-widest">Detail Pemeriksaan</h3>
        </div>
 
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Nomor BAP (Angka)</label>
            <input type="text" name="nomor" value="<?php echo e(old('nomor', preg_replace('/\D+/', '', $data['nomor'] ?? ''))); ?>" inputmode="numeric" pattern="[0-9]*" 
              oninvalid="this.setCustomValidity('Nomor BAP harus diisi')" 
              oninput="this.value=this.value.replace(/\D/g,''); this.setCustomValidity('');"
              class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-mono font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="001" required>
            <?php $__errorArgs = ['nomor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Tanggal</label>
              <input type="date" name="tanggal" value="<?php echo e(old('tanggal', $data['tanggal'] ?? now()->toDateString())); ?>" 
                oninvalid="this.setCustomValidity('Tanggal harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
              <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-app-muted uppercase tracking-widest ml-4">Tempat</label>
              <input type="text" name="tempat" value="<?php echo e(old('tempat', $data['tempat'] ?? 'Bolaang Uki')); ?>" 
                oninvalid="this.setCustomValidity('Tempat harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full px-6 py-4 bg-app-bg border-none rounded-2xl text-xs font-bold text-app-main focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Contoh: Boroko" required>
              <?php $__errorArgs = ['tempat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-[10px] font-bold text-rose-600 mt-1 ml-4"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>
        </div>
      </div>

      
      <div class="flex gap-3 px-2">
        <a href="<?php echo e(route('reports.pemeriksaan.list')); ?>" class="flex-1 py-5 bg-app-surface text-app-muted border border-app-main rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
        <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Simpan BAP</button>
      </div>
    </form>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($isMobile ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/pemeriksaan/create.blade.php ENDPATH**/ ?>