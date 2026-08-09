<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month');
        $type = $request->query('type');
        $categoryId = $request->query('category_id');
        $walletId = $request->query('wallet_id');
        $search = trim((string) $request->query('q'));

        $query = Transaction::with(['category', 'wallet'])
            ->when($month, fn ($q) => $q->inMonth($month))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($walletId, fn ($q) => $q->where('wallet_id', $walletId))
            ->when($search !== '', fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->whereRaw('LOWER(note) LIKE ?', ['%'.mb_strtolower($search).'%'])
                    ->orWhereHas('category', fn ($cq) => $cq->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($search).'%']));
            }));

        $summary = (clone $query)
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'income' THEN amount END), 0) as income")
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'expense' THEN amount END), 0) as expense")
            ->first();

        $transactions = $query
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'month' => $month,
            'type' => $type,
            'categoryId' => $categoryId,
            'walletId' => $walletId,
            'q' => $search,
            'summaryIncome' => (float) $summary->income,
            'summaryExpense' => (float) $summary->expense,
            'categories' => Category::orderBy('type')->orderBy('name')->get(),
            'wallets' => Wallet::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('transactions.create', [
            'categories' => Category::orderBy('type')->orderBy('name')->get(),
            'wallets' => Wallet::allWithBalance(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Transaction::create($validated + ['user_id' => auth()->id()]);

        $target = $request->input('redirect') === route('dashboard')
            ? route('dashboard')
            : route('transactions.index');

        return redirect($target)->with('status', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::orderBy('type')->orderBy('name')->get(),
            'wallets' => Wallet::allWithBalance(),
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
            'wallet_id' => ['required', Rule::exists('wallets', 'id')->where('user_id', auth()->id())],
            'type' => 'required|in:income,expense',
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
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
