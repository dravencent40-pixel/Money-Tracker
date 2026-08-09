@extends('layouts.app')

@section('content')
    <h1 class="text-lg font-semibold mb-5">Kategori</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4">
            <h2 class="text-sm font-semibold mb-3">Pengeluaran</h2>
            <div class="space-y-2">
                @foreach ($categories->where('type', 'expense') as $c)
                    <div class="flex justify-between items-center text-sm border-b border-zinc-900 pb-2">
                        <span>{{ $c->name }}</span>
                        <form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-500 hover:text-rose-400">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4">
            <h2 class="text-sm font-semibold mb-3">Pemasukan</h2>
            <div class="space-y-2">
                @foreach ($categories->where('type', 'income') as $c)
                    <div class="flex justify-between items-center text-sm border-b border-zinc-900 pb-2">
                        <span>{{ $c->name }}</span>
                        <form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-500 hover:text-rose-400">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <h2 class="text-sm font-semibold mb-3">Tambah Kategori</h2>
    <form method="POST" action="{{ route('categories.store') }}" class="bg-zinc-950 border border-zinc-900 rounded-lg border border-zinc-900 p-4 max-w-sm space-y-3">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori" required class="w-full rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
        <select name="type" class="w-full rounded-md border-zinc-800 bg-zinc-900 text-zinc-200 text-sm">
            <option value="expense">Pengeluaran</option>
            <option value="income">Pemasukan</option>
        </select>
        <button type="submit" class="bg-amber-500 text-zinc-950 font-medium text-sm px-4 py-2 rounded-md hover:bg-amber-400">Tambah</button>
    </form>
@endsection
