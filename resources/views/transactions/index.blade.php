@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold">Transaksi</h1>
        <a href="{{ route('transactions.create') }}" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">+ Tambah</a>
    </div>

    <form method="GET" class="mb-4">
        <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" class="rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
        @if ($month)
            <a href="{{ route('transactions.index') }}" class="ml-2 text-sm text-zinc-600 hover:text-zinc-300">Reset filter</a>
        @endif
    </form>

    <div class="bg-zinc-950 border border-zinc-900 rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-900 text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-4 py-2">Tanggal</th>
                    <th class="text-left px-4 py-2">Tipe</th>
                    <th class="text-left px-4 py-2">Kategori</th>
                    <th class="text-left px-4 py-2">Dompet</th>
                    <th class="text-right px-4 py-2">Nominal</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-900">
                @forelse ($transactions as $t)
                    <tr>
                        <td class="px-4 py-3">{{ $t->date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $t->type === 'income' ? 'bg-emerald-950/40 text-emerald-400' : 'bg-rose-950/40 text-rose-400' }}">
                                {{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-zinc-500">{{ $t->wallet->name }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('transactions.edit', $t) }}" class="text-zinc-500 hover:text-zinc-100 text-xs mr-2">Edit</a>
                            <form method="POST" action="{{ route('transactions.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-400 text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-zinc-600">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
@endsection
