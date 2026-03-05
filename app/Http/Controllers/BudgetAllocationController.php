<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetAllocations\UpdateBudgetAllocationRequest;
use App\Http\Requests\StoreBudgetAllocationRequest;
use App\Models\BudgetAllocation;
use App\Models\TransactionCategory;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BudgetAllocationController extends Controller
{
    public function index(Request $request): Response
    {
        $userId = Auth::id();
        $monthParam = $request->query('month', now()->format('Y-m'));
        $date = \Carbon\Carbon::parse($monthParam);

        // 1. Fetch budgets with spending summed up in one single SQL query
        $budgets = BudgetAllocation::where('user_id', $userId)
            ->where('month_year', $monthParam)
            ->with('category')
            ->withSum(['transactions as spent_amount' => function ($query) {
                $query->whereIn('type', ['budget_spending', 'expense']);
            }], 'amount')
            ->get();

        // 2. Calculate totals from the collection (Guarantees consistency with the rows)
        $totalPlanned = (float) $budgets->sum('plan_amount');
        $totalSpent   = (float) $budgets->sum('spent_amount');
        
        // Calculate usage percentage for the header
        $usagePct = $totalPlanned > 0 ? (int) round(($totalSpent / $totalPlanned) * 100) : 0;

        return Inertia::render('budget/Index', [
            'budgets'           => $budgets->map(fn($budget) => $this->formatBudget($budget)),
            'total_planned'     => $totalPlanned,
            'total_spent'       => $totalSpent,
            'usage_pct'         => $usagePct,
            
            // Static call for historical comparison (Last Month vs This Month)
            'analysis'          => BudgetAllocation::getMonthlyAnalysis($userId, $monthParam),
            
            'current_month_raw'   => $monthParam,
            'current_month_label' => $date->translatedFormat('F Y'),
        ]);
    }

    protected function formatBudget($budget)
    {
        $planned = (float) $budget->plan_amount;
        // Use 'spent_amount' from withSum if available, otherwise fallback to accessor
        $spent   = (float) ($budget->spent_amount ?? $budget->used_amount);
        
        // Calculate percentage as a whole number (Integer)
        $percentage = $planned > 0 ? ($spent / $planned) * 100 : 0;

        return [
            'uuid'            => $budget->uuid,
            'name'            => $budget->name,
            'plan_amount'     => $planned,
            'spent_amount'    => $spent,
            'remaining'       => max(0, $planned - $spent),
            'category_name'   => $budget->category->name ?? 'Uncategorized',
            'month_year'      => $budget->month_year,
            'percentage'      => (int) round($percentage) 
        ];
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
        $userId = Auth::id();
        $categories = TransactionCategory::select('id', 'name')
            ->where('user_id', $userId)
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
    **/
    public function store(StoreBudgetAllocationRequest $request): RedirectResponse
    {
        $request->user()->budgets()->create(array_merge($request->validated(), [
            'uuid' => (string) Str::uuid(),
        ]));

        return redirect()->route('budget.index')->with('success', 'Budget created successfully!');
    }

    public function edit($uuid): Response
    {
        $userId = Auth::id();

        $budget = BudgetAllocation::where('uuid', $uuid)
            ->where('user_id', $userId)
            ->firstOrFail();

        $categories = TransactionCategory::select('id', 'name')
            ->where('user_id', $userId)
            ->get();

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
                'name' => $budget->name,
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

    public function destroy($uuid): RedirectResponse
    {
        // 1. Find the budget by UUID
        $budget = BudgetAllocation::where('uuid', $uuid)->firstOrFail();

        // 2. Validation: Check if transactions exist for this specific budget ID
        $hasTransactions = WalletTransaction::where('reference_type', BudgetAllocation::class)
            ->where('reference_id', $budget->id)
            ->exists();

            if($budget->loan_id) {
            return redirect()->back()->withErrors([
                'message' => 'Anggaran cicilan pinjaman tidak dapat dihapus secara manual.'
            ]);
        }
            

        if ($hasTransactions) {
            // Return with an error message that Inertia's onError will catch
            return Redirect::back()->withErrors([
                'delete' => 'Cannot delete budget: There are existing transactions linked to this allocation.'
            ]);
        }

        // 3. Perform the deletion
        $budget->delete();

        return Redirect::route('budget.index')->with('success', 'Budget deleted successfully.');
    }
}
