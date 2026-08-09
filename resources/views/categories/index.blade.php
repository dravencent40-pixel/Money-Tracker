@extends('layouts.app')

@section('content')
    <h1>Kategori</h1>

    <div class="card">
        <table>
            <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('categories.destroy', $c) }}" style="display:inline;" onsubmit="return confirm('Hapus kategori ini? Transaksi terkait akan kehilangan kategorinya.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="link-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2>Tambah Kategori Baru</h2>
    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori" required>
        <button type="submit">Tambah</button>
    </form>
    @error('name')
        <p style="color:#dc2626; font-size:13px;">{{ $message }}</p>
    @enderror
@endsection
