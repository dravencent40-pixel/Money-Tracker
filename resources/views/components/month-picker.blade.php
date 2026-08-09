@props(['route', 'month'])

@php
    $current = \Carbon\Carbon::createFromFormat('Y-m', $month);
    $prev = $current->copy()->subMonth()->format('Y-m');
    $next = $current->copy()->addMonth()->format('Y-m');
    $isCurrentMonth = $month === \Carbon\Carbon::now()->format('Y-m');
@endphp

<div class="flex items-center gap-1">
    <a href="{{ route($route, ['month' => $prev] + request()->except('month')) }}"
       class="btn-ghost !px-2 !py-1.5" aria-label="Bulan sebelumnya">
        <x-icon name="chevron-left" class="h-4 w-4" />
    </a>
    <input type="month" name="month" value="{{ $month }}" data-month-picker
           class="input !w-44 !py-1.5 text-center" aria-label="Pilih bulan">
    <a href="{{ route($route, ['month' => $next] + request()->except('month')) }}"
       class="btn-ghost !px-2 !py-1.5" aria-label="Bulan berikutnya">
        <x-icon name="chevron-right" class="h-4 w-4" />
    </a>
    @if (! $isCurrentMonth)
        <a href="{{ route($route, ['month' => now()->format('Y-m')] + request()->except('month')) }}"
           class="btn-ghost !px-2 !py-1.5 text-xs font-semibold" title="Kembali ke bulan ini">
            Bulan ini
        </a>
    @endif
</div>
