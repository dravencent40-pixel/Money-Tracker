@extends('layouts.guest')

@section('content')
    <h1 class="text-lg font-semibold text-center mb-5">Masuk ke akunmu</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-md border-slate-300 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Password</label>
            <input type="password" name="password" required class="w-full rounded-md border-slate-300 text-sm">
        </div>

        <label class="flex items-center gap-2 text-xs text-slate-500">
            <input type="checkbox" name="remember" class="rounded border-slate-300">
            Ingat saya
        </label>

        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-zinc-950 font-medium text-sm px-4 py-2.5 rounded-md">
            Masuk
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-5">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-amber-600 font-medium hover:text-amber-500">Daftar</a>
    </p>
@endsection
