<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\WeeklyBudget;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $month = $today->format('Y-m');
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $totalIncome = Transaction::where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $totalExpense = Transaction::where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $categories = Category::orderBy('name')->get();

        $summary = $categories->map(function ($category) use ($startOfMonth, $endOfMonth, $startOfWeek, $endOfWeek, $month) {
            $monthlyBudget = Budget::where('category_id', $category->id)
                ->where('month', $month)
                ->value('amount');

            $monthlySpent = Transaction::where('category_id', $category->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $weeklyBudget = WeeklyBudget::where('category_id', $category->id)
                ->where('week_start_date', $startOfWeek->toDateString())
                ->value('amount');

            $weeklySpent = Transaction::where('category_id', $category->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->sum('amount');

            return (object) [
                'category' => $category,
                'monthly_budget' => $monthlyBudget,
                'monthly_spent' => $monthlySpent,
                'weekly_budget' => $weeklyBudget,
                'weekly_spent' => $weeklySpent,
            ];
        });

        return view('dashboard.index', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'summary' => $summary,
            'month' => $month,
            'weekLabel' => $startOfWeek->translatedFormat('d M') . ' - ' . $endOfWeek->translatedFormat('d M'),
        ]);
    }
}
