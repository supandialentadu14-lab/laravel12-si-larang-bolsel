<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'title'  => '',
  'subtitle' => '',
  'icon'   => 'fa-circle',
  'color'  => 'indigo',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
  'title'  => '',
  'subtitle' => '',
  'icon'   => 'fa-circle',
  'color'  => 'indigo',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$colorMap = [
  'indigo' => ['bg' => 'bg-indigo-600', 'light' => 'bg-indigo-50 ', 'border' => 'border-indigo-100 ', 'text' => 'text-indigo-700 ', 'shadow' => 'shadow-indigo-100'],
  'violet' => ['bg' => 'bg-violet-600', 'light' => 'bg-violet-50 ', 'border' => 'border-violet-100 ', 'text' => 'text-violet-700 ', 'shadow' => 'shadow-violet-100'],
  'sky'   => ['bg' => 'bg-sky-600',   'light' => 'bg-sky-50 ',   'border' => 'border-sky-100 ',  'text' => 'text-sky-700 ',   'shadow' => 'shadow-sky-100'],
  'emerald' => ['bg' => 'bg-emerald-600', 'light' => 'bg-emerald-50 ', 'border' => 'border-emerald-100 ','text' => 'text-emerald-700 ', 'shadow' => 'shadow-emerald-100'],
  'orange' => ['bg' => 'bg-orange-600', 'light' => 'bg-orange-50 ', 'border' => 'border-orange-100 ', 'text' => 'text-orange-700 ', 'shadow' => 'shadow-orange-100'],
  'rose'  => ['bg' => 'bg-rose-600',  'light' => 'bg-rose-50 ',  'border' => 'border-rose-100 ',  'text' => 'text-rose-700 ',  'shadow' => 'shadow-rose-100'],
];
$c = $colorMap[$color] ?? $colorMap['indigo'];
?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-md">
  
  <div class="flex items-center gap-3 px-5 py-4 border-b <?php echo e($c['border']); ?> <?php echo e($c['light']); ?> transition-colors">
    <div class="w-8 h-8 rounded-lg <?php echo e($c['bg']); ?> flex items-center justify-center shadow-sm <?php echo e($c['shadow']); ?> ">
      <i class="fas <?php echo e($icon); ?> text-white text-xs"></i>
    </div>
    <div>
      <h3 class="text-sm font-bold <?php echo e($c['text']); ?> transition-colors"><?php echo e($title); ?></h3>
      <?php if($subtitle): ?>
        <p class="text-[11px] text-slate-400 mt-0.5 transition-colors"><?php echo e($subtitle); ?></p>
      <?php endif; ?>
    </div>
  </div>

  
  <div class="p-5">
    <?php echo e($slot); ?>

  </div>
</div>
<?php /**PATH D:\SI-LARANG\resources\views/components/master-card.blade.php ENDPATH**/ ?>