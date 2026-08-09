@extends('layouts.app')

@section('content')
    <h1>Budget</h1>

    <h2>Budget Bulanan</h2>
    <form method="GET" action="{{ route('budgets.index') }}" style="flex-direction:row; margin-bottom:8px;">
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
        <input type="hidden" name="week" value="{{ $weekStart }}">
    </form>

    <form method="POST" action="{{ route('budgets.monthly.store') }}" style="max-width:100%;">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <table>
            <thead>
                <tr><th>Kategori</th><th>Budget Bulan Ini (Rp)</th></tr>
            </thead>
            <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>
                            <input type="number" name="amounts[{{ $c->id }}]" step="0.01" min="0"
                                value="{{ $monthlyBudgets[$c->id] ?? '' }}" style="width:140px;">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" style="margin-top:10px;">Simpan Budget Bulanan</button>
    </form>

    <h2>Budget Mingguan</h2>
    <form method="GET" action="{{ route('budgets.index') }}" style="flex-direction:row; margin-bottom:8px;">
        <input type="date" name="week" value="{{ $weekStart }}" onchange="this.form.submit()">
        <input type="hidden" name="month" value="{{ $month }}">
    </form>
    <p class="muted">Isi tanggal Senin dari minggu yang mau diatur.</p>

    <form method="POST" action="{{ route('budgets.weekly.store') }}" style="max-width:100%;">
        @csrf
        <input type="hidden" name="week_start_date" value="{{ $weekStart }}">
        <table>
            <thead>
                <tr><th>Kategori</th><th>Budget Minggu Ini (Rp)</th></tr>
            </thead>
            <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>
                            <input type="number" name="amounts[{{ $c->id }}]" step="0.01" min="0"
                                value="{{ $weeklyBudgets[$c->id] ?? '' }}" style="width:140px;">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" style="margin-top:10px;">Simpan Budget Mingguan</button>
    </form>
@endsection
