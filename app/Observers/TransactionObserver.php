<?php

namespace App\Observers;

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
        // Load the related wallet model
        $wallet = $transaction->wallet;

        /**
         * WALLET BALANCE LOGIC
         * ---------------------------------------------------------
         * Determine whether to increment or decrement the wallet 
         * balance based on the cash flow direction.
         */
        switch ($transaction->type) {
            // Cash Inflows: Increase wallet balance
            case WalletTransaction::TYPE_INCOME:
            case WalletTransaction::TYPE_LOAN_DISBURSEMENT:
                $wallet->increment('balance', $transaction->amount);
                break;
                
            // Cash Outflows: Decrease wallet balance
            case WalletTransaction::TYPE_EXPENSE:
            case WalletTransaction::TYPE_BUDGET_SPENDING:
            case WalletTransaction::TYPE_SAVING:
            case WalletTransaction::TYPE_LOAN_REPAYMENT:
                $wallet->decrement('balance', $transaction->amount);
                break;
        }

        /**
         * LOAN SENSITIVE LOGIC
         * ---------------------------------------------------------
         * If the transaction is specifically for a loan repayment, 
         * we must update the liability status of the user.
         */
        if ($transaction->type === WalletTransaction::TYPE_LOAN_REPAYMENT && $transaction->loan_id) {
            $loan = $transaction->loan;
            
            // Subtract the paid amount from the total remaining debt
            $loan->decrement('remaining_amount', $transaction->amount);
            
            // Check for full repayment to auto-update status
            if ($loan->remaining_amount <= 0) {
                $loan->update(['status' => 'paid']);
            }
        }
    }
}