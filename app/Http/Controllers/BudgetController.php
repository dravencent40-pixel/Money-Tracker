<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\WeeklyBudget;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));
        $weekStart = $request->query('week', Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString());

        $categories = Category::orderBy('name')->get();

        $monthlyBudgets = Budget::where('month', $month)->pluck('amount', 'category_id');
        $weeklyBudgets = WeeklyBudget::where('week_start_date', $weekStart)->pluck('amount', 'category_id');

        return view('budgets.index', compact('categories', 'monthlyBudgets', 'weeklyBudgets', 'month', 'weekStart'));
    }

    public function storeMonthly(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'amounts' => 'array',
            'amounts.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['amounts'] ?? [] as $categoryId => $amount) {
            if ($amount === null || $amount === '') {
                Budget::where('category_id', $categoryId)->where('month', $validated['month'])->delete();
                continue;
            }

            Budget::updateOrCreate(
                ['category_id' => $categoryId, 'month' => $validated['month']],
                ['amount' => $amount]
            );
        }

        return redirect()->route('budgets.index', ['month' => $validated['month']])
            ->with('status', 'Budget bulanan disimpan.');
    }

    public function storeWeekly(Request $request)
    {
        $validated = $request->validate([
            'week_start_date' => 'required|date',
            'amounts' => 'array',
            'amounts.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['amounts'] ?? [] as $categoryId => $amount) {
            if ($amount === null || $amount === '') {
                WeeklyBudget::where('category_id', $categoryId)
                    ->where('week_start_date', $validated['week_start_date'])
                    ->delete();
                continue;
            }

            WeeklyBudget::updateOrCreate(
                ['category_id' => $categoryId, 'week_start_date' => $validated['week_start_date']],
                ['amount' => $amount]
            );
        }

        return redirect()->route('budgets.index', ['week' => $validated['week_start_date']])
            ->with('status', 'Budget mingguan disimpan.');
    }
}
