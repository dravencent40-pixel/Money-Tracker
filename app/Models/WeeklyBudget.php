<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyBudget extends Model
{
    protected $fillable = ['category_id', 'week_start_date', 'amount'];

    protected $casts = [
        'week_start_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
