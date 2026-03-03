<?php

namespace App\Observers;

use App\Enums\TransactionType;
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
        // We use the methods we defined in the Enum
        $wallet = $transaction->wallet;

        if ($transaction->type->isInflow()) {
            $wallet->increment('balance', $transaction->amount);
        } elseif ($transaction->type->isOutflow()) {
            $wallet->decrement('balance', $transaction->amount);
        }

        // 2. UPDATE LOAN BALANCE
        // Check if the transaction affects a Loan via the Morphic relationship
        if ($transaction->type->affectsLoanBalance() && $transaction->reference_type === Loan::class) {
            
            $loan = $transaction->reference; // Using the 'reference' morphTo relation

            if ($loan) {
                if ($transaction->type === TransactionType::LOAN_DISBURSEMENT) {
                    // Getting a new loan increases your debt
                    $loan->increment('remaining_amount', $transaction->amount);
                } elseif ($transaction->type === TransactionType::LOAN_REPAYMENT) {
                    // Paying it back decreases your debt
                    $loan->decrement('remaining_amount', $transaction->amount);
                }

                // Auto-close loan if paid off
                if ($loan->remaining_amount <= 0) {
                    $loan->update([
                        'remaining_amount' => 0,
                        'status' => 'paid' // Matches your Loan::STATUS_PAID
                    ]);
                }
            }
        }
    }
}