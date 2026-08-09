@props(['icon' => 'inbox', 'title', 'description', 'action' => null])

<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/60 px-6 py-10 text-center">
    <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        <x-icon :name="$icon" class="h-6 w-6" />
    </span>
    <h3 class="text-sm font-semibold text-slate-800">{{ $title }}</h3>
    <p class="mt-1 max-w-sm text-sm text-slate-500">{{ $description }}</p>
    @if ($action)
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
