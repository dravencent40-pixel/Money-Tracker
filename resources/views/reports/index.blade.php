@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    @php $colors = ['#17a571', '#0c855c', '#ed2f4a', '#cb1736', '#f59e0b', '#fbbf24', '#33425f', '#6e88b8']; @endphp

    <x-page-header title="Laporan Bulanan" subtitle="{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}">
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.export', ['month' => $month]) }}" class="btn-ghost !border !border-slate-200">
                <x-icon name="download" class="h-4 w-4" /> Export CSV
            </a>
            <x-month-picker route="reports.index" :month="$month" />
        </div>
    </x-page-header>

    {{-- Ringkasan --}}
    <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-stat-card label="Pemasukan" :value="'Rp ' . number_format($totalIncome, 0, ',', '.')" color="text-cash-600" icon="cash" />
        <x-stat-card label="Pengeluaran" :value="'Rp ' . number_format($totalExpense, 0, ',', '.')" color="text-cost-600" icon="reports" />
        <x-stat-card label="Net Balance" :value="'Rp ' . number_format($net, 0, ',', '.')"
            :color="$net >= 0 ? 'text-slate-900' : 'text-cost-600'" icon="scale" />
        <x-stat-card label="Tingkat Menabung" :value="$savingsRate !== null ? $savingsRate . '%' : '-'"
            :color="$savingsRate !== null && $savingsRate >= 0 ? 'text-slate-900' : 'text-cost-600'" icon="target"
            :sub="$savingsRate !== null ? 'dari total pemasukan' : null" />
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Donut pengeluaran per kategori --}}
        <section class="card p-5">
            <h2 class="mb-1 text-sm font-semibold text-slate-700">Komposisi Pengeluaran</h2>
            <p class="mb-4 text-xs text-slate-400">Per kategori, bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</p>
            @if ($byCategory->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">Belum ada pengeluaran bulan ini.</p>
            @else
                <div class="flex flex-col items-center gap-6 sm:flex-row">
                    <div class="relative h-44 w-44 shrink-0">
                        <canvas id="categoryDonut"></canvas>
                    </div>
                    <ul class="w-full min-w-0 flex-1 space-y-2">
                        @foreach ($byCategory as $row)
                            <li class="flex items-center justify-between gap-2 text-sm">
                                <span class="flex min-w-0 items-center gap-1.5">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $colors[$loop->index % count($colors)] }}"></span>
                                    <span class="truncate">{{ $row->category->icon ?? '' }} {{ $row->category->name ?? 'Tanpa kategori' }}</span>
                                </span>
                                <span class="money shrink-0 text-slate-600">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>

        {{-- Rincian per kategori --}}
        <section class="card p-5">
            <h2 class="mb-1 text-sm font-semibold text-slate-700">Rincian per Kategori</h2>
            <p class="mb-4 text-xs text-slate-400">Jumlah transaksi dalam tanda kurung</p>
            <div class="divide-y divide-slate-100">
                @forelse ($byCategory as $row)
                    <div class="flex items-center justify-between py-2.5 text-sm">
                        <span class="flex min-w-0 items-center gap-1.5">
                            <span class="text-base leading-none">{{ $row->category->icon ?? '🏷️' }}</span>
                            <span class="truncate">{{ $row->category->name ?? 'Tanpa kategori' }}</span>
                            <span class="text-xs text-slate-400">({{ $row->jumlah_transaksi }}x)</span>
                        </span>
                        <span class="money shrink-0 font-medium text-slate-800">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-slate-400">Tidak ada pengeluaran bulan ini.</p>
                @endforelse
            </div>
        </section>
    </div>

    {{-- Semua transaksi --}}
    <h2 class="mb-3 text-sm font-semibold text-slate-700">Semua Transaksi</h2>
    @if ($transactions->isEmpty())
        <x-empty-state icon="inbox" title="Tidak ada transaksi"
            description="Belum ada transaksi tercatat pada bulan ini." />
    @else
        <div class="card overflow-hidden">
            <table class="hidden w-full text-sm md:table">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-2.5 font-medium">Tanggal</th>
                        <th class="px-4 py-2.5 font-medium">Kategori</th>
                        <th class="px-4 py-2.5 font-medium">Dompet</th>
                        <th class="px-4 py-2.5 text-right font-medium">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($transactions as $t)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2.5 text-slate-600">{{ $t->date->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2.5">
                                @if ($t->category)
                                    <span class="flex items-center gap-1.5">
                                        @if ($t->category->icon)<span class="text-base leading-none">{{ $t->category->icon }}</span>@endif
                                        {{ $t->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-slate-500">{{ $t->wallet->name }}</td>
                            <td class="money whitespace-nowrap px-4 py-2.5 text-right font-medium {{ $t->type === 'income' ? 'text-cash-600' : 'text-cost-600' }}">
                                {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <ul class="divide-y divide-slate-100 md:hidden">
                @foreach ($transactions as $t)
                    <li class="flex items-center justify-between gap-2 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-slate-800">
                                {{ $t->category->name ?? ($t->type === 'income' ? 'Pemasukan' : 'Tanpa kategori') }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $t->date->translatedFormat('d M Y') }} &middot; {{ $t->wallet->name }}
                            </p>
                        </div>
                        <p class="money text-sm font-medium {{ $t->type === 'income' ? 'text-cash-600' : 'text-cost-600' }}">
                            {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('scripts')
    @if ($byCategory->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Chart(document.getElementById('categoryDonut'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($byCategory->pluck('category.name')) !!},
                        datasets: [{
                            data: {!! json_encode($byCategory->pluck('total')->map(fn ($v) => (float) $v)) !!},
                            backgroundColor: {!! json_encode($byCategory->keys()->map(fn ($i) => $colors[$i % count($colors)])->all()) !!},
                            borderWidth: 0,
                            hoverOffset: 4,
                        }],
                    },
                    options: {
                        cutout: '62%',
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': Rp ' + Number(ctx.parsed).toLocaleString('id-ID') } } },
                    },
                });
            });
        </script>
    @endif
@endsection
