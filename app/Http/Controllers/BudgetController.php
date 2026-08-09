<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        $categories = Category::expense()->orderBy('name')->get();
        $budgets = Budget::where('month', $month)->pluck('amount', 'category_id');

        return view('budgets.index', compact('categories', 'budgets', 'month'));
    }

    public function store(Request $request)
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
            ->with('status', 'Budget bulan ' . $validated['month'] . ' disimpan.');
    }
}
