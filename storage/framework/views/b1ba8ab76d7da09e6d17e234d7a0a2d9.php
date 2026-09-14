<?php $__env->startSection('content'); ?>
<div x-data="{
  showFilters: <?php echo e((request('search') || request('date')) ? 'true' : 'false'); ?>

}" class="space-y-6">

  
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Log Aktivitas</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Audit Trail Sistem</p>
    </div>
    <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50' : ''">
      <i class="fas fa-filter text-xs"></i>
    </button>
  </div>

  
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Filter Log</h3>
      <form action="<?php echo e(route('activity_log.index')); ?>" method="GET" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Cari Kata Kunci</label>
          <div class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari aktivitas..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>

        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Filter Tanggal</label>
          <div class="relative">
            <i class="fas fa-calendar-day absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Terapkan</button>
          <a href="<?php echo e(route('activity_log.index')); ?>" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Reset</a>
        </div>
      </form>
    </div>
  </div>

  
  <div class="bg-slate-800 rounded-[2.5rem] p-6 text-white shadow-xl shadow-slate-100 overflow-hidden relative group">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10 text-center py-2">
      <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-history text-lg opacity-60"></i>
      </div>
      <h2 class="text-3xl font-black tracking-tight"><?php echo e($logs->count()); ?> Aktivitas</h2>
      <p class="text-[9px] font-bold mt-1 opacity-60 uppercase tracking-widest">
        <?php if($datePaginator->count() > 0): ?>
          Pada <?php echo e(\Carbon\Carbon::parse($datePaginator->items()[0]->log_date)->translatedFormat('d M Y')); ?>

        <?php else: ?>
          Data Kosong
        <?php endif; ?>
      </p>
    </div>
  </div>

  
  <div class="space-y-6 pb-24 relative before:absolute before:left-8 before:top-2 before:bottom-24 before:w-0.5 before:bg-slate-100">
    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="relative pl-14">
        
        <div class="absolute left-6 top-0 -translate-x-1/2 w-4 h-4 rounded-full border-4 border-white shadow-sm z-10
          <?php echo e($log->action === 'CREATED' ? 'bg-emerald-400' : ''); ?>

          <?php echo e($log->action === 'UPDATED' ? 'bg-blue-400' : ''); ?>

          <?php echo e($log->action === 'DELETED' ? 'bg-rose-400' : ''); ?>">
        </div>

        <div class="bg-white rounded-[2rem] p-5 border border-slate-50 shadow-sm">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <span class="text-[8px] font-black px-2 py-0.5 rounded-lg uppercase tracking-widest
                <?php echo e($log->action === 'CREATED' ? 'bg-emerald-50 text-emerald-600' : ''); ?>

                <?php echo e($log->action === 'UPDATED' ? 'bg-blue-50 text-blue-600' : ''); ?>

                <?php echo e($log->action === 'DELETED' ? 'bg-rose-50 text-rose-600' : ''); ?>">
                <?php echo e($log->action); ?>

              </span>
              <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest"><?php echo e($log->created_at->translatedFormat('H:i:s')); ?></span>
            </div>
            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($log->created_at->translatedFormat('d M Y')); ?></span>
          </div>

          <div class="space-y-1">
            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-tight leading-tight"><?php echo e($log->description); ?></h4>
            <div class="flex items-center gap-1.5 mt-1">
              <div class="w-4 h-4 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fas fa-user text-[6px] text-slate-400"></i>
              </div>
              <span class="text-[9px] font-bold text-slate-500 uppercase"><?php echo e($log->user->name ?? 'SYSTEM'); ?></span>
            </div>
          </div>

          <?php if($log->formatted_properties): ?>
            <div class="mt-6 bg-slate-50/50 rounded-2xl p-4 space-y-3 border border-slate-100">
              <?php $__currentLoopData = $log->formatted_properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="space-y-1">
                  <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($change['label']); ?></span>
                  <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-rose-500 line-through opacity-60 truncate max-w-[120px]"><?php echo e($change['old']); ?></span>
                    <i class="fas fa-arrow-right text-[8px] text-slate-300 shrink-0"></i>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg truncate max-w-[120px]"><?php echo e($change['new']); ?></span>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          <?php endif; ?>

          <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-1">
              <i class="fas fa-network-wired text-[8px] text-slate-300"></i>
              <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($log->ip_address); ?></span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-history text-3xl text-slate-200"></i>
        </div>
        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Belum Ada Log</h3>
      </div>
    <?php endif; ?>

    <div class="pt-4 px-4 pb-12">
      <?php echo e($datePaginator->links()); ?>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/activity_log/index.blade.php ENDPATH**/ ?>