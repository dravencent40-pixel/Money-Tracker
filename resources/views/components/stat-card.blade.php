@props(['label', 'value', 'color' => 'text-slate-900', 'icon' => null, 'sub' => null])

<div class="card p-5">
    <div class="flex items-center justify-between">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
        @if ($icon)
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                <x-icon :name="$icon" class="h-4 w-4" />
            </span>
        @endif
    </div>
    <p class="money mt-2 text-2xl font-semibold tracking-tight {{ $color }}">{{ $value }}</p>
    @if ($sub)
        <p class="mt-1 text-xs text-slate-400">{{ $sub }}</p>
    @endif
</div>
