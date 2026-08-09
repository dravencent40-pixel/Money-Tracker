<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Money Tracker</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
            margin: 0;
            padding: 0;
        }
        nav {
            background: #222;
            padding: 12px 20px;
            display: flex;
            gap: 18px;
        }
        nav a {
            color: #eee;
            text-decoration: none;
            font-size: 14px;
        }
        nav a:hover { text-decoration: underline; }
        .container {
            max-width: 720px;
            margin: 24px auto;
            padding: 0 16px;
        }
        h1 { font-size: 20px; margin-bottom: 12px; }
        h2 { font-size: 16px; margin: 20px 0 8px; }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 12px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px 4px; border-bottom: 1px solid #eee; font-size: 14px; }
        .row { display: flex; justify-content: space-between; }
        .muted { color: #777; font-size: 12px; }
        .bar-bg { background: #eee; border-radius: 4px; height: 8px; margin-top: 4px; overflow: hidden; }
        .bar-fill { height: 100%; background: #3b82f6; }
        .bar-fill.over { background: #ef4444; }
        form { display: flex; flex-direction: column; gap: 8px; max-width: 400px; }
        input, select, button {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #222;
            color: #fff;
            border: none;
            cursor: pointer;
            width: fit-content;
            padding: 8px 16px;
        }
        button:hover { background: #444; }
        .status { background: #e6ffed; border: 1px solid #b6f0c2; padding: 8px; border-radius: 4px; margin-bottom: 12px; font-size: 14px; }
        .link-btn { font-size: 13px; color: #b91c1c; cursor: pointer; background: none; border: none; padding: 0; }
        .income { color: #16a34a; }
        .expense { color: #dc2626; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('transactions.index') }}">Transaksi</a>
        <a href="{{ route('categories.index') }}">Kategori</a>
        <a href="{{ route('budgets.index') }}">Budget</a>
    </nav>
    <div class="container">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>
