<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between px-2">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Pesan</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Chat Internal</p>
        </div>
        <a href="<?php echo e(route('dashboard')); ?>" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-colors">
            <i class="fas fa-times text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-50 shadow-sm">
        <div class="divide-y divide-slate-50">
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('chat.show', $user->id)); ?>" class="flex items-center gap-4 p-5 active:bg-slate-50 transition-colors">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 overflow-hidden shadow-sm">
                            <img src="<?php echo e($user->avatar ? asset('media/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=ffffff'); ?>" class="w-full h-full object-cover">
                        </div>
                        <?php if($user->isOnline()): ?>
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white"></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-black text-slate-800 truncate uppercase mt-0.5"><?php echo e($user->name); ?></h3>
                            <?php if($user->unread_count > 0): ?>
                                <span class="bg-indigo-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full"><?php echo e($user->unread_count); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-[10px] font-bold <?php echo e($user->isOnline() ? 'text-emerald-500' : 'text-slate-400'); ?> uppercase tracking-wider mt-0.5">
                            <?php if($user->isOnline()): ?>
                                Online
                            <?php else: ?>
                                <?php echo e($user->last_seen_at ? 'Aktif ' . $user->last_seen_at->diffForHumans() : 'Offline'); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-10 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada user lain</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SI-LARANG\resources\views/chat/index.blade.php ENDPATH**/ ?>