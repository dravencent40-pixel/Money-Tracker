<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CashFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen antialiased">
    <header class="border-b border-slate-200 sticky top-0 z-10 bg-white/90 backdrop-blur">
        <div class="max-w-5xl mx-auto px-5 flex items-center justify-between h-14">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full border-2 border-[#D2042D] flex items-center justify-center text-[#D2042D] font-bold text-sm leading-none">C</span>
                <span class="font-semibold tracking-tight text-lg text-slate-900"><span class="text-[#D2042D]">C</span>ashFlow</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm text-slate-500">{{ auth()->user()->name }}</span>
                <a href="{{ route('transactions.create') }}"
                   class="bg-amber-500 hover:bg-amber-400 active:scale-[0.97] transition text-zinc-950 text-sm font-medium px-4 py-2 rounded-lg">
                    + Transaksi
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-400 hover:text-slate-600">Keluar</button>
                </form>
            </div>
        </div>
        <div class="max-w-5xl mx-auto px-5 flex items-center gap-5 text-sm overflow-x-auto">
            @php
                $links = [
                    'dashboard' => 'Dashboard',
                    'transactions.index' => 'Transaksi',
                    'categories.index' => 'Kategori',
                    'wallets.index' => 'Dompet',
                    'budgets.index' => 'Budget',
                    'reports.index' => 'Laporan',
                ];
            @endphp
            @foreach ($links as $routeName => $label)
                @php $active = request()->routeIs(str_replace('.index','',$routeName).'*') || request()->routeIs($routeName); @endphp
                <a href="{{ route($routeName) }}"
                   class="pb-2.5 pt-1 border-b-2 whitespace-nowrap {{ $active ? 'border-amber-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 py-6">
        @if (session('status'))
            <div class="mb-4 text-sm text-emerald-700 border border-emerald-200 bg-emerald-50 rounded-lg px-4 py-2.5">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-rose-700 border border-rose-200 bg-rose-50 rounded-lg px-4 py-2.5">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
