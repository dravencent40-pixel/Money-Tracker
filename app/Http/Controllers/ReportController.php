<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        $totalIncome = Transaction::income()->inMonth($month)->sum('amount');
        $totalExpense = Transaction::expense()->inMonth($month)->sum('amount');

        $byCategory = Transaction::expense()
            ->inMonth($month)
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as jumlah_transaksi')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        $transactions = Transaction::with(['category', 'wallet'])
            ->inMonth($month)
            ->orderByDesc('date')
            ->get();

        return view('reports.index', compact('month', 'totalIncome', 'totalExpense', 'byCategory', 'transactions'));
    }
}
