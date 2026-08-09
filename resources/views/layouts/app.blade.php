<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dompetku</title>
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
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen antialiased">
    <header class="border-b border-zinc-900 sticky top-0 z-10 bg-zinc-950/90 backdrop-blur">
        <div class="max-w-5xl mx-auto px-5 flex items-center justify-between h-14">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-4 bg-amber-500 rounded-sm"></span>
                <span class="font-semibold tracking-tight">Dompetku</span>
            </div>
            <a href="{{ route('transactions.create') }}"
               class="bg-amber-500 hover:bg-amber-400 active:scale-[0.97] transition text-zinc-950 text-sm font-medium px-4 py-2 rounded-lg">
                + Transaksi
            </a>
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
                   class="pb-2.5 pt-1 border-b-2 whitespace-nowrap {{ $active ? 'border-amber-500 text-zinc-50' : 'border-transparent text-zinc-500 hover:text-zinc-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-5 py-6">
        @if (session('status'))
            <div class="mb-4 text-sm text-emerald-400 border border-emerald-900 bg-emerald-950/40 rounded-lg px-4 py-2.5">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-rose-400 border border-rose-900 bg-rose-950/40 rounded-lg px-4 py-2.5">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
