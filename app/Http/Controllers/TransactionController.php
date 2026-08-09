<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month');

        $transactions = Transaction::with(['category', 'wallet'])
            ->when($month, fn ($q) => $q->inMonth($month))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'month'));
    }

    public function create()
    {
        return view('transactions.create', [
            'categories' => Category::orderBy('type')->orderBy('name')->get(),
            'wallets' => Wallet::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::orderBy('type')->orderBy('name')->get(),
            'wallets' => Wallet::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $this->validated($request);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('status', 'Transaksi dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        // Pastikan kategori yang dipilih sesuai tipe transaksi (income tidak boleh pakai kategori expense, dst).
        if ($validated['category_id']) {
            $category = Category::find($validated['category_id']);
            if ($category && $category->type !== $validated['type']) {
                $validated['category_id'] = null;
            }
        }

        return $validated;
    }
}
