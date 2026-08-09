<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('category')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validated['type'] === 'income') {
            $validated['category_id'] = null;
        }

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('status', 'Transaksi ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validated['type'] === 'income') {
            $validated['category_id'] = null;
        }

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('status', 'Transaksi diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('status', 'Transaksi dihapus.');
    }
}
