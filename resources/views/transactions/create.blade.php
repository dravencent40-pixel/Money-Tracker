@extends('layouts.app')

@section('content')
    <h1 class="text-lg font-semibold mb-5">Tambah Transaksi</h1>

    @include('transactions._form', ['action' => route('transactions.store'), 'method' => 'POST', 'transaction' => null])
@endsection
