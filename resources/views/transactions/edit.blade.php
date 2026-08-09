@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <x-page-header title="Edit Transaksi" subtitle="Perbarui detail transaksi" />

    <div class="max-w-md">
        @include('transactions._form', [
            'action' => route('transactions.update', $transaction),
            'method' => 'PUT',
            'transaction' => $transaction,
            'uid' => 'edit',
            'wallets' => $wallets,
            'categories' => $categories,
        ])
    </div>
@endsection
