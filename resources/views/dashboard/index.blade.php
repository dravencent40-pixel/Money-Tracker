@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Dashboard</h1>
            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</p>
        </div>
        <form method="GET">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                   class="bg-slate-100 border border-slate-300 rounded-lg text-sm px-3 py-1.5 text-slate-600">
        </form>
    </div>

    {{-- Ringkasan --}}
    <section class="border border-slate-200 rounded-2xl p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-4">
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">Total Saldo</p>
                <p class="text-3xl font-semibold font-mono tracking-tight">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
            </div>
            <div class="flex gap-6 text-sm">
                <div>
                    <p class="text-xs text-slate-500">Pemasukan bulan ini</p>
                    <p class="font-mono text-emerald-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Pengeluaran bulan ini</p>
                    <p class="font-mono text-rose-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        @if ($walletComposition->isNotEmpty())
            <div class="flex w-full h-1.5 rounded-full overflow-hidden bg-slate-100 mb-2">
                @foreach ($walletComposition as $wc)
                    <div class="h-full {{ ['bg-amber-500','bg-zinc-400','bg-zinc-600'][$loop->index % 3] }}"
                         style="width: {{ $wc->percentage }}%"></div>
                @endforeach
            </div>
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                @foreach ($walletComposition as $wc)
                    <span class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ ['bg-amber-500','bg-zinc-400','bg-zinc-600'][$loop->index % 3] }}"></span>
                        {{ $wc->wallet->name }} {{ $wc->percentage }}%
                    </span>
                @endforeach
            </div>
        @endif
    </section>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        {{-- Arus kas --}}
        <section class="md:col-span-3 border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-medium text-slate-600">Arus Kas &middot; 5 bulan terakhir</h2>
                <div class="flex gap-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Masuk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span>Keluar</span>
                </div>
            </div>
            <canvas id="trendChart" height="180"></canvas>
        </section>

        {{-- Runway --}}
        <section class="md:col-span-2 border border-slate-200 rounded-2xl p-5 flex flex-col">
            <h2 class="text-sm font-medium text-slate-600 mb-1">Estimasi Bertahan</h2>
            <p class="text-xs text-slate-500 mb-4">Berdasarkan rata-rata pengeluaran harian bulan ini</p>

            @php
                $zoneColor = ['aman' => 'text-emerald-600', 'waspada' => 'text-amber-600', 'kritis' => 'text-rose-600'][$runwayZone];
                $zoneLabel = ['aman' => 'Zona Aman', 'waspada' => 'Zona Waspada', 'kritis' => 'Zona Kritis'][$runwayZone];
            @endphp

            <div class="bg-slate-100 rounded-xl p-4 flex-1">
                <p class="text-xs font-medium uppercase tracking-wide {{ $zoneColor }} mb-2">{{ $zoneLabel }}</p>
                @if ($daysLeft !== null)
                    <p class="text-3xl font-semibold font-mono">{{ $daysLeft }}<span class="text-base font-normal text-slate-500"> hari lagi</span></p>
                @else
                    <p class="text-lg text-slate-400">Belum ada pengeluaran tercatat bulan ini.</p>
                @endif
            </div>

            <div class="flex justify-between text-xs text-slate-500 mt-3">
                <span>Rata-rata harian</span>
                <span class="font-mono text-slate-600">Rp {{ number_format($avgDailyExpense, 0, ',', '.') }}</span>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Transaksi terbaru --}}
        <section class="border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-slate-600">Transaksi Terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs text-amber-500 hover:text-amber-600">Lihat semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentTransactions as $t)
                    <div class="flex items-center justify-between py-2.5 text-sm">
                        <div>
                            <p class="text-slate-700">{{ $t->category->name ?? ($t->type === 'income' ? 'Pemasukan' : 'Tanpa kategori') }}</p>
                            <p class="text-xs text-slate-500">{{ $t->date->translatedFormat('d M Y') }} &middot; {{ $t->wallet->name }}</p>
                        </div>
                        <span class="font-mono text-sm {{ $t->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="py-4 text-sm text-slate-500">Belum ada transaksi.</p>
                @endforelse
            </div>
        </section>

        {{-- Pengeluaran terbesar --}}
        <section class="border border-slate-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-slate-600">Pengeluaran Terbesar</h2>
                <span class="text-xs text-slate-500">bulan ini</span>
            </div>
            <div class="space-y-3">
                @forelse ($topExpenseCategories as $s)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-700">{{ $s->category->name }}</span>
                            <span class="font-mono text-slate-400">Rp {{ number_format($s->spent, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            @php $share = $totalExpense > 0 ? min(100, ($s->spent / $totalExpense) * 100) : 0; @endphp
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $share }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada pengeluaran bulan ini.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Progress budget --}}
    <section class="border border-slate-200 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-medium text-slate-600">Progres Anggaran</h2>
            <a href="{{ route('budgets.index') }}" class="text-xs text-amber-500 hover:text-amber-600">Atur budget &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($budgetSummary as $s)
                <div class="py-3">
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="text-slate-700">{{ $s->category->name }}</span>
                        <span class="font-mono text-slate-400">
                            Rp {{ number_format($s->spent, 0, ',', '.') }}@if ($s->limit !== null) / Rp {{ number_format($s->limit, 0, ',', '.') }}@endif
                        </span>
                    </div>
                    @if ($s->status === 'no_budget')
                        <p class="text-xs text-slate-400">Belum ada budget diatur.</p>
                    @else
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $s->status === 'over' ? 'bg-rose-500' : ($s->status === 'warning' ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                 style="width: {{ $s->percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-slate-400">{{ $s->percentage }}% terpakai</span>
                            @if ($s->status === 'over')
                                <span class="text-xs text-rose-600">Melebihi budget</span>
                            @elseif ($s->status === 'warning')
                                <span class="text-xs text-amber-600">Mendekati limit</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="py-4 text-sm text-slate-500">Belum ada kategori pengeluaran.</p>
            @endforelse
        </div>
    </section>

    <script>
        new Chart(document.getElementById('trendChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($trend->pluck('label')) !!},
                datasets: [
                    { label: 'Pemasukan', data: {!! json_encode($trend->pluck('income')) !!}, backgroundColor: '#34d399', borderRadius: 4 },
                    { label: 'Pengeluaran', data: {!! json_encode($trend->pluck('expense')) !!}, backgroundColor: '#fb7185', borderRadius: 4 },
                ]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b' } },
                    y: { grid: { color: '#e2e8f0' }, ticks: { color: '#64748b' } },
                }
            }
        });
    </script>
@endsection
