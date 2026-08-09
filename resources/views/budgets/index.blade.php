@extends('layouts.app')

@section('title', 'Budget')

@section('content')
    @php
        $budgetTotal = (float) $summary['budget'];
        $spentTotal = (float) $summary['spent'];
        $percent = $budgetTotal > 0 ? round(min(100, ($spentTotal / $budgetTotal) * 100), 1) : null;
        $barColor = $percent === null
            ? 'bg-slate-300'
            : ($spentTotal > $budgetTotal ? 'bg-cost-500' : ($percent >= 80 ? 'bg-amber-500' : 'bg-cash-500'));
    @endphp

    <x-page-header title="Budget Bulanan"
        subtitle="{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}">
        <div class="flex items-center gap-2">
            <a href="{{ route('budgets.index', ['month' => $month, 'copy' => 'prev']) }}" class="btn-ghost !border !border-slate-200">
                Copy bulan lalu
            </a>
            <x-month-picker route="budgets.index" :month="$month" />
        </div>
    </x-page-header>

    {{-- Ringkasan budget --}}
    <section class="card mb-6 p-5">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Budget Bulan Ini</p>
                <p class="money mt-1 text-2xl font-semibold tracking-tight text-slate-900">Rp {{ number_format($budgetTotal, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500">Terpakai</p>
                <p class="money text-lg font-medium {{ $spentTotal > $budgetTotal ? 'text-cost-600' : 'text-slate-800' }}">
                    Rp {{ number_format($spentTotal, 0, ',', '.') }}
                    @if ($percent !== null)
                        <span class="text-sm text-slate-400">({{ $percent }}%)</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full transition-all {{ $barColor }}" style="width: {{ $percent ?? 0 }}%"></div>
        </div>
        @if ($spentTotal > $budgetTotal)
            <p class="mt-2 text-xs text-cost-600">Total pengeluaran melebihi total budget sebesar Rp {{ number_format($spentTotal - $budgetTotal, 0, ',', '.') }}.</p>
        @elseif ($percent !== null && $percent >= 80)
            <p class="mt-2 text-xs text-amber-600">Perhatian: sudah memakai {{ $percent }}% dari total budget.</p>
        @endif
    </section>

    {{-- Form budget per kategori --}}
    @if ($categories->isEmpty())
        <x-empty-state icon="target" title="Belum ada kategori pengeluaran"
            description="Tambahkan kategori pengeluaran dulu di halaman Kategori, lalu atur budget-nya di sini.">
            <a href="{{ route('categories.index') }}" class="btn-primary">Atur Kategori</a>
        </x-empty-state>
    @else
        <form method="POST" action="{{ route('budgets.store') }}" class="card divide-y divide-slate-100">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">

            @foreach ($categories as $c)
                @php
                    $limit = isset($budgets[$c->id]) ? (float) $budgets[$c->id] : null;
                    $used = (float) ($spent[$c->id] ?? 0);
                    $pct = $limit && $limit > 0 ? round(min(100, ($used / $limit) * 100), 1) : null;
                    $status = $limit === null ? 'none' : ($used > $limit ? 'over' : ($pct >= 80 ? 'warning' : 'ok'));
                @endphp
                <div class="px-4 py-4 md:px-5">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <span class="text-xl leading-none">{{ $c->icon ?? '🏷️' }}</span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $c->name }}</p>
                                @if ($limit !== null)
                                    <p class="money text-xs {{ $status === 'over' ? 'text-cost-600' : ($status === 'warning' ? 'text-amber-600' : 'text-slate-400') }}">
                                        Terpakai Rp {{ number_format($used, 0, ',', '.') }}
                                        @if ($status === 'over')
                                            &middot; <span class="font-semibold">Melebihi Rp {{ number_format($used - $limit, 0, ',', '.') }}</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400">Belum ada budget</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
                            <span class="text-xs text-slate-400">Rp</span>
                            <input type="number" name="amounts[{{ $c->id }}]" step="0.01" min="0"
                                   value="{{ $limit !== null ? number_format($limit, 2, '.', '') : '' }}"
                                   placeholder="0" class="input !w-28 text-right md:!w-32">
                        </div>
                    </div>
                    @if ($limit !== null)
                        <div class="mt-2.5 flex items-center gap-2">
                            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all {{ $status === 'over' ? 'bg-cost-500' : ($status === 'warning' ? 'bg-amber-500' : 'bg-cash-500') }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="money w-16 text-right text-xs text-slate-400">{{ $pct }}%</span>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="flex items-center justify-between gap-3 px-4 py-4 md:px-5">
                <p class="text-xs text-slate-400">Kosongkan field untuk menghapus budget kategori tersebut di bulan ini.</p>
                <button type="submit" class="btn-primary shrink-0">Simpan Budget</button>
            </div>
        </form>
    @endif
@endsection
