@extends('layouts.guest')

@section('content')
    <h1 class="text-lg font-semibold text-center mb-5">Buat akun baru</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-md border-slate-300 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full rounded-md border-slate-300 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Password</label>
            <input type="password" name="password" required class="w-full rounded-md border-slate-300 text-sm">
            <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-md border-slate-300 text-sm">
        </div>

        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-zinc-950 font-medium text-sm px-4 py-2.5 rounded-md">
            Daftar
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-5">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-amber-600 font-medium hover:text-amber-500">Masuk</a>
    </p>
@endsection
