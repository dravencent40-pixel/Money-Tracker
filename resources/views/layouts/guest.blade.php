<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CashFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Outfit', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="flex items-center justify-center gap-2 mb-6">
            <span class="w-8 h-8 rounded-full border-2 border-[#D2042D] flex items-center justify-center text-[#D2042D] font-bold text-base leading-none">C</span>
            <span class="font-semibold tracking-tight text-xl text-slate-900"><span class="text-[#D2042D]">C</span>ashFlow</span>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            @if ($errors->any())
                <div class="mb-4 text-sm text-rose-700 border border-rose-200 bg-rose-50 rounded-lg px-4 py-2.5">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
