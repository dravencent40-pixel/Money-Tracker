<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CashFlow') · CashFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans min-h-screen">
    <div class="min-h-screen lg:flex">
        {{-- Sidebar (desktop) --}}
        <aside class="hidden lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-60 lg:shrink-0 lg:flex-col lg:border-r lg:border-slate-200 lg:bg-white">
            <div class="flex items-center gap-2 px-5 pt-6">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-ink-950 text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><line x1="2" y1="12" x2="22" y2="12"/>
                    </svg>
                </span>
                <span class="text-lg font-semibold tracking-tight text-slate-900">CashFlow</span>
            </div>

            <button type="button" data-toggle-modal="quick-add"
                    class="btn-primary mx-5 mt-6 w-[calc(100%-2.5rem)]">
                <x-icon name="plus" class="h-4 w-4" /> Tambah Transaksi
            </button>

            <nav class="mt-6 flex-1 space-y-1 px-3">
                @php
                    $nav = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                        ['route' => 'transactions.index', 'label' => 'Transaksi', 'icon' => 'transactions'],
                        ['route' => 'categories.index', 'label' => 'Kategori', 'icon' => 'categories'],
                        ['route' => 'wallets.index', 'label' => 'Dompet', 'icon' => 'wallet'],
                        ['route' => 'budgets.index', 'label' => 'Budget', 'icon' => 'budget'],
                        ['route' => 'reports.index', 'label' => 'Laporan', 'icon' => 'reports'],
                    ];
                    $active = fn ($name) => request()->routeIs(str_replace('.index', '', $name) . '*') || request()->routeIs($name);
                @endphp
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ $active($item['route']) ? 'nav-link-active' : 'nav-link-idle' }}">
                        <x-icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-slate-100 px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-100 text-sm font-semibold text-ink-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 transition hover:text-slate-700" aria-label="Keluar" title="Keluar">
                            <x-icon name="logout" class="h-5 w-5" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Topbar (mobile) --}}
            <header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-slate-200 bg-white/90 px-4 backdrop-blur lg:hidden">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-ink-950 text-white">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><line x1="2" y1="12" x2="22" y2="12"/>
                        </svg>
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-slate-900">CashFlow</span>
                </a>
                <button type="button" data-toggle-modal="quick-add" class="btn-primary !px-3 !py-1.5">
                    <x-icon name="plus" class="h-4 w-4" /> <span class="hidden sm:inline">Transaksi</span>
                </button>
            </header>

            <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-6 pb-28 sm:px-6 lg:px-8 lg:pb-8">
                @if (session('status'))
                    <x-flash type="status">{{ session('status') }}</x-flash>
                @endif
                @if (session('error'))
                    <x-flash type="error">{{ session('error') }}</x-flash>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Bottom nav (mobile) --}}
    <nav class="fixed inset-x-0 bottom-0 z-30 border-t border-slate-200 bg-white lg:hidden" aria-label="Navigasi utama">
        <div class="grid grid-cols-5">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] {{ request()->routeIs('dashboard') ? 'font-semibold text-ink-950' : 'text-slate-400' }}">
                <x-icon name="dashboard" class="h-5 w-5" /> Dashboard
            </a>
            <a href="{{ route('transactions.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] {{ request()->routeIs('transactions*') ? 'font-semibold text-ink-950' : 'text-slate-400' }}">
                <x-icon name="transactions" class="h-5 w-5" /> Transaksi
            </a>
            <div class="relative flex justify-center">
                <button type="button" data-toggle-modal="quick-add" aria-label="Tambah transaksi"
                        class="absolute -top-5 flex h-14 w-14 items-center justify-center rounded-full bg-amber-500 text-zinc-950 shadow-lg shadow-amber-500/30 transition hover:bg-amber-400 active:scale-95">
                    <x-icon name="plus" class="h-6 w-6" />
                </button>
            </div>
            <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] {{ request()->routeIs('reports*') ? 'font-semibold text-ink-950' : 'text-slate-400' }}">
                <x-icon name="reports" class="h-5 w-5" /> Laporan
            </a>
            <button type="button" data-toggle-modal="menu-sheet" class="flex flex-col items-center gap-0.5 py-2.5 text-[11px] text-slate-400">
                <x-icon name="menu" class="h-5 w-5" /> Menu
            </button>
        </div>
    </nav>

    {{-- Modal quick-add --}}
    <div id="quick-add" class="fixed inset-0 z-40 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-ink-950/50 backdrop-blur-sm" data-close-modal></div>
        <div class="absolute inset-x-0 bottom-0 max-h-[92vh] overflow-y-auto rounded-t-2xl bg-white p-5 shadow-xl sm:inset-x-auto sm:bottom-auto sm:left-1/2 sm:top-1/2 sm:w-full sm:max-w-md sm:-translate-x-1/2 sm:-translate-y-1/2 sm:rounded-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Tambah Transaksi</h2>
                <button type="button" data-close-modal class="btn-ghost !px-2 !py-1" aria-label="Tutup">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            @include('transactions._form', [
                'action' => route('transactions.store'),
                'method' => 'POST',
                'transaction' => null,
                'redirect' => route('dashboard'),
                'wallets' => $quickAddWallets ?? [],
                'categories' => $quickAddCategories ?? [],
                'compact' => true,
            ])
        </div>
    </div>

    {{-- Bottom sheet menu --}}
    <div id="menu-sheet" class="fixed inset-0 z-40 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-ink-950/50 backdrop-blur-sm" data-close-modal></div>
        <div class="absolute inset-x-0 bottom-0 rounded-t-2xl bg-white p-5 pb-8 shadow-xl sm:mx-auto sm:max-w-sm">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-900">Menu</h2>
                <button type="button" data-close-modal class="btn-ghost !px-2 !py-1" aria-label="Tutup">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-1">
                @foreach ([
                    ['route' => 'categories.index', 'label' => 'Kategori', 'icon' => 'categories', 'desc' => 'Kelola jenis pemasukan & pengeluaran'],
                    ['route' => 'wallets.index', 'label' => 'Dompet', 'icon' => 'wallet', 'desc' => 'Cash, bank, e-wallet'],
                    ['route' => 'budgets.index', 'label' => 'Budget', 'icon' => 'budget', 'desc' => 'Atur limit pengeluaran per kategori'],
                ] as $m)
                    <a href="{{ route($m['route']) }}" class="flex items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-slate-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                            <x-icon :name="$m['icon']" class="h-5 w-5" />
                        </span>
                        <span>
                            <span class="block text-sm font-medium text-slate-800">{{ $m['label'] }}</span>
                            <span class="block text-xs text-slate-400">{{ $m['desc'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = (el, force) => {
                const hidden = force !== undefined ? force : el.classList.contains('hidden');
                el.classList.toggle('hidden', hidden);
                document.body.classList.toggle('overflow-hidden', !hidden);
                if (!hidden) {
                    el.querySelector('input, select, button')?.focus?.();
                }
            };

            document.querySelectorAll('[data-toggle-modal]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const el = document.getElementById(btn.dataset.toggleModal);
                    if (el) toggle(el, el.classList.contains('hidden'));
                });
            });
            document.querySelectorAll('[data-close-modal]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('[role="dialog"]');
                    if (modal) toggle(modal, true);
                });
            });

            document.querySelectorAll('[data-month-picker]').forEach((picker) => {
                picker.addEventListener('change', () => picker.form.submit());
            });

            document.querySelectorAll('[data-flash]').forEach((flash) => {
                const close = flash.querySelector('[data-flash-close]');
                close?.addEventListener('click', () => flash.remove());
                setTimeout(() => flash.classList.add('opacity-0', 'transition-opacity', 'duration-500'), 5000);
                setTimeout(() => flash.remove(), 5600);
            });
        });
    </script>
</body>
</html>
