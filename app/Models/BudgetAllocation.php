<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAllocation extends Model
{
    use SoftDeletes, BelongsToUser;
    
    protected $fillable = [
        'uuid', 
        'user_id', 
        'transaction_category_id', 
        'loan_id', 
        'plan_amount', 
        'month_year',
        'is_settled'
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    public function transactions()
    {
        return $this->morphMany(WalletTransaction::class, 'reference');
    }

    public function getUsedAmountAttribute() {
        return $this->transactions()->sum('amount');
    }

    public function getRemainingAmountAttribute() {
        return max(0, $this->plan_amount - $this->used_amount);
    }

    public function loan(): BelongsTo {
        return $this->belongsTo(Loan::class);
    }
    
}