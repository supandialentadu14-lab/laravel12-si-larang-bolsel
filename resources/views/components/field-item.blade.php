@props([
    'label' => '',
    'value' => null,
    'mono'  => false,
])

<div>
    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">{{ $label }}</p>
    @if($value)
        <p class="text-sm font-semibold text-slate-800 {{ $mono ? 'font-mono tracking-wide' : '' }}">
            {{ $value }}
        </p>
    @else
        <p class="text-sm text-slate-400 italic flex items-center gap-1">
            <i class="fas fa-minus text-[10px]"></i> Belum diisi
        </p>
    @endif
</div>
