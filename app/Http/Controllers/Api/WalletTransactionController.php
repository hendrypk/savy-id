<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Models\BudgetAllocation;
use App\Models\Loan;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WalletTransactionController extends Controller
{


    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        
        return DB::transaction(function () use ($data) {
            // --- HANDLE MORPH RELATION ---
            if (!empty($data['budget_allocation_id'])) {
                $data['reference_type'] = \App\Models\BudgetAllocation::class;
                $data['reference_id'] = $data['budget_allocation_id'];
            }

            if (!empty($data['reference_id']) && empty($data['budget_allocation_id'])) {
                $data['reference_type'] = \App\Models\Loan::class;
            }

            unset($data['budget_allocation_id']);
            unset($data['ref_category']);

            // JUST CREATE: The Observer will automatically handle the side effects
            WalletTransaction::create([
                ...$data,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('transactions.index');
        });
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(WalletTransaction $transaction)
    {
        // Authorization: Ensure the user owns the transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        
        return DB::transaction(function () use ($transaction) {
            if ($transaction->reference_type === \App\Models\BudgetAllocation::class) {
                $budget = $transaction->reference; // Mengambil model Budget terkait
                
                if ($budget) {
                    // Kurangi used_amount di budget dengan amount transaksi yang akan dihapus
                    // Kita gunakan decrement untuk keamanan database (atomic operation)
                    $budget->decrement('used_amount', $transaction->amount);
                }
            }
            // Just Delete: The Observer's "deleted" or "deleting" method 
            // will automatically handle the balance reversal.
            $transaction->delete();

            return redirect()->route('transactions.index');
        });
    }
}
