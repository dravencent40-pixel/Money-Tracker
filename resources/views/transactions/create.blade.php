@extends('layouts.app')

@section('content')
    <h1>Tambah Transaksi</h1>

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf

        <label>Tipe</label>
        <select name="type" id="type" required>
            <option value="expense">Pengeluaran</option>
            <option value="income">Pemasukan</option>
        </select>

        <div id="category-field">
            <label>Kategori</label>
            <select name="category_id">
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <label>Nominal (Rp)</label>
        <input type="number" name="amount" step="0.01" min="0" required>

        <label>Tanggal</label>
        <input type="date" name="date" value="{{ now()->toDateString() }}" required>

        <label>Catatan (opsional)</label>
        <input type="text" name="note">

        <button type="submit">Simpan</button>
    </form>

    <script>
        const typeEl = document.getElementById('type');
        const catField = document.getElementById('category-field');
        function toggleCategory() {
            catField.style.display = typeEl.value === 'income' ? 'none' : 'block';
        }
        typeEl.addEventListener('change', toggleCategory);
        toggleCategory();
    </script>
@endsection
