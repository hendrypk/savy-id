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
public function index()
{
    $transactions = WalletTransaction::with(['category', 'wallet', 'reference'])
        ->where('user_id', auth()->id())
        ->orderBy('transaction_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy(function($item) {
            return $item->transaction_date->format('Y-m-d');
        });

    return Inertia::render('transactions/Index', [
        'groupedTransactions' => $transactions,
        'stats' => [
            'total_balance' => auth()->user()->wallets()->sum('balance'),
            'this_month_expense' => WalletTransaction::where('user_id', auth()->id())
                ->whereMonth('transaction_date', now()->month)
                ->whereIn('type', ['expense', 'loan_repayment', 'saving'])
                ->sum('amount'),
            'this_month_income' => WalletTransaction::where('user_id', auth()->id())
                ->whereMonth('transaction_date', now()->month)
                ->whereIn('type', ['income', 'loan_disbursement'])
                ->sum('amount'),
        ]
    ]);
}

    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $type = TransactionType::from($data['type']);

        return DB::transaction(function () use ($data, $type) {

            // --- HANDLE MORPH RELATION ---
            if (!empty($data['budget_allocation_id'])) {
                $data['reference_type'] = \App\Models\BudgetAllocation::class;
                $data['reference_id'] = $data['budget_allocation_id'];
            }

            if (!empty($data['reference_id']) && empty($data['budget_allocation_id'])) {
                $data['reference_type'] = \App\Models\Loan::class;
            }

            // optional: unset yang tidak dipakai
            unset($data['budget_allocation_id']);
            unset($data['ref_category']);

            // 1. Create transaction
            $transaction = WalletTransaction::create([
                ...$data,
                'user_id' => auth()->id(),
            ]);

            // 2. Update wallet
            $wallet = Wallet::lockForUpdate()->findOrFail($data['wallet_id']);
            
            if ($type->isInflow()) {
                $wallet->increment('balance', $data['amount']);
            } elseif ($type->isOutflow()) {
                $wallet->decrement('balance', $data['amount']);
            }

            // 3. Update Loan balance
            if (
                isset($data['reference_type']) &&
                $data['reference_type'] === \App\Models\Loan::class
            ) {
                $loan = Loan::lockForUpdate()->findOrFail($data['reference_id']);

                $type === TransactionType::LOAN_DISBURSEMENT
                    ? $loan->increment('remaining_amount', $data['amount'])
                    : $loan->decrement('remaining_amount', $data['amount']);
            }

            return redirect()->route('api.transactions.index');
        });
    }
}
