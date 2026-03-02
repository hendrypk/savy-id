<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToUser
{
    /**
     * Boot the trait to apply global scope and model events.
     */
    protected static function bootBelongsToUser(): void
    {
        // 1. AUTOMATIC FILTER: Every query will automatically include "WHERE user_id = X"
        static::addGlobalScope('user_id', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where($builder->getQuery()->from . '.user_id', Auth::id());
            }
        });

        // 2. AUTOMATIC INSERT: Every time you create a record, user_id is filled automatically
        static::creating(function ($model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }
        });
    }

    /**
     * Helper to bypass the filter (e.g., for Admin panels or internal tasks)
     */
    public function scopeAllTenants($query)
    {
        return $query->withoutGlobalScope('user_id');
    }
}