@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold">Laporan Bulanan</h1>
        <form method="GET">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
        </form>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-white border border-slate-200 rounded-lg border border-slate-200 p-4">
            <div class="text-xs text-slate-500">Total Pemasukan</div>
            <div class="text-lg font-semibold text-emerald-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-lg border border-slate-200 p-4">
            <div class="text-xs text-slate-500">Total Pengeluaran</div>
            <div class="text-lg font-semibold text-rose-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2 class="text-sm font-semibold mb-3">Rincian per Kategori</h2>
    <div class="bg-white border border-slate-200 rounded-lg border border-slate-200 divide-y divide-slate-100 mb-6">
        @forelse ($byCategory as $row)
            <div class="flex justify-between px-4 py-3 text-sm">
                <span>{{ $row->category->name ?? 'Tanpa kategori' }} <span class="text-xs text-slate-400">({{ $row->jumlah_transaksi }}x)</span></span>
                <span class="font-medium">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="px-4 py-4 text-sm text-slate-400">Tidak ada pengeluaran bulan ini.</p>
        @endforelse
    </div>

    <h2 class="text-sm font-semibold mb-3">Semua Transaksi</h2>
    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-2">Tanggal</th>
                    <th class="text-left px-4 py-2">Kategori</th>
                    <th class="text-left px-4 py-2">Dompet</th>
                    <th class="text-right px-4 py-2">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($transactions as $t)
                    <tr>
                        <td class="px-4 py-2">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-slate-500">{{ $t->wallet->name }}</td>
                        <td class="px-4 py-2 text-right {{ $t->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Tidak ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
