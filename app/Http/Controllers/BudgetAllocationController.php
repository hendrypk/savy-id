<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetAllocations\UpdateBudgetAllocationRequest;
use App\Http\Requests\StoreBudgetAllocationRequest;
use App\Models\BudgetAllocation;
use App\Models\TransactionCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BudgetAllocationController extends Controller
{
    public function index(Request $request): Response
    {
        // 1. Get month from request or default to now (Format: YYYY-MM)
        $monthParam = $request->query('month', now()->format('Y-m'));
        
        // 2. Create a Carbon instance for easier formatting
        $date = \Carbon\Carbon::parse($monthParam);

        $userId = Auth::id();

        $budgets = BudgetAllocation::where('user_id', $userId)
            ->where('month_year', $monthParam) // Filter by selected month
            ->with('category')
            ->withSum(['transactions as spent_amount' => function ($query) {
                $query->whereIn('type', ['budget_spending', 'expense']);
            }], 'amount')
            ->get()
            ->map(function ($budget) {
                $spent = $budget->spent_amount ?? 0;
                $plan = $budget->plan_amount;
                
                return [
                    'uuid' => $budget->uuid,
                    'name' => $budget->category->name,
                    'type' => $budget->category->type,
                    'plan_amount' => (float) $plan,
                    'spent_amount' => (float) $spent,
                    'remaining' => (float) ($plan - $spent),
                    'percentage' => $plan > 0 ? round(($spent / $plan) * 100) : 0,
                ];
            });

        return Inertia::render('budget/Index', [
            'categories' => $budgets,
            'total_planned' => (float) $budgets->sum('plan_amount'),
            'total_spent' => (float) $budgets->sum('spent_amount'),
            // Send raw YYYY-MM for the logic and formatted string for UI
            'current_month_raw' => $monthParam,
            'current_month_label' => $date->translatedFormat('F Y'),
        ]);
    }

    /**
     * Show the form for creating a new budget allocation.
     * * @return \Inertia\Response
     */
    public function create(): Response
    {
        /**
         * We fetch categories from the transaction_categories table.
         * Only selecting 'id' and 'name' to keep the payload lightweight.
         */
        $categories = TransactionCategory::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('budget/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created budget allocation in storage.
     * * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBudgetAllocationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $userId = Auth::id();

        BudgetAllocation::create(array_merge($request->validated(), [
            'user_id' => $userId,
            'uuid' => (string) str()->uuid(),
            'transaction_category_id' => $validated['transaction_category_id'],
            'plan_amount' => $validated['plan_amount'],
            'month_year' => $validated['month_year'],
        ]));

        return redirect()->route('budget.index')->with('success', 'Budget created successfully!');
    }

    public function edit($uuid): Response
    {
        $userId = Auth::id();

        $budget = BudgetAllocation::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->firstOrFail();

        $categories = TransactionCategory::select('id', 'name')->get();

        return Inertia::render('budget/Edit', [
            'budget' => $budget,
            'categories' => $categories
        ]);
    }

    public function update(UpdateBudgetAllocationRequest $request, $uuid): RedirectResponse
    {
        // Retrieve the budget already fetched during validation
        $budget = $request->getBudget();

        $budget->update($request->validated());

        return redirect()->route('budget.show', $budget->uuid)->with('success', 'Budget updated!');
    }
    
    public function show($uuid): Response
    {
        $userId = Auth::id();

        $budget = BudgetAllocation::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->with(['category'])
            ->withSum(['transactions as spent_amount' => function ($query) {
                $query->whereIn('type', ['budget_spending', 'expense']);
            }], 'amount')
            ->firstOrFail();

        $transactions = $budget->transactions()
            ->with('wallet') 
            ->orderBy('transaction_date', 'desc')
            ->get();

        return Inertia::render('budget/Show', [
            'budget' => [
                'uuid' => $budget->uuid,
                'name' => $budget->category->name,
                'plan_amount' => (float) $budget->plan_amount,
                'spent_amount' => (float) ($budget->spent_amount ?? 0),
                'remaining' => (float) ($budget->plan_amount - ($budget->spent_amount ?? 0)),
                'percentage' => $budget->plan_amount > 0 ? round(($budget->spent_amount / $budget->plan_amount) * 100) : 0,
                'month_label' => \Carbon\Carbon::parse($budget->month_year)->translatedFormat('F Y'),
                'month_raw' => $budget->month_year, // Required for the lock logic
            ],
            'transactions' => $transactions
        ]);
    }
}
