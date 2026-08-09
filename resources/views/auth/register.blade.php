@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <h1 class="mb-5 text-center text-lg font-semibold text-slate-900">Buat akun baru</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="reg-name" class="label">Nama</label>
            <input id="reg-name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   autocomplete="name" placeholder="Nama kamu" class="input">
        </div>

        <div>
            <label for="reg-email" class="label">Email</label>
            <input id="reg-email" type="email" name="email" value="{{ old('email') }}" required
                   autocomplete="email" placeholder="nama@email.com" class="input">
        </div>

        <div>
            <label for="reg-password" class="label">Password</label>
            <div class="relative">
                <input id="reg-password" type="password" name="password" required autocomplete="new-password"
                       placeholder="Minimal 8 karakter" class="input !pr-10">
                <button type="button" data-password-toggle data-target="reg-password"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-600" aria-label="Tampilkan password">
                    <span data-icon-show><x-icon name="eye" class="h-4 w-4" /></span>
                    <span data-icon-hide class="hidden"><x-icon name="eye-off" class="h-4 w-4" /></span>
                </button>
            </div>
        </div>

        <div>
            <label for="reg-password-confirm" class="label">Konfirmasi Password</label>
            <div class="relative">
                <input id="reg-password-confirm" type="password" name="password_confirmation" required
                       autocomplete="new-password" placeholder="Ulangi password" class="input !pr-10">
                <button type="button" data-password-toggle data-target="reg-password-confirm"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-600" aria-label="Tampilkan password">
                    <span data-icon-show><x-icon name="eye" class="h-4 w-4" /></span>
                    <span data-icon-hide class="hidden"><x-icon name="eye-off" class="h-4 w-4" /></span>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full !py-2.5">Daftar</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-500">Masuk</a>
    </p>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const input = document.getElementById(btn.dataset.target);
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.querySelector('[data-icon-show]').classList.toggle('hidden', show);
                    btn.querySelector('[data-icon-hide]').classList.toggle('hidden', !show);
                });
            });
        });
    </script>
@endsection
