@props(['title', 'subtitle' => null, 'actions' => null])

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold tracking-tight text-slate-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endif
</div>
