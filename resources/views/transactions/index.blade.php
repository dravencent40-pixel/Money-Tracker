@extends('layouts.app')

@section('content')
    <div class="row">
        <h1>Transaksi</h1>
        <a href="{{ route('transactions.create') }}"><button>+ Tambah</button></a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Catatan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->date->format('d/m/Y') }}</td>
                    <td class="{{ $t->type }}">{{ $t->type === 'income' ? 'Masuk' : 'Keluar' }}</td>
                    <td>{{ $t->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td>{{ $t->note }}</td>
                    <td>
                        <a href="{{ route('transactions.edit', $t) }}">Edit</a>
                        <form method="POST" action="{{ route('transactions.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">{{ $transactions->links() }}</div>
@endsection
