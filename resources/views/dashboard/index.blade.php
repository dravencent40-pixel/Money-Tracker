@extends('layouts.app')

@section('content')
    <h1>Dashboard - {{ $month }}</h1>

    <div class="card row">
        <div>
            <div class="muted">Pemasukan bulan ini</div>
            <div class="income" style="font-size:18px;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="muted">Pengeluaran bulan ini</div>
            <div class="expense" style="font-size:18px;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
        <div>
            <div class="muted">Sisa</div>
            <div style="font-size:18px;">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>Progress Budget per Kategori</h2>
    <p class="muted">Minggu ini: {{ $weekLabel }}</p>

    @foreach ($summary as $s)
        <div class="card">
            <strong>{{ $s->category->name }}</strong>

            <div style="margin-top:6px;">
                <div class="muted row">
                    <span>Bulan ini</span>
                    <span>
                        Rp {{ number_format($s->monthly_spent, 0, ',', '.') }}
                        @if ($s->monthly_budget !== null)
                            / Rp {{ number_format($s->monthly_budget, 0, ',', '.') }}
                        @else
                            (belum ada budget)
                        @endif
                    </span>
                </div>
                @if ($s->monthly_budget !== null && $s->monthly_budget > 0)
                    @php $pct = min(100, ($s->monthly_spent / $s->monthly_budget) * 100); @endphp
                    <div class="bar-bg">
                        <div class="bar-fill {{ $s->monthly_spent > $s->monthly_budget ? 'over' : '' }}" style="width: {{ $pct }}%;"></div>
                    </div>
                @endif
            </div>

            <div style="margin-top:10px;">
                <div class="muted row">
                    <span>Minggu ini</span>
                    <span>
                        Rp {{ number_format($s->weekly_spent, 0, ',', '.') }}
                        @if ($s->weekly_budget !== null)
                            / Rp {{ number_format($s->weekly_budget, 0, ',', '.') }}
                        @else
                            (belum ada budget)
                        @endif
                    </span>
                </div>
                @if ($s->weekly_budget !== null && $s->weekly_budget > 0)
                    @php $pctW = min(100, ($s->weekly_spent / $s->weekly_budget) * 100); @endphp
                    <div class="bar-bg">
                        <div class="bar-fill {{ $s->weekly_spent > $s->weekly_budget ? 'over' : '' }}" style="width: {{ $pctW }}%;"></div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endsection
