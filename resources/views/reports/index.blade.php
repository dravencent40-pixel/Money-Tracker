@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold">Laporan Bulanan</h1>
        <form method="GET">
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
        </form>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4">
            <div class="text-xs text-zinc-500">Total Pemasukan</div>
            <div class="text-lg font-semibold text-emerald-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4">
            <div class="text-xs text-zinc-500">Total Pengeluaran</div>
            <div class="text-lg font-semibold text-rose-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2 class="text-sm font-semibold mb-3">Rincian per Kategori</h2>
    <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 divide-y divide-zinc-900 mb-6">
        @forelse ($byCategory as $row)
            <div class="flex justify-between px-4 py-3 text-sm">
                <span>{{ $row->category->name ?? 'Tanpa kategori' }} <span class="text-xs text-zinc-600">({{ $row->jumlah_transaksi }}x)</span></span>
                <span class="font-medium">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="px-4 py-4 text-sm text-zinc-600">Tidak ada pengeluaran bulan ini.</p>
        @endforelse
    </div>

    <h2 class="text-sm font-semibold mb-3">Semua Transaksi</h2>
    <div class="bg-zinc-950 border border-zinc-900 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-900 text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-2">Tanggal</th>
                    <th class="text-left px-4 py-2">Kategori</th>
                    <th class="text-left px-4 py-2">Dompet</th>
                    <th class="text-right px-4 py-2">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-900">
                @forelse ($transactions as $t)
                    <tr>
                        <td class="px-4 py-2">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-zinc-500">{{ $t->wallet->name }}</td>
                        <td class="px-4 py-2 text-right {{ $t->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-zinc-600">Tidak ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
