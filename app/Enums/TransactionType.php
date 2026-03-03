<?php

namespace App\Enums;

enum TransactionType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';
    case LOAN_DISBURSEMENT = 'loan_disbursement';
    case LOAN_REPAYMENT = 'loan_repayment';
    case SAVING = 'saving';

    /**
     * Determine if the transaction increases the wallet balance.
     */
    public function isInflow(): bool
    {
        return match($this) {
            self::INCOME, self::LOAN_DISBURSEMENT => true,
            default => false,
        };
    }

    /**
     * Determine if the transaction decreases the wallet balance.
     */
    public function isOutflow(): bool
    {
        return match($this) {
            self::EXPENSE, self::LOAN_REPAYMENT, self::SAVING => true,
            default => false,
        };
    }

    /**
     * Get a human-readable label for the UI.
     */
    public function label(): string
    {
        return match($this) {
            self::INCOME, self::LOAN_DISBURSEMENT => 'income',
            self::EXPENSE, self::LOAN_REPAYMENT => 'expense',
            self::SAVING => 'move',
        };
    }

    /**
     * Determines if this transaction affects the total debt of a Loan.
     */
    public function affectsLoanBalance(): bool
    {
        return match($this) {
            self::LOAN_DISBURSEMENT, self::LOAN_REPAYMENT => true,
            default => false,
        };
    }

    /**
     * Determines if this transaction typically counts toward a Budget.
     */
    public function isBudgetable(): bool
    {
        return match($this) {
            self::EXPENSE, self::LOAN_REPAYMENT, self::SAVING => true,
            default => false,
        };
    }
}