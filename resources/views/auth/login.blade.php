@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <h1 class="mb-5 text-center text-lg font-semibold text-slate-900">Masuk ke akunmu</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="login-email" class="label">Email</label>
            <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" placeholder="nama@email.com" class="input">
        </div>

        <div>
            <label for="login-password" class="label">Password</label>
            <div class="relative">
                <input id="login-password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••" class="input !pr-10">
                <button type="button" data-password-toggle data-target="login-password"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-600" aria-label="Tampilkan password">
                    <span data-icon-show><x-icon name="eye" class="h-4 w-4" /></span>
                    <span data-icon-hide class="hidden"><x-icon name="eye-off" class="h-4 w-4" /></span>
                </button>
            </div>
        </div>

        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full !py-2.5">Masuk</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-amber-600 hover:text-amber-500">Daftar</a>
    </p>
@endsection
