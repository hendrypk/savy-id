<?php

namespace App\Http\Controllers;

use App\Models\BudgetAllocation;
use App\Models\Loan;
use App\Models\TransactionCategory;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletTransactionController extends Controller
{
public function create(Request $request)
{
    $userId = auth()->id();
    $currentMonth = now()->format('Y-m');

    // 1. Prepare Wallets
    $wallets = Wallet::where('user_id', $userId)
        ->get(['id', 'name', 'balance']);

    // 2. Prepare Budget Allocations (Regular & Installments)
    $budgets = BudgetAllocation::with(['category:id,name', 'loan:id,name'])
        ->where('user_id', $userId)
        ->where('month_year', $currentMonth)
        ->get()
        ->map(fn($budget) => [
            'id'          => $budget->id,
            'transaction_category_id' => $budget->transaction_category_id, // WAJIB ADA agar frontend bisa auto-select
            'loan_id'     => $budget->loan_id,
            'name'        => $budget->loan_id 
                             ? "Cicilan: " . ($budget->loan->name ?? 'Pinjaman') 
                             : ($budget->category->name ?? 'Tanpa Kategori'),
            'plan_amount' => $budget->plan_amount,
            'remaining'   => $budget->remaining_amount,
        ]);

    // 3. Prepare Loans (Flexible/Tenor 0 only)
    $loans = Loan::where('user_id', $userId)
        ->where('status', Loan::STATUS_ACTIVE)
        ->where(fn($query) => $query->whereNull('total_tenor')->orWhere('total_tenor', 0))
        ->get(['id', 'name', 'remaining_amount']);

    // 4. Prepare General Categories
    $categories = TransactionCategory::get(['id', 'name']);

    // 5. Contextual data
    $selectedType = $request->query('type', 'expense');

    return Inertia::render('transactions/Create', [
        'wallets'      => $wallets,
        'budgets'      => $budgets,
        'loans'        => $loans,
        'categories'   => $categories,
        'selectedType' => $selectedType,
        'app_version'  => config('app.version', '1.0.0'), // Optional extra context
    ]);
}
}
