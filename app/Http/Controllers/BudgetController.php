<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        $categories = Category::expense()->orderBy('name')->get();
        $budgets = Budget::where('month', $month)->pluck('amount', 'category_id');

        if ($request->query('copy') === 'prev') {
            $prevMonth = Carbon::createFromFormat('Y-m', $month)->subMonth()->format('Y-m');
            $prev = Budget::where('month', $prevMonth)->pluck('amount', 'category_id');
            if ($prev->isNotEmpty()) {
                $budgets = $prev;
                session()->flash('status', 'Budget bulan '.$prevMonth.' disalin. Tekan "Simpan Budget" untuk menerapkannya.');
            } else {
                session()->flash('error', 'Tidak ada budget di bulan sebelumnya untuk disalin.');
            }
        }

        $spent = Transaction::expense()
            ->inMonth($month)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $summary = $categories->reduce(function ($carry, $category) use ($spent, $budgets) {
            $carry['budget'] += (float) ($budgets[$category->id] ?? 0);
            $carry['spent'] += (float) ($spent[$category->id] ?? 0);

            return $carry;
        }, ['budget' => 0.0, 'spent' => 0.0]);

        return view('budgets.index', compact('categories', 'budgets', 'month', 'spent', 'summary'));
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
                ['category_id' => $categoryId, 'month' => $validated['month'], 'user_id' => auth()->id()],
                ['amount' => $amount]
            );
        }

        return redirect()->route('budgets.index', ['month' => $validated['month']])
            ->with('status', 'Budget bulan '.$validated['month'].' disimpan.');
    }
}
