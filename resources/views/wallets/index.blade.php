@extends('layouts.app')

@section('content')
    <h1 class="text-lg font-semibold mb-5">Dompet / Akun</h1>

    <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 divide-y divide-zinc-900 mb-6">
        @foreach ($wallets as $w)
            <div class="flex justify-between items-center px-4 py-3">
                <div>
                    <div class="text-sm font-medium">{{ $w->name }}</div>
                    <div class="text-xs text-zinc-600">{{ ucfirst($w->type) }}</div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium">Rp {{ number_format($w->current_balance, 0, ',', '.') }}</span>
                    <form method="POST" action="{{ route('wallets.destroy', $w) }}" onsubmit="return confirm('Hapus dompet ini?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-rose-500 hover:text-rose-400">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="text-sm font-semibold mb-3">Tambah Dompet</h2>
    <form method="POST" action="{{ route('wallets.store') }}" class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4 max-w-sm space-y-3">
        @csrf
        <input type="text" name="name" placeholder="mis. Rekening BCA" required class="w-full rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
        <select name="type" class="w-full rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
            <option value="cash">Cash</option>
            <option value="bank">Bank</option>
            <option value="ewallet">E-Wallet</option>
        </select>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1">Saldo awal (Rp)</label>
            <input type="number" name="starting_balance" step="0.01" min="0" value="0" class="w-full rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
        </div>
        <button type="submit" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">Tambah</button>
    </form>
@endsection
