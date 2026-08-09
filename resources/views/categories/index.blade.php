@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    @php
        $icons = ['🍜', '🍔', '☕', '🛒', '🚗', '⛽', '🏠', '💡', '📱', '📺', '🧾', '🎁', '👕', '💊', '🎓', '📦', '💰', '💵', '🏦', '💳', '📈', '💼'];
        $list = fn ($type) => $categories->where('type', $type);
    @endphp

    <x-page-header title="Kategori" subtitle="Kelola jenis pemasukan & pengeluaran" />

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        {{-- Pengeluaran --}}
        <section class="card p-5">
            <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
                <span class="h-2 w-2 rounded-full bg-cost-500"></span> Pengeluaran
            </h2>
            @if ($list('expense')->isEmpty())
                <p class="py-6 text-center text-sm text-slate-400">Belum ada kategori pengeluaran.</p>
            @else
                <div class="space-y-1">
                    @foreach ($list('expense') as $c)
                        <div class="group flex items-center justify-between rounded-lg px-2 py-2 transition hover:bg-slate-50">
                            <span class="flex min-w-0 items-center gap-2 text-sm">
                                <span class="text-lg leading-none">{{ $c->icon ?? '🏷️' }}</span>
                                <span class="truncate">{{ $c->name }}</span>
                            </span>
                            <form method="POST" action="{{ route('categories.destroy', $c) }}"
                                  onsubmit="return confirm('Hapus kategori ini? Transaksi lama di dalamnya akan menjadi tanpa kategori.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ghost !px-2 !py-1 !text-cost-600 hover:!bg-cost-50 opacity-0 transition group-hover:opacity-100 max-md:opacity-100" aria-label="Hapus {{ $c->name }}" title="Hapus">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Pemasukan --}}
        <section class="card p-5">
            <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
                <span class="h-2 w-2 rounded-full bg-cash-500"></span> Pemasukan
            </h2>
            @if ($list('income')->isEmpty())
                <p class="py-6 text-center text-sm text-slate-400">Belum ada kategori pemasukan.</p>
            @else
                <div class="space-y-1">
                    @foreach ($list('income') as $c)
                        <div class="group flex items-center justify-between rounded-lg px-2 py-2 transition hover:bg-slate-50">
                            <span class="flex min-w-0 items-center gap-2 text-sm">
                                <span class="text-lg leading-none">{{ $c->icon ?? '🏷️' }}</span>
                                <span class="truncate">{{ $c->name }}</span>
                            </span>
                            <form method="POST" action="{{ route('categories.destroy', $c) }}"
                                  onsubmit="return confirm('Hapus kategori ini? Transaksi lama di dalamnya akan menjadi tanpa kategori.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-ghost !px-2 !py-1 !text-cost-600 hover:!bg-cost-50 opacity-0 transition group-hover:opacity-100 max-md:opacity-100" aria-label="Hapus {{ $c->name }}" title="Hapus">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    {{-- Tambah kategori --}}
    <h2 class="mb-3 text-sm font-semibold text-slate-700">Tambah Kategori</h2>
    <form method="POST" action="{{ route('categories.store') }}" class="card max-w-md space-y-3 p-5">
        @csrf

        <div>
            <label for="c-name" class="label">Nama kategori</label>
            <input id="c-name" type="text" name="name" placeholder="mis. Nonton bioskop" required value="{{ old('name') }}" class="input">
            @error('name')
                <p class="mt-1 text-xs text-cost-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <span class="label">Tipe</span>
            <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1" role="radiogroup" aria-label="Tipe kategori">
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="expense" class="peer sr-only" @checked(old('type', 'expense') === 'expense')>
                    <span class="block rounded-lg px-3 py-2 text-center text-sm font-medium text-slate-500 transition peer-checked:bg-white peer-checked:text-cost-600 peer-checked:shadow-sm">Pengeluaran</span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="type" value="income" class="peer sr-only" @checked(old('type') === 'income')>
                    <span class="block rounded-lg px-3 py-2 text-center text-sm font-medium text-slate-500 transition peer-checked:bg-white peer-checked:text-cash-600 peer-checked:shadow-sm">Pemasukan</span>
                </label>
            </div>
        </div>

        <div>
            <span class="label">Ikon (opsional)</span>
            <div class="flex flex-wrap gap-1.5">
                @foreach ($icons as $icon)
                    <label class="cursor-pointer" title="{{ $icon }}">
                        <input type="radio" name="icon" value="{{ $icon }}" class="peer sr-only" @checked(old('icon') === $icon)>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-lg leading-none transition peer-checked:bg-ink-950 peer-checked:ring-2 peer-checked:ring-ink-950 peer-checked:ring-offset-2">{{ $icon }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn-primary w-full">Tambah Kategori</button>
    </form>
@endsection
