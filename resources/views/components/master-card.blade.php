@props([
    'title'    => '',
    'subtitle' => '',
    'icon'     => 'fa-circle',
    'color'    => 'indigo',
])

@php
$colorMap = [
    'indigo'  => ['bg' => 'bg-indigo-600',  'light' => 'bg-indigo-50 dark:bg-indigo-950/20',  'border' => 'border-indigo-100 dark:border-indigo-900/40', 'text' => 'text-indigo-700 dark:text-indigo-400',  'shadow' => 'shadow-indigo-100'],
    'violet'  => ['bg' => 'bg-violet-600',  'light' => 'bg-violet-50 dark:bg-violet-950/20',  'border' => 'border-violet-100 dark:border-violet-900/40', 'text' => 'text-violet-700 dark:text-violet-400',  'shadow' => 'shadow-violet-100'],
    'sky'     => ['bg' => 'bg-sky-600',     'light' => 'bg-sky-50 dark:bg-sky-950/20',     'border' => 'border-sky-100 dark:border-sky-900/40',    'text' => 'text-sky-700 dark:text-sky-400',     'shadow' => 'shadow-sky-100'],
    'emerald' => ['bg' => 'bg-emerald-600', 'light' => 'bg-emerald-50 dark:bg-emerald-950/20', 'border' => 'border-emerald-100 dark:border-emerald-900/40','text' => 'text-emerald-700 dark:text-emerald-400', 'shadow' => 'shadow-emerald-100'],
    'orange'  => ['bg' => 'bg-orange-600',  'light' => 'bg-orange-50 dark:bg-orange-950/20',  'border' => 'border-orange-100 dark:border-orange-900/40', 'text' => 'text-orange-700 dark:text-orange-400',  'shadow' => 'shadow-orange-100'],
    'rose'    => ['bg' => 'bg-rose-600',    'light' => 'bg-rose-50 dark:bg-rose-950/20',    'border' => 'border-rose-100 dark:border-rose-900/40',   'text' => 'text-rose-700 dark:text-rose-400',    'shadow' => 'shadow-rose-100'],
];
$c = $colorMap[$color] ?? $colorMap['indigo'];
@endphp

<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden transition-all duration-300 hover:shadow-md">
    {{-- Card Header --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b {{ $c['border'] }} {{ $c['light'] }} transition-colors">
        <div class="w-8 h-8 rounded-lg {{ $c['bg'] }} flex items-center justify-center shadow-sm {{ $c['shadow'] }} dark:shadow-none">
            <i class="fas {{ $icon }} text-white text-xs"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold {{ $c['text'] }} transition-colors">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Card Body --}}
    <div class="p-5">
        {{ $slot }}
    </div>
</div>
