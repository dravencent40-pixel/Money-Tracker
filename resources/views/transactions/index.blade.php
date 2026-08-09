@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    @php
        $pickerMonth = $month ?: now()->format('Y-m');
        $net = $summaryIncome - $summaryExpense;
        $hasFilter = $month || $type || $categoryId || $walletId || $q !== '';
    @endphp

    <x-page-header title="Transaksi" subtitle="{{ $month ? \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') : 'Semua waktu' }}">
        <button type="button" data-toggle-modal="quick-add" class="btn-primary">
            <x-icon name="plus" class="h-4 w-4" /> Tambah
        </button>
    </x-page-header>

    {{-- Filter --}}
    <form method="GET" class="card mb-5 flex flex-col gap-2 p-4 md:flex-row md:items-end">
        <div>
            <span class="label">Bulan</span>
            <div>
                <x-month-picker route="transactions.index" :month="$pickerMonth" />
            </div>
        </div>
        <div class="grid flex-1 grid-cols-2 gap-2 md:grid-cols-4">
            <div>
                <label for="f-type" class="label">Tipe</label>
                <select id="f-type" name="type" class="input">
                    <option value="">Semua tipe</option>
                    <option value="income" @selected($type === 'income')>Pemasukan</option>
                    <option value="expense" @selected($type === 'expense')>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label for="f-category" class="label">Kategori</label>
                <select id="f-category" name="category_id" class="input">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected($categoryId == $c->id)>
                            {{ $c->icon ? $c->icon . ' ' : '' }}{{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-wallet" class="label">Dompet</label>
                <select id="f-wallet" name="wallet_id" class="input">
                    <option value="">Semua dompet</option>
                    @foreach ($wallets as $w)
                        <option value="{{ $w->id }}" @selected($walletId == $w->id)>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="f-q" class="label">Cari</label>
                <div class="relative">
                    <x-icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <input id="f-q" type="text" name="q" value="{{ $q }}" placeholder="Catatan / kategori"
                           class="input !pl-8">
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-dark">Terapkan</button>
            @if ($hasFilter)
                <a href="{{ route('transactions.index') }}" class="btn-ghost !border !border-slate-200">Reset</a>
            @endif
        </div>
    </form>

    {{-- Ringkasan hasil filter --}}
    <div class="mb-6 grid grid-cols-3 gap-3">
        <x-stat-card label="Pemasukan" :value="'Rp ' . number_format($summaryIncome, 0, ',', '.')" color="text-cash-600" icon="cash" />
        <x-stat-card label="Pengeluaran" :value="'Rp ' . number_format($summaryExpense, 0, ',', '.')" color="text-cost-600" icon="reports" />
        <x-stat-card label="Selisih" :value="'Rp ' . number_format($net, 0, ',', '.')" :color="$net >= 0 ? 'text-slate-900' : 'text-cost-600'" icon="scale" />
    </div>

    {{-- Daftar transaksi: tabel desktop / card mobile --}}
    @if ($transactions->isEmpty())
        <x-empty-state icon="inbox" title="Tidak ada transaksi"
            description="{{ $hasFilter ? 'Coba ubah filter atau cari kata kunci lain.' : 'Belum ada transaksi tercatat. Mulai dengan mencatat transaksi pertamamu.' }}">
            <button type="button" data-toggle-modal="quick-add" class="btn-primary">
                <x-icon name="plus" class="h-4 w-4" /> Tambah Transaksi
            </button>
        </x-empty-state>
    @else
        <div class="card overflow-hidden">
            <table class="hidden w-full text-sm md:table">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-2.5 font-medium">Tanggal</th>
                        <th class="px-4 py-2.5 font-medium">Tipe</th>
                        <th class="px-4 py-2.5 font-medium">Kategori</th>
                        <th class="px-4 py-2.5 font-medium">Dompet</th>
                        <th class="px-4 py-2.5 text-right font-medium">Nominal</th>
                        <th class="px-4 py-2.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($transactions as $t)
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="whitespace-nowrap px-4 py-3 text-slate-600">{{ $t->date->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $t->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                    {{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($t->category)
                                    <span class="flex items-center gap-1.5">
                                        @if ($t->category->icon)<span class="text-base leading-none">{{ $t->category->icon }}</span>@endif
                                        {{ $t->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                                @if ($t->note)
                                    <span class="mt-0.5 block max-w-[16rem] truncate text-xs text-slate-400" title="{{ $t->note }}">{{ $t->note }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $t->wallet->name }}</td>
                            <td class="money whitespace-nowrap px-4 py-3 text-right font-medium {{ $t->type === 'income' ? 'text-cash-600' : 'text-cost-600' }}">
                                {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('transactions.edit', $t) }}" class="btn-ghost !px-2 !py-1.5" aria-label="Edit" title="Edit">
                                        <x-icon name="pencil" class="h-4 w-4" />
                                    </a>
                                    <form method="POST" action="{{ route('transactions.destroy', $t) }}"
                                          onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ghost !px-2 !py-1.5 !text-cost-600 hover:!bg-cost-50" aria-label="Hapus" title="Hapus">
                                            <x-icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <ul class="divide-y divide-slate-100 md:hidden">
                @foreach ($transactions as $t)
                    <li class="px-4 py-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-lg {{ $t->type === 'income' ? 'bg-cash-50' : 'bg-cost-50' }}">
                                    {{ $t->category->icon ?? ($t->type === 'income' ? '💰' : '💸') }}
                                </span>
                                <div>
                                    <p class="font-medium text-slate-800">
                                        {{ $t->category->name ?? ($t->type === 'income' ? 'Pemasukan' : 'Tanpa kategori') }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $t->date->translatedFormat('d M Y') }} &middot; {{ $t->wallet->name }}
                                    </p>
                                    @if ($t->note)
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $t->note }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="money font-medium {{ $t->type === 'income' ? 'text-cash-600' : 'text-cost-600' }}">
                                    {{ $t->type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </p>
                                <div class="mt-1 flex justify-end gap-2">
                                    <a href="{{ route('transactions.edit', $t) }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">Edit</a>
                                    <form method="POST" action="{{ route('transactions.destroy', $t) }}"
                                          onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-cost-600">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection
