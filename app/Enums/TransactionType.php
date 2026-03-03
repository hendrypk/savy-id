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
     * Get a human-readable label for the UI.
     */
    public function label(): string
    {
        return match($this) {
            self::INCOME => 'Pemasukan',
            self::EXPENSE => 'Pengeluaran',
            self::LOAN_DISBURSEMENT => 'Pencairan Pinjaman',
            self::LOAN_REPAYMENT => 'Pembayaran Cicilan',
            self::SAVING => 'Tabungan',
        };
    }
}