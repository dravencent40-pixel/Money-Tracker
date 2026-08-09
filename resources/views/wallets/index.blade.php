@extends('layouts.app')

@section('title', 'Dompet')

@section('content')
    @php
        $total = $wallets->sum('current_balance');
        $typeMeta = [
            'cash' => ['label' => 'Cash', 'icon' => 'cash', 'bg' => 'bg-amber-50 text-amber-600'],
            'bank' => ['label' => 'Bank', 'icon' => 'bank', 'bg' => 'bg-cash-50 text-cash-600'],
            'ewallet' => ['label' => 'E-Wallet', 'icon' => 'ewallet', 'bg' => 'bg-ink-100 text-ink-700'],
        ];
    @endphp

    <x-page-header title="Dompet / Akun" subtitle="Kelola tempat menyimpan uangmu" />

    {{-- Total saldo --}}
    <section class="mb-6 overflow-hidden rounded-2xl bg-ink-950 p-6 text-white shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total Saldo Semua Dompet</p>
                <p class="money mt-2 text-3xl font-semibold tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                <x-icon name="wallet" class="h-6 w-6" />
            </span>
        </div>
    </section>

    {{-- Daftar dompet --}}
    @if ($wallets->isEmpty())
        <x-empty-state icon="wallet-card" title="Belum ada dompet"
            description="Buat dompet pertamamu — misalnya Cash, Rekening Bank, atau E-Wallet." />
    @else
        <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
            @foreach ($wallets as $w)
                @php $meta = $typeMeta[$w->type] ?? $typeMeta['cash']; @endphp
                <div class="card flex items-center justify-between p-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $meta['bg'] }}">
                            <x-icon :name="$meta['icon']" class="h-5 w-5" />
                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-800">{{ $w->name }}</p>
                            <p class="text-xs text-slate-400">{{ $meta['label'] }}</p>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-3">
                        <div class="text-right">
                            <p class="money text-sm font-semibold {{ $w->current_balance >= 0 ? 'text-slate-800' : 'text-cost-600' }}">
                                Rp {{ number_format($w->current_balance, 0, ',', '.') }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('wallets.destroy', $w) }}"
                              onsubmit="return confirm('Hapus dompet ini? Transaksi di dalamnya juga akan dihapus.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-ghost !px-2 !py-1.5 !text-cost-600 hover:!bg-cost-50" aria-label="Hapus dompet" title="Hapus">
                                <x-icon name="trash" class="h-4 w-4" />
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Tambah dompet --}}
    <h2 class="mb-3 text-sm font-semibold text-slate-700">Tambah Dompet</h2>
    <form method="POST" action="{{ route('wallets.store') }}" class="card max-w-md space-y-3 p-5">
        @csrf

        <div>
            <label for="w-name" class="label">Nama dompet</label>
            <input id="w-name" type="text" name="name" placeholder="mis. Rekening BCA" required value="{{ old('name') }}" class="input">
            @error('name')
                <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="w-type" class="label">Tipe</label>
            <select id="w-type" name="type" class="input">
                @foreach ($typeMeta as $key => $meta)
                    <option value="{{ $key }}" @selected(old('type', 'cash') === $key)>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="w-balance" class="label">Saldo awal (Rp)</label>
            <input id="w-balance" type="number" name="starting_balance" step="0.01" min="0" value="{{ old('starting_balance', 0) }}" class="input money">
            @error('starting_balance')
                <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">Tambah Dompet</button>
    </form>
@endsection
