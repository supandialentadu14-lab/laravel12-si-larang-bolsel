<?php $__env->startSection('header', 'Agenda Surat'); ?>
<?php $__env->startSection('subheader', 'Daftar seluruh surat & dokumen yang telah dibuat'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{
  showFilters: <?php echo e((request('search') || request('type')) ? 'true' : 'false'); ?>,
}" class="space-y-6">

  
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Register Dokumen</h2>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Seluruh surat terurut berdasarkan tanggal & nomor</p>
    </div>
    <button @click="showFilters = !showFilters"
            class="w-10 h-10 rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-center text-slate-400 transition-all"
            :class="showFilters ? 'text-indigo-600 border-indigo-200 ring-4 ring-indigo-50' : ''">
      <i class="fas fa-sliders-h text-xs"></i>
    </button>
  </div>

  
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Filter & Pencarian</h3>
      <form action="<?php echo e(route('agenda.surat.index')); ?>" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor / Uraian</label>
            <div class="relative">
              <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
              <input type="text" name="search" value="<?php echo e($search); ?>"
                     placeholder="Cari nomor atau uraian surat..."
                     class="w-full pl-12 pr-6 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none dark:text-white dark:placeholder-slate-500">
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Jenis Surat</label>
            <select name="type"
                    class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none dark:text-white">
              <option value="">Semua Jenis</option>
              <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>" <?php echo e($filter === $key ? 'selected' : ''); ?>><?php echo e($src['label']); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit"
                  class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">
            Terapkan
          </button>
          <a href="<?php echo e(route('agenda.surat.index')); ?>"
             class="w-full py-4 bg-slate-50 dark:bg-slate-700 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    
    <div class="col-span-2 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-200 overflow-hidden relative group">
      <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-60">Total Dokumen</p>
          <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
            <i class="fas fa-book-open text-xs opacity-70"></i>
          </div>
        </div>
        <h2 class="text-4xl font-black tracking-tight"><?php echo e(number_format($totalDokumen)); ?></h2>
        <p class="text-[9px] font-bold mt-2 opacity-70 uppercase tracking-widest">Surat Tercatat</p>
      </div>
    </div>

    
    <div class="col-span-2 bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden relative group">
      <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-50 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-3">
          <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Nominal</p>
          <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
            <i class="fas fa-coins text-xs text-emerald-500"></i>
          </div>
        </div>
        <h2 class="text-2xl font-black tracking-tight text-slate-800 dark:text-white">
          Rp<?php echo e(number_format($totalNominal, 0, ',', '.')); ?>

        </h2>
        <p class="text-[9px] font-bold mt-2 text-slate-400 uppercase tracking-widest">Dari semua dokumen</p>
      </div>
    </div>

    
    <?php
      $typeColors = [
        'nota'        => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'icon_bg' => 'bg-blue-100'],
        'pemeriksaan' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'icon_bg' => 'bg-amber-100'],
        'penerimaan'  => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'icon_bg' => 'bg-indigo-100'],
        'kwitansi'    => ['bg' => 'bg-emerald-50','text' => 'text-emerald-600','icon_bg' => 'bg-emerald-100'],
      ];
    ?>
    <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $c = $typeColors[$key];
        $count = $items->getCollection()->where('type', $key)->count();
      ?>
      <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="w-10 h-10 rounded-xl <?php echo e($c['icon_bg']); ?> flex items-center justify-center mb-3">
          <i class="fas <?php echo e($src['icon']); ?> text-sm <?php echo e($c['text']); ?>"></i>
        </div>
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?php echo e($src['label']); ?></p>
        <p class="text-2xl font-black <?php echo e($c['text']); ?> mt-1"><?php echo e(number_format($items->total() > 0 ? $count : 0)); ?></p>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
    
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-50 dark:border-slate-700">
      <div>
        <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Daftar Agenda</h3>
        <p class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">
          Menampilkan <?php echo e($items->count()); ?> dari <?php echo e($items->total()); ?> surat
          <?php if($filter && isset($sources[$filter])): ?>
            — <span class="text-indigo-500"><?php echo e($sources[$filter]['label']); ?></span>
          <?php endif; ?>
        </p>
      </div>
      <?php if($search || $filter): ?>
        <a href="<?php echo e(route('agenda.surat.index')); ?>"
           class="text-[9px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">
          <i class="fas fa-times mr-1"></i>Hapus Filter
        </a>
      <?php endif; ?>
    </div>

    
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-slate-50 dark:border-slate-700">
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">No</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Jenis</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nomor Surat</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Uraian</th>
            <th class="px-6 py-3 text-left text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Tanggal</th>
            <th class="px-6 py-3 text-right text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Nominal</th>
            <th class="px-6 py-3 text-center text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
          <?php $noUrut = ($items->currentPage() - 1) * $items->perPage() + 1; ?>
          <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $c = $typeColors[$row['type']] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'icon_bg' => 'bg-slate-100']; ?>
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition-colors group">
              
              <td class="px-6 py-4">
                <span class="text-[10px] font-black text-slate-300"><?php echo e($noUrut++); ?></span>
              </td>
              
              <td class="px-6 py-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl <?php echo e($c['icon_bg']); ?> <?php echo e($c['text']); ?> text-[9px] font-black uppercase tracking-wide whitespace-nowrap">
                  <i class="fas <?php echo e($row['icon']); ?> text-[9px]"></i>
                  <?php echo e($row['label']); ?>

                </span>
              </td>
              
              <td class="px-6 py-4">
                <a href="<?php echo e($row['route_show']); ?>"
                   class="text-[11px] font-black text-slate-800 dark:text-white hover:text-indigo-600 transition-colors block leading-tight">
                  <?php echo e($row['nomor']); ?>

                </a>
              </td>
              
              <td class="px-6 py-4 max-w-xs">
                <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 leading-relaxed truncate" title="<?php echo e($row['uraian']); ?>">
                  <?php echo e($row['uraian'] ?: '-'); ?>

                </p>
              </td>
              
              <td class="px-6 py-4 whitespace-nowrap">
                <?php if($row['tanggal']): ?>
                  <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">
                    <?php echo e(\Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y')); ?>

                  </p>
                  <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">
                    <?php echo e(\Carbon\Carbon::parse($row['tanggal'])->translatedFormat('l')); ?>

                  </p>
                <?php else: ?>
                  <span class="text-slate-300 text-[10px]">-</span>
                <?php endif; ?>
              </td>
              
              <td class="px-6 py-4 text-right whitespace-nowrap">
                <?php if($row['total'] > 0): ?>
                  <span class="text-[11px] font-black <?php echo e($c['text']); ?>">
                    Rp<?php echo e(number_format($row['total'], 0, ',', '.')); ?>

                  </span>
                <?php else: ?>
                  <span class="text-[10px] text-slate-300">-</span>
                <?php endif; ?>
              </td>
              
              <td class="px-6 py-4 text-center">
                <a href="<?php echo e($row['route_show']); ?>"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/30 transition-all text-[9px] font-black uppercase tracking-widest">
                  <i class="fas fa-eye text-[9px]"></i> Lihat
                </a>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="7" class="px-6 py-24 text-center">
                <div class="flex flex-col items-center gap-4">
                  <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-700 flex items-center justify-center">
                    <i class="fas fa-book-open text-3xl text-slate-200 dark:text-slate-600"></i>
                  </div>
                  <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Belum Ada Surat</h3>
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">
                      Buat dokumen (Nota Pesanan, BAP, Kwitansi) agar muncul di sini
                    </p>
                  </div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    
    <div class="md:hidden divide-y divide-slate-50 dark:divide-slate-700">
      <?php $noUrut = ($items->currentPage() - 1) * $items->perPage() + 1; ?>
      <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $c = $typeColors[$row['type']] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'icon_bg' => 'bg-slate-100']; ?>
        <div class="p-5 hover:bg-slate-50/50 transition-colors">
          <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-2xl <?php echo e($c['icon_bg']); ?> flex items-center justify-center flex-shrink-0">
              <i class="fas <?php echo e($row['icon']); ?> <?php echo e($c['text']); ?> text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <span class="inline-block px-2 py-0.5 rounded-lg <?php echo e($c['icon_bg']); ?> <?php echo e($c['text']); ?> text-[8px] font-black uppercase tracking-wide mb-1">
                    <?php echo e($row['label']); ?>

                  </span>
                  <a href="<?php echo e($row['route_show']); ?>"
                     class="block text-[11px] font-black text-slate-800 dark:text-white leading-tight break-all hover:text-indigo-600 transition-colors">
                    <?php echo e($row['nomor']); ?>

                  </a>
                  <p class="text-[9px] text-slate-400 mt-0.5 leading-relaxed"><?php echo e(Str::limit($row['uraian'], 60) ?: '-'); ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                  <?php if($row['total'] > 0): ?>
                    <p class="text-[10px] font-black <?php echo e($c['text']); ?>">Rp<?php echo e(number_format($row['total'], 0, ',', '.')); ?></p>
                  <?php endif; ?>
                  <?php if($row['tanggal']): ?>
                    <p class="text-[8px] font-bold text-slate-300 mt-0.5"><?php echo e(\Carbon\Carbon::parse($row['tanggal'])->format('d/m/Y')); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-16 text-center">
          <i class="fas fa-book-open text-3xl text-slate-200 mb-4 block"></i>
          <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada surat tercatat</p>
        </div>
      <?php endif; ?>
    </div>

    
    <?php if($items->hasPages()): ?>
      <div class="px-6 py-5 border-t border-slate-50 dark:border-slate-700">
        <?php echo e($items->links()); ?>

      </div>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/agenda_surat/index.blade.php ENDPATH**/ ?>