@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold">Transaksi</h1>
        <a href="{{ route('transactions.create') }}" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">+ Tambah</a>
    </div>

    <form method="GET" class="mb-4">
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="rounded-md border-slate-300 bg-slate-100 text-slate-700 text-sm">
        @if ($month)
            <a href="{{ route('transactions.index') }}" class="ml-2 text-sm text-slate-400 hover:text-slate-600">Reset filter</a>
        @endif
    </form>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-2">Tanggal</th>
                    <th class="text-left px-4 py-2">Tipe</th>
                    <th class="text-left px-4 py-2">Kategori</th>
                    <th class="text-left px-4 py-2">Dompet</th>
                    <th class="text-right px-4 py-2">Nominal</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($transactions as $t)
                    <tr>
                        <td class="px-4 py-3">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $t->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->wallet->name }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('transactions.edit', $t) }}" class="text-slate-500 hover:text-slate-800 text-xs mr-2">Edit</a>
                            <form method="POST" action="{{ route('transactions.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-600 text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
@endsection
