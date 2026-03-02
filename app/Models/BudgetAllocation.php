<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAllocation extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'uuid', 'user_id', 'transaction_category_id', 
        'plan_amount', 'month_year'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    public function transactions(): HasMany {
        return $this->hasMany(WalletTransaction::class, 'budget_allocation_id');
    }

    public function getUsedAmountAttribute() {
        return $this->transactions()->sum('amount');
    }
}