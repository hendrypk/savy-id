<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
public function index(Request $request)
    {
        $user = $request->user();

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
                'amount'   => $tx->amount,
                'type'     => $tx->type->isInflow() ? 'in' : 'out',
                'date'     => $tx->created_at->diffForHumans(),
            ]);

        // 3. FETCH ACTIVE LOANS
        $activeLoans = $user->loans()
            ->where('status', 'active')
            ->get()
            ->map(fn($loan) => [
                'id'       => $loan->id,
                'provider' => $loan->name,
                'amount'   => $loan->remaining_amount,
            ]);

        // 4. FETCH BUDGET USAGE (Current Month)
        $budgets = $user->budgets()
            ->where('month_year', Carbon::now()->format('Y-m'))
            ->get()
            ->map(fn($budget) => [
                'id'               => $budget->id,
                'name'             => $budget->name,
                'plan_amount'      => $budget->plan_amount,
                'used_amount'      => $budget->used_amount,      // Via Model Accessor
                'remaining_amount' => $budget->remaining_amount, // Via Model Accessor
                'percentage'       => $budget->plan_amount > 0 
                    ? round(($budget->used_amount / $budget->plan_amount) * 100) 
                    : 0,
            ]);

        // 5. RENDER VIEW VIA INERTIA
        return Inertia::render('Dashboard', [
            'inspiringQuote' => [
                'text'   => $quote,
                'author' => $author,
            ],
            'stats' => [
                'total_equity'      => $user->currentBalance(),
                'total_debt'        => $user->totalDebt(),
                'total_budget'      => $user->totalBudget(),
                'monthly_savings'   => $user->totalSavings(),
                'growth_percentage' => $user->getGrowthPercentage(),
                'budget_usage' => $user->getBudgetUsage(),
                'budget_usage_percentage' => $user->getBudgetUsagePercentage(),
            ],
            'budgets'            => $budgets,
            'recentTransactions' => $recentTransactions,
            'activeLoans'        => $activeLoans,
        ]);
    }
}