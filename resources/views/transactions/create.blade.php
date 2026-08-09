@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <x-page-header title="Tambah Transaksi" subtitle="Catat pemasukan atau pengeluaran baru" />

    <div class="max-w-md">
        @include('transactions._form', [
            'action' => route('transactions.store'),
            'method' => 'POST',
            'transaction' => null,
            'uid' => 'create',
            'wallets' => $wallets,
            'categories' => $categories,
        ])
    </div>
@endsection
