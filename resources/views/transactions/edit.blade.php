@extends('layouts.app')

@section('content')
    <h1 class="text-lg font-semibold mb-5">Edit Transaksi</h1>

    @include('transactions._form', ['action' => route('transactions.update', $transaction), 'method' => 'PUT', 'transaction' => $transaction])
@endsection
