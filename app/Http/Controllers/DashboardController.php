<?php

namespace App\Http\Controllers;

use App\Models\BudgetAllocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $currentMonth = Carbon::now()->format('Y-m');

        // 1. PROCESS INSPIRING QUOTE
        $fullQuote = $user->inspiring_quote ?? '';
        $quote = $fullQuote;
        $author = 'Anonymous';

        if (str_contains($fullQuote, '—')) {
            [$quotePart, $authorPart] = explode('—', $fullQuote, 2);
            $quote = trim($quotePart);
            $author = trim($authorPart);
        }

        // 2. FETCH RECENT TRANSACTIONS
        $recentTransactions = $user->walletTransactions()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($tx) => [
                'id'       => $tx->id,
                'uuid'     => $tx->uuid,
                'title'    => $tx->description,
                'category' => $tx->type->label(),
                'amount'   => (float) $tx->amount,
                'type'     => $tx->type->isInflow() ? 'in' : 'out',
                'date'     => $tx->transaction_date->diffForHumans(),
            ]);

        // 3. FETCH ACTIVE LOANS
        $activeLoans = $user->loans()
            ->where('status', 'active')
            ->get()
            ->map(fn($loan) => [
                'id'       => $loan->id,
                'provider' => $loan->name,
                'amount'   => (float) $loan->remaining_amount,
            ]);

        // 4. FETCH BUDGETS (Using the same logic as Index)
        // We use withSum for performance on the dashboard too
        $budgets = BudgetAllocation::where('user_id', $userId)
            ->where('month_year', $currentMonth)
            ->withSum(['transactions as spent_amount' => function ($query) {
                $query->whereIn('type', ['budget_spending', 'expense']);
            }], 'amount')
            ->get()
            ->map(fn($budget) => [
                'id'               => $budget->id,
                'name'             => $budget->name,
                'plan_amount'      => (float) $budget->plan_amount,
                'used_amount'      => (float) ($budget->spent_amount ?? 0),
                'remaining_amount' => (float) max(0, $budget->plan_amount - ($budget->spent_amount ?? 0)),
                'percentage'       => $budget->plan_amount > 0 
                    ? (int) round(($budget->spent_amount / $budget->plan_amount) * 100) 
                    : 0,
            ]);

        // 5. RENDER VIEW VIA INERTIA
        return Inertia::render('Dashboard', [
            'inspiringQuote' => [
                'text'   => $quote,
                'author' => $author,
            ],
            'stats' => [
                'total_equity'      => (float) $user->currentBalance(),
                'total_debt'        => (float) $user->totalDebt(),
                'total_budget'      => BudgetAllocation::totalPlanned($userId, $currentMonth),
                'monthly_savings'   => (float) $user->totalSavings(),
                'growth_percentage' => (float) $user->getGrowthPercentage(),
                
                // Consistency: Use the static methods we built in BudgetAllocation
                'budget_usage'            => BudgetAllocation::totalSpent($userId, $currentMonth),
                'budget_usage_percentage' => (int) BudgetAllocation::usagePercentage($userId, $currentMonth),
                
                // Bonus: Pass the Analysis to Dashboard as well
                'analysis'                => BudgetAllocation::getMonthlyAnalysis($userId, $currentMonth),
            ],
            'budgets'            => $budgets,
            'recentTransactions' => $recentTransactions,
            'activeLoans'        => $activeLoans,
        ]);
    }
}