<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['name', 'type', 'starting_balance'];

    protected $casts = [
        'starting_balance' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Saldo berjalan = saldo awal + total pemasukan - total pengeluaran di wallet ini.
     */
    public function getCurrentBalanceAttribute(): float
    {
        $income = $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->transactions()->where('type', 'expense')->sum('amount');

        return (float) $this->starting_balance + (float) $income - (float) $expense;
    }
}
