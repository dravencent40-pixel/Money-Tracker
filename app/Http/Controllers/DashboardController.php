<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $today = Carbon::now();

        $totalIncome = Transaction::income()->inMonth($month)->sum('amount');
        $totalExpense = Transaction::expense()->inMonth($month)->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Akumulasi pengeluaran per kategori bulan ini, dipakai untuk budget progress.
        $expenseByCategory = Transaction::expense()
            ->inMonth($month)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $budgets = Budget::where('month', $month)->pluck('amount', 'category_id');

        $categories = Category::expense()->orderBy('name')->get();

        $budgetSummary = $categories->map(function ($category) use ($expenseByCategory, $budgets) {
            $spent = (float) ($expenseByCategory[$category->id] ?? 0);
            $limit = isset($budgets[$category->id]) ? (float) $budgets[$category->id] : null;
            $percentage = $limit && $limit > 0 ? round(min(100, ($spent / $limit) * 100), 1) : null;

            return (object) [
                'category' => $category,
                'spent' => $spent,
                'limit' => $limit,
                'percentage' => $percentage,
                'status' => $limit === null
                    ? 'no_budget'
                    : ($spent > $limit ? 'over' : ($percentage >= 80 ? 'warning' : 'ok')),
            ];
        });

        // 5 kategori pengeluaran terbesar bulan ini, untuk ringkasan "Pengeluaran Terbesar".
        $topExpenseCategories = $budgetSummary
            ->filter(fn ($s) => $s->spent > 0)
            ->sortByDesc('spent')
            ->take(5)
            ->values();

        // Breakdown pengeluaran untuk donut chart.
        $expenseBreakdown = $topExpenseCategories->take(6);

        $wallets = Wallet::allWithBalance();
        $totalBalance = $wallets->sum('current_balance');

        // Komposisi saldo per dompet (untuk bar proporsi di ringkasan).
        $walletComposition = $wallets->map(fn ($w) => (object) [
            'wallet' => $w,
            'percentage' => $totalBalance > 0 ? round(($w->current_balance / $totalBalance) * 100, 1) : 0,
        ])->filter(fn ($w) => $w->wallet->current_balance != 0)->values();

        // Tren arus kas 5 bulan terakhir — 1 query untuk seluruh jendela.
        $trendMonths = collect(range(4, 0))->map(fn ($i) => $today->copy()->subMonths($i));
        $rows = Transaction::whereBetween('date', [
            $trendMonths->first()->startOfMonth(),
            $trendMonths->last()->endOfMonth(),
        ])->get(['date', 'type', 'amount'])->groupBy(fn ($t) => $t->date->format('Y-m'));

        $trend = $trendMonths->map(function ($m) use ($rows) {
            $group = $rows[$m->format('Y-m')] ?? collect();

            return [
                'label' => $m->translatedFormat('M'),
                'income' => (float) $group->where('type', 'income')->sum('amount'),
                'expense' => (float) $group->where('type', 'expense')->sum('amount'),
            ];
        });

        // Rata-rata pengeluaran harian bulan ini.
        $daysElapsed = max(1, $today->day);
        $avgDailyExpense = $totalExpense > 0 ? $totalExpense / $daysElapsed : 0;

        $recentTransactions = Transaction::with(['category', 'wallet'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $hasAnyData = Transaction::exists() || Wallet::exists();

        return view('dashboard.index', [
            'month' => $month,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'budgetSummary' => $budgetSummary,
            'topExpenseCategories' => $topExpenseCategories,
            'expenseBreakdown' => $expenseBreakdown,
            'wallets' => $wallets,
            'totalBalance' => $totalBalance,
            'walletComposition' => $walletComposition,
            'trend' => $trend,
            'avgDailyExpense' => $avgDailyExpense,
            'recentTransactions' => $recentTransactions,
            'hasAnyData' => $hasAnyData,
        ]);
    }
}
