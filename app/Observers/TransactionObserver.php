<?php

namespace App\Observers;

use App\Enums\TransactionType;
use App\Models\Loan;
use App\Models\WalletTransaction;

/**
 * Class TransactionObserver
 * * This observer handles the "side effects" of wallet transactions.
 * It ensures that whenever a transaction is recorded, the related 
 * wallet balances and loan balances are automatically synchronized.
 */
class TransactionObserver
{
    /**
     * Handle the WalletTransaction "created" event.
     * * This method is triggered immediately after a transaction is saved to the database.
     * It performs the following:
     * 1. Updates the associated Wallet balance based on the transaction type.
     * 2. Decrements the Loan's remaining amount if the type is a repayment.
     * 3. Automatically marks a Loan as 'paid' once the balance reaching zero.
     *
     * @param  \App\Models\WalletTransaction  $transaction
     * @return void
     */
    public function created(WalletTransaction $transaction): void
    {
        // 1. UPDATE WALLET BALANCE
        $wallet = $transaction->wallet;
        if ($transaction->type->isInflow()) {
            $wallet->increment('balance', $transaction->amount);
        } elseif ($transaction->type->isOutflow()) {
            $wallet->decrement('balance', $transaction->amount);
        }

        // 2. UPDATE BUDGET ALLOCATION BALANCE (NEW)
        if ($transaction->reference_type === \App\Models\BudgetAllocation::class) {
            $budget = $transaction->reference;
            if ($budget) {
                // Every time a transaction hits a budget, increase the used_amount
                $budget->increment('used_amount', $transaction->amount);
            }
        }

        // 3. UPDATE LOAN BALANCE
        if ($transaction->type->affectsLoanBalance() && $transaction->reference_type === Loan::class) {
            $loan = $transaction->reference;
            if ($loan) {
                if ($transaction->type === TransactionType::LOAN_DISBURSEMENT) {
                    $loan->increment('remaining_amount', $transaction->amount);
                } elseif ($transaction->type === TransactionType::LOAN_REPAYMENT) {
                    $loan->decrement('remaining_amount', $transaction->amount);
                }

                if ($loan->remaining_amount <= 0) {
                    $loan->update(['remaining_amount' => 0, 'status' => 'paid']);
                }
            }
        }
    }

    public function deleted(WalletTransaction $transaction)
    {
        $wallet = $transaction->wallet;

        // --- REVERSE WALLET BALANCE ---
        // If we delete an EXPENSE, we must put the money BACK (increment)
        // If we delete an INCOME, we must take the money AWAY (decrement)
        if ($transaction->type === TransactionType::INCOME) {
            $wallet->decrement('balance', $transaction->amount);
        } else {
            $wallet->increment('balance', $transaction->amount);
        }

        // --- HANDLE LOAN REVERSAL (If applicable) ---
        // If the transaction was a loan repayment, deleting it should 
        // increase the remaining debt again.
        if ($transaction->reference_type === \App\Models\Loan::class) {
            $loan = $transaction->reference;
            if ($loan) {
                $loan->increment('remaining_amount', $transaction->amount);
            }
        }
    }
}