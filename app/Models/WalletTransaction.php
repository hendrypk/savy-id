<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    // Konstanta Type agar tidak typo: WalletTransaction::TYPE_EXPENSE
    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';
    const TYPE_BUDGET_SPENDING = 'budget_spending';
    const TYPE_SAVING = 'saving';
    const TYPE_LOAN_REPAYMENT = 'loan_repayment';
    const TYPE_LOAN_DISBURSEMENT = 'loan_disbursement';

    protected $fillable = [
        'uuid', 'user_id', 'wallet_id', 'budget_allocation_id', 
        'saving_id', 'loan_id', 'description', 'amount', 
        'type', 'transaction_date'
    ];

    public function wallet(): BelongsTo {
        return $this->belongsTo(Wallet::class);
    }

    public function budgetAllocation(): BelongsTo {
        return $this->belongsTo(BudgetAllocation::class);
    }

    public function loan(): BelongsTo {
        return $this->belongsTo(Loan::class);
    }
}