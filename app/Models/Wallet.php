<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use BelongsToUser;

    protected $fillable = ['user_id', 'name', 'type', 'starting_balance'];

    protected $casts = [
        'starting_balance' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Saldo berjalan = saldo awal + total pemasukan - total pengeluaran di wallet ini.
     *
     * Untuk mencegah N+1, muat agregat via withSum:
     *   Wallet::withSum(['transactions as total_income' => fn ($q) => $q->where('type', 'income')], 'amount')
     *          ->withSum(['transactions as total_expense' => fn ($q) => $q->where('type', 'expense')], 'amount')
     */
    public function getCurrentBalanceAttribute(): float
    {
        $income = $this->total_income ?? $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->total_expense ?? $this->transactions()->where('type', 'expense')->sum('amount');

        return (float) $this->starting_balance + (float) $income - (float) $expense;
    }

    /**
     * Muat semua dompet beserta saldo berjalan dalam jumlah query konstan.
     */
    public static function allWithBalance()
    {
        return self::query()
            ->withSum(['transactions as total_income' => fn ($q) => $q->where('type', 'income')], 'amount')
            ->withSum(['transactions as total_expense' => fn ($q) => $q->where('type', 'expense')], 'amount')
            ->orderBy('name')
            ->get();
    }
}
