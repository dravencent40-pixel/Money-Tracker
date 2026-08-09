@extends('layouts.app')

@section('content')
    <h1>Edit Transaksi</h1>

    <form method="POST" action="{{ route('transactions.update', $transaction) }}">
        @csrf
        @method('PUT')

        <label>Tipe</label>
        <select name="type" id="type" required>
            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>Pemasukan</option>
        </select>

        <div id="category-field">
            <label>Kategori</label>
            <select name="category_id">
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ $transaction->category_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <label>Nominal (Rp)</label>
        <input type="number" name="amount" step="0.01" min="0" value="{{ $transaction->amount }}" required>

        <label>Tanggal</label>
        <input type="date" name="date" value="{{ $transaction->date->toDateString() }}" required>

        <label>Catatan (opsional)</label>
        <input type="text" name="note" value="{{ $transaction->note }}">

        <button type="submit">Simpan Perubahan</button>
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
