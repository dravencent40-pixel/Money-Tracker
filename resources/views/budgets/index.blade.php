@extends('layouts.app')

@section('content')
    <h1 class="text-lg font-semibold mb-5">Budget Bulanan</h1>

    <form method="GET" class="mb-4">
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
    </form>

    <form method="POST" action="{{ route('budgets.store') }}" class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 divide-y divide-zinc-900">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        @foreach ($categories as $c)
            <div class="flex justify-between items-center px-4 py-3">
                <span class="text-sm">{{ $c->name }}</span>
                <div class="flex items-center gap-1 text-sm">
                    <span class="text-zinc-600">Rp</span>
                    <input type="number" name="amounts[{{ $c->id }}]" step="0.01" min="0"
                           value="{{ $budgets[$c->id] ?? '' }}" placeholder="0"
                           class="w-32 rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm text-right">
                </div>
            </div>
        @endforeach
        <div class="px-4 py-3">
            <button type="submit" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">Simpan Budget</button>
        </div>
    </form>
    <p class="text-xs text-zinc-600 mt-2">Kosongkan field untuk menghapus budget kategori tersebut di bulan ini.</p>
@endsection
