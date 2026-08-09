<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::allWithBalance();

        return view('wallets.index', compact('wallets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,ewallet',
            'starting_balance' => 'nullable|numeric|min:0',
        ]);

        Wallet::create($validated + ['user_id' => auth()->id()]);

        return redirect()->route('wallets.index')->with('status', 'Dompet ditambahkan.');
    }

    public function destroy(Wallet $wallet)
    {
        if ($wallet->transactions()->exists()) {
            return redirect()->route('wallets.index')
                ->with('error', 'Dompet tidak bisa dihapus karena masih punya riwayat transaksi.');
        }

        $wallet->delete();

        return redirect()->route('wallets.index')->with('status', 'Dompet dihapus.');
    }
}
