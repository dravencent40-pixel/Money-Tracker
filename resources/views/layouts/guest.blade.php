<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CashFlow') · CashFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-10 font-sans">
    <div class="w-full max-w-sm">
        <div class="mb-6 flex items-center justify-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-ink-950 text-white">
                <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"/><line x1="2" y1="12" x2="22" y2="12"/>
                </svg>
            </span>
            <span class="text-xl font-semibold tracking-tight text-slate-900">CashFlow</span>
        </div>

        <div class="card p-6">
            @if ($errors->any())
                <div class="mb-4 flex items-start gap-2 rounded-xl border border-cost-200 bg-cost-50 px-4 py-3 text-sm text-cost-700">
                    <x-icon name="alert" class="mt-0.5 h-4 w-4 shrink-0" />
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            Atur keuanganmu lebih santai — catat, pantau, dan rencanakan.
        </p>
    </div>

    @yield('scripts')
</body>
</html>
