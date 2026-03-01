<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    const TYPE_CREDIT_CARD = 'credit_card';
    const TYPE_PINJOL = 'pinjol';
    const TYPE_OTHER = 'other';

    const STATUS_ACTIVE = 'active';
    const STATUS_PAID = 'paid';

    protected $fillable = [
        'uuid', 'user_id', 'name', 'loan_type', 'credit_limit', 
        'remaining_amount', 'monthly_installment', 'interest_rate_monthly', 
        'due_date', 'status'
    ];

    public function transactions(): HasMany {
        return $this->hasMany(WalletTransaction::class);
    }

    public function getProgressAttribute() {
        if ($this->credit_limit <= 0) return 0;
        $paid = $this->credit_limit - $this->remaining_amount;
        return round(($paid / $this->credit_limit) * 100);
    }
}