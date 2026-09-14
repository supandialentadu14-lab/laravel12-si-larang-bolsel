<?php $__env->startSection('content'); ?>
<div x-data="{ showFilters: <?php echo e(request('search') || request('category_id') ? 'true' : 'false'); ?> }" class="space-y-6">

  
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-slate-800 transition-colors uppercase tracking-tight">Daftar Barang</h1>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 transition-colors">Kelola Stok & Inventaris</p>
      </div>
      <div class="flex gap-2">
        <button @click="showFilters = !showFilters" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-all" :class="showFilters ? 'text-indigo-600 border-indigo-100 ring-4 ring-indigo-50' : ''">
          <i class="fas fa-filter text-xs"></i>
        </button>
        <a href="<?php echo e(route('products.create')); ?>" class="w-10 h-10 rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-100 flex items-center justify-center active:scale-90 transition-transform">
          <i class="fas fa-plus text-xs"></i>
        </a>
      </div>
    </div>

    
    <a href="<?php echo e(route('import.index')); ?>" class="w-full flex items-center justify-center gap-3 py-4 bg-white border border-slate-100 rounded-2xl shadow-sm text-slate-600 active:scale-[0.98] transition-all group">
      <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
        <i class="fas fa-file-import text-[10px]"></i>
      </div>
      <span class="text-[10px] font-black uppercase tracking-[0.2em]">Impor Data Barang</span>
    </a>
  </div>

  
  <div x-show="showFilters" x-collapse x-cloak>
    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-4">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-2">Filter Pencarian</h3>
      <form action="<?php echo e(route('products.index')); ?>" method="GET" class="space-y-4">
        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Barang</label>
          <div class="relative">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama barang..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none">
          </div>
        </div>

        <div class="space-y-1.5">
          <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Kategori</label>
          <select name="category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
            <option value="">Semua Kategori</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">
            Terapkan
          </button>
          <a href="<?php echo e(route('products.index')); ?>" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  
  <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 overflow-hidden relative group transition-all">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="relative z-10">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Total Nilai Persediaan</p>
      <h2 class="text-3xl font-black mt-2 tracking-tight">Rp<?php echo e(number_format($products->sum(fn($p) => $p->price * $p->stock), 0, ',', '.')); ?></h2>
      
      <div class="flex items-center gap-4 mt-6">
        <div class="flex flex-col">
          <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Total Item</span>
          <span class="text-sm font-black"><?php echo e($products->total()); ?> Jenis</span>
        </div>
        <div class="w-px h-8 bg-white/20"></div>
        <div class="flex flex-col">
          <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Stok Rendah</span>
          <span class="text-sm font-black text-rose-300"><?php echo e($lowStockCount ?? 0); ?> Item</span>
        </div>
      </div>
    </div>
  </div>

  
  <div class="space-y-4">
    <div class="flex items-center justify-between px-2">
      <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Daftar Stok Barang</h3>
      <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest"><?php echo e($products->count()); ?> Ditampilkan</span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
      $stock = $product->stock;
      $min = $product->min_stock ?: 5;
      $isLow = $stock <= $min;
    ?>
    <div class="bg-white rounded-[2.5rem] p-5 border border-slate-50 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
      <div class="flex items-start gap-4">
        
        <div class="w-14 h-14 rounded-[1.5rem] <?php echo e($isLow ? 'bg-rose-50 text-rose-500' : 'bg-indigo-50 text-indigo-600'); ?> flex items-center justify-center text-xl font-black shadow-inner flex-shrink-0 transition-colors">
          <?php echo e(substr($product->name, 0, 1)); ?>

        </div>

        
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between">
            <div>
              <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate leading-tight transition-colors"><?php echo e($product->name); ?></h3>
              <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1"><?php echo e($product->category->name ?? 'Tanpa Kategori'); ?></p>
            </div>
            <div class="flex flex-col items-end">
              <span class="text-xs font-black text-slate-900 transition-colors">Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
              <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">/ <?php echo e($product->unit); ?></span>
            </div>
          </div>

          <div class="flex items-center justify-between mt-5">
            <div class="flex items-center gap-2">
              <div class="px-4 py-1.5 rounded-full <?php echo e($isLow ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white'); ?> flex items-center gap-2 shadow-sm">
                <i class="fas <?php echo e($isLow ? 'fa-exclamation-triangle' : 'fa-check-circle'); ?> text-[9px]"></i>
                <span class="text-[9px] font-black tracking-widest"><?php echo e($product->stock); ?> <?php echo e(strtoupper($product->unit)); ?></span>
              </div>
            </div>

            
            <div class="flex items-center gap-1.5">
              <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                <i class="fas fa-edit text-[10px]"></i>
              </a>
              <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" @click.prevent="if(confirm('PERHATIAN! Menghapus barang ini akan menghapus SELURUH RIWAYAT TRANSAKSI (Masuk & Keluar) serta mengupdate Berkas/Laporan yang memuat data barang ini secara permanen. Apakah Anda yakin?')) $el.form.submit()" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-600 transition-colors">
                  <i class="fas fa-trash text-[10px]"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-50 shadow-sm transition-colors">
      <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-box-open text-3xl text-slate-200 "></i>
      </div>
      <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tidak Ada Barang</h3>
      <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-widest">Gunakan tombol (+) untuk menambah</p>
    </div>
    <?php endif; ?>
  </div>

  
  <div class="mt-8">
    <?php echo e($products->links()); ?>

  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/products/index.blade.php ENDPATH**/ ?>