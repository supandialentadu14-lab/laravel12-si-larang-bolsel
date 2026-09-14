<?php if (isset($component)) { $__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.guest-layout','data' => ['title' => 'MASUK','subtitle' => 'Akses manajemen persediaan & pemetaan jaringan telekomunikasi di Kab. Bolsel']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'MASUK','subtitle' => 'Akses manajemen persediaan & pemetaan jaringan telekomunikasi di Kab. Bolsel']); ?>

  <?php if(session('success_message')): ?>
    <div class="mb-4 animate__animated animate__fadeIn">
      <div class="bg-emerald-50 border border-emerald-100/50 rounded-2xl p-3 flex items-center gap-3">
        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
        <p class="text-emerald-700 font-bold text-[9px] leading-tight"><?php echo e(session('success_message')); ?></p>
      </div>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('login', absolute: false)); ?>" class="space-y-4 sm:space-y-4">
    <?php echo csrf_field(); ?>

    
    <div class="space-y-0.5 sm:space-y-1">
      <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Email</label>
      <div class="relative group"> 
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="far fa-envelope text-[10px] sm:text-xs"></i>
        </div>
        <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
          oninvalid="this.setCustomValidity('Email harus diisi')" 
          oninput="this.setCustomValidity('')"
          class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="nama@email.com">
      </div>
      <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-0.5 ml-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-0.5 ml-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
    </div>

    
    <div class="space-y-0.5 sm:space-y-1" x-data="{ show: false }">
      <div class="flex items-center justify-between px-4">
        <label class="block text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">Password</label>
      </div>
      <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-600 transition-colors">
          <i class="fas fa-lock text-[10px] sm:text-xs"></i>
        </div>
        <input id="password" :type="show ? 'text' : 'password'" name="password" required
          oninvalid="this.setCustomValidity('Password harus diisi')" 
          oninput="this.setCustomValidity('')"
          class="w-full pl-10 sm:pl-12 pr-10 sm:pr-12 py-2.5 sm:py-3 bg-slate-50 border-2 border-transparent focus:border-indigo-600 focus:bg-white rounded-[1.2rem] text-[10px] sm:text-[11px] font-bold text-slate-800 transition-all outline-none"
          placeholder="••••••••">
        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 sm:pr-5 flex items-center text-slate-300 hover:text-indigo-600 transition-colors">
          <i class="fas text-[9px] sm:text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
        </button>
      </div>
      <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'mt-0.5 ml-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'mt-0.5 ml-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
    </div>

    <div class="flex items-center justify-between px-4">
      <label class="flex items-center cursor-pointer group">
        <input id="remember_me" name="remember" type="checkbox" class="sr-only peer">
        <div class="w-8 h-4 bg-slate-100 rounded-full peer peer-checked:bg-slate-800 transition-all relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
        <span class="ml-2.5 text-[8px] sm:text-[9px] font-black text-slate-400 uppercase tracking-widest">Ingat</span>
      </label>
      <?php if(Route::has('password.request')): ?>
        <a href="<?php echo e(route('password.request')); ?>" class="text-[8px] sm:text-[9px] font-black text-indigo-600 uppercase">Lupa?</a>
      <?php endif; ?>
    </div>

    <button type="submit" class="w-full py-3.5 sm:py-4 bg-indigo-600 text-white rounded-[1.5rem] sm:rounded-[1.8rem] text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-slate-800 transition-all">
      Masuk <i class="fas fa-arrow-right ml-1 sm:ml-2 text-[9px] sm:text-[10px]"></i>
    </button>
  </form>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a)): ?>
<?php $attributes = $__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a; ?>
<?php unset($__attributesOriginalcb8170ac00b272413fe5b25f86fc5e3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a)): ?>
<?php $component = $__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a; ?>
<?php unset($__componentOriginalcb8170ac00b272413fe5b25f86fc5e3a); ?>
<?php endif; ?>
<?php /**PATH D:\SI-LARANG\resources\views/auth/login.blade.php ENDPATH**/ ?>