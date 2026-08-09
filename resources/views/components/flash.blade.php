@props(['type' => 'status'])

@php
    $styles = $type === 'error'
        ? 'border-cost-200 bg-cost-50 text-cost-700'
        : 'border-cash-200 bg-cash-50 text-cash-800';
@endphp

<div data-flash class="mb-4 flex items-start justify-between gap-3 rounded-xl border px-4 py-3 text-sm {{ $styles }}">
    <div class="flex items-center gap-2">
        @if ($type === 'error')
            <x-icon name="alert" class="h-4 w-4 shrink-0" />
        @endif
        <span>{{ $slot }}</span>
    </div>
    <button type="button" data-flash-close class="shrink-0 opacity-60 transition hover:opacity-100" aria-label="Tutup">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>
</div>
