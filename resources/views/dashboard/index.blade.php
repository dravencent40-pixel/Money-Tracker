@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $colors = ['#17a571', '#0c855c', '#ed2f4a', '#cb1736', '#f59e0b', '#fbbf24'];
    @endphp

    <x-page-header title="Dashboard" subtitle="{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}">
        <x-month-picker route="dashboard" :month="$month" />
    </x-page-header>

    @if (! $hasAnyData)
        <x-empty-state icon="sparkle" title="Selamat datang di CashFlow!"
            description="Mulai dengan mencatat transaksi pertamamu, lalu pantau arus kas, budget, dan laporan keuanganmu di satu tempat.">
            <button type="button" data-toggle-modal="quick-add" class="btn-primary">
                <x-icon name="plus" class="h-4 w-4" /> Catat Transaksi Pertama
            </button>
        </x-empty-state>
    @endif

    {{-- Ringkasan --}}
    <section class="mb-6 overflow-hidden rounded-2xl bg-ink-950 p-6 text-white shadow-sm">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total Saldo</p>
                <p class="money mt-2 text-3xl font-semibold tracking-tight">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
            </div>
            <div class="flex gap-8 text-sm">
                <div>
                    <p class="text-xs text-slate-400">Pemasukan bulan ini</p>
                    <p class="money mt-1 font-semibold text-cash-300">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Pengeluaran bulan ini</p>
                    <p class="money mt-1 font-semibold text-cost-300">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        @if ($walletComposition->isNotEmpty())
            <div class="mt-6">
                <div class="mb-2 flex h-1.5 w-full overflow-hidden rounded-full bg-white/10">
                    @foreach ($walletComposition as $wc)
                        <div class="h-full {{ ['bg-amber-400', 'bg-cash-400', 'bg-cost-400', 'bg-slate-400'][$loop->index % 4] }}"
                             style="width: {{ $wc->percentage }}%"></div>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-300">
                    @foreach ($walletComposition as $wc)
                        <span class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full {{ ['bg-amber-400', 'bg-cash-400', 'bg-cost-400', 'bg-slate-400'][$loop->index % 4] }}"></span>
                            {{ $wc->wallet->name }} {{ $wc->percentage }}%
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-5">
        {{-- Arus kas --}}
        <section class="card p-5 md:col-span-3">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700">Arus Kas</h2>
                <div class="flex gap-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-cash-500"></span>Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-cost-500"></span>Keluar</span>
                </div>
            </div>
            <p class="mb-4 text-xs text-slate-400">5 bulan terakhir</p>
            <div class="relative h-40 w-full sm:h-48">
                <canvas id="trendChart" aria-label="Grafik arus kas 5 bulan terakhir" role="img"></canvas>
            </div>
        </section>

        {{-- Rata-rata pengeluaran harian --}}
        <section class="card flex flex-col p-5 md:col-span-2">
            <h2 class="text-sm font-semibold text-slate-700">Pengeluaran Harian</h2>
            <p class="mb-4 text-xs text-slate-400">Rata-rata pengeluaran per hari bulan ini</p>

            <div class="flex flex-1 flex-col justify-center rounded-xl bg-slate-100 p-5">
                @if ($avgDailyExpense > 0)
                    <p class="money text-3xl font-semibold tracking-tight text-slate-900">
                        Rp {{ number_format($avgDailyExpense, 0, ',', '.') }}
                        <span class="ml-1 text-base font-normal text-slate-500">/ hari</span>
                    </p>
                @else
                    <p class="text-sm text-slate-400">Belum ada pengeluaran tercatat bulan ini.</p>
                @endif
            </div>
        </section>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        {{-- Transaksi terbaru --}}
        <section class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700">Transaksi Terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs font-medium text-amber-600 hover:text-amber-500">Lihat semua &rarr;</a>
            </div>
            @if ($recentTransactions->isEmpty())
                <p class="py-6 text-center text-sm text-slate-400">Belum ada transaksi.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($recentTransactions as $t)
                        <div class="flex items-center justify-between gap-2 py-2.5 text-sm">
                            <div class="flex min-w-0 items-center gap-2.5">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-lg {{ $t->type === 'income' ? 'bg-cash-50' : 'bg-cost-50' }}">
                                    {{ $t->category->icon ?? ($t->type === 'income' ? '💰' : '💸') }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-slate-700">{{ $t->category->name ?? ($t->type === 'income' ? 'Pemasukan' : 'Tanpa kategori') }}</p>
                                    <p class="text-xs text-slate-400">{{ $t->date->translatedFormat('d M Y') }} &middot; {{ $t->wallet->name }}</p>
                                </div>
                            </div>
                            <span class="money shrink-0 text-sm font-medium {{ $t->type === 'income' ? 'text-cash-600' : 'text-cost-600' }}">
                                {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Komposisi pengeluaran --}}
        <section class="card p-5">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-700">Pengeluaran per Kategori</h2>
                <a href="{{ route('reports.index', ['month' => $month]) }}" class="text-xs font-medium text-amber-600 hover:text-amber-500">Laporan &rarr;</a>
            </div>
            @if ($expenseBreakdown->isEmpty())
                <p class="py-6 text-center text-sm text-slate-400">Belum ada pengeluaran bulan ini.</p>
            @else
                <div class="flex flex-col items-center gap-4 sm:flex-row">
                    <div class="relative h-40 w-40 shrink-0">
                        <canvas id="expenseDonut"></canvas>
                    </div>
                    <div class="w-full min-w-0 flex-1 space-y-2">
                        @foreach ($expenseBreakdown as $row)
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <span class="flex min-w-0 items-center gap-1.5">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $colors[$loop->index % count($colors)] }}"></span>
                                    <span class="truncate">{{ $row->category->icon ?? '' }} {{ $row->category->name }}</span>
                                </span>
                                <span class="money shrink-0 text-slate-600">Rp {{ number_format($row->spent, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>

    {{-- Progress budget --}}
    <section class="card p-5">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-700">Progres Anggaran</h2>
            <a href="{{ route('budgets.index', ['month' => $month]) }}" class="text-xs font-medium text-amber-600 hover:text-amber-500">Atur budget &rarr;</a>
        </div>
        @if ($budgetSummary->isEmpty())
            <p class="py-4 text-center text-sm text-slate-400">Belum ada kategori pengeluaran.</p>
        @else
            <div class="grid grid-cols-1 gap-x-8 md:grid-cols-2">
                @foreach ($budgetSummary as $s)
                    <div class="py-3">
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <span class="flex min-w-0 items-center gap-2">
                                <span class="text-base leading-none">{{ $s->category->icon ?? '🏷️' }}</span>
                                <span class="truncate text-slate-700">{{ $s->category->name }}</span>
                            </span>
                            <span class="money shrink-0 text-slate-500">
                                Rp {{ number_format($s->spent, 0, ',', '.') }}@if ($s->limit !== null)<span class="text-slate-400"> / Rp {{ number_format($s->limit, 0, ',', '.') }}</span>@endif
                            </span>
                        </div>
                        @if ($s->status === 'no_budget')
                            <p class="mt-1.5 text-xs text-slate-400">Belum ada budget diatur.</p>
                        @else
                            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all {{ $s->status === 'over' ? 'bg-cost-500' : ($s->status === 'warning' ? 'bg-amber-500' : 'bg-cash-500') }}"
                                     style="width: {{ $s->percentage }}%"></div>
                            </div>
                            <div class="mt-1 flex justify-between">
                                <span class="money text-xs text-slate-400">{{ $s->percentage }}% terpakai</span>
                                @if ($s->status === 'over')
                                    <span class="text-xs font-medium text-cost-600">Melebihi budget</span>
                                @elseif ($s->status === 'warning')
                                    <span class="text-xs font-medium text-amber-600">Mendekati limit</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Chart(document.getElementById('trendChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($trend->pluck('label')) !!},
                    datasets: [
                        { label: 'Pemasukan', data: {!! json_encode($trend->pluck('income')) !!}, backgroundColor: '#17a571', borderRadius: 4 },
                        { label: 'Pengeluaran', data: {!! json_encode($trend->pluck('expense')) !!}, backgroundColor: '#ed2f4a', borderRadius: 4 },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748b' } },
                        y: { grid: { color: '#e2e8f0' }, ticks: { color: '#64748b' }, beginAtZero: true },
                    },
                },
            });

            @if ($expenseBreakdown->isNotEmpty())
                new Chart(document.getElementById('expenseDonut'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($expenseBreakdown->pluck('category.name')) !!},
                        datasets: [{
                            data: {!! json_encode($expenseBreakdown->pluck('spent')->map(fn ($v) => (float) $v)) !!},
                            backgroundColor: {!! json_encode($expenseBreakdown->keys()->map(fn ($i) => $colors[$i % count($colors)])->all()) !!},
                            borderWidth: 0,
                            hoverOffset: 4,
                        }],
                    },
                    options: {
                        cutout: '62%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': Rp ' + Number(ctx.parsed).toLocaleString('id-ID') } },
                        },
                    },
                });
            @endif
        });
    </script>
@endsection
