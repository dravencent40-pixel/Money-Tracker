<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Batasi query hanya ke data milik user tertentu (default: user yang login).
     */
    public function scopeForUser(Builder $query, ?int $userId = null): Builder
    {
        return $query->where($query->qualifyColumn('user_id'), $userId ?? auth()->id());
    }

    /**
     * Selalu batasi ke user yang sedang login selama ada sesi autentikasi.
     * Scope dinonaktifkan otomatis di luar request web (mis. seeder / tinker).
     */
    protected static function bootBelongsToUser(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where($builder->qualifyColumn('user_id'), auth()->id());
            }
        });
    }
}
