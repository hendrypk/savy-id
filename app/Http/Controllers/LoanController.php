<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loan\StoreLoanRequest;
use App\Http\Requests\Loan\UpdateLoanRequest;
use App\Models\BudgetAllocation;
use App\Models\Loan;
use App\Models\TransactionCategory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LoanController extends Controller
{
    private function userLoans()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->loans();
    }

    public function index()
    {
        $loans = $this->userLoans()
            ->latest()
            ->get()
            ->map(fn($loan) => $this->transformLoan($loan));      

        return Inertia::render('loans/Index', [
            'loans' => $loans,
            'totalDebt' => $loans->sum('remaining_amount'),
            'totalMonthly' => $loans->sum('monthly_installment'),
        ]);
    }

    public function create()
    {
        return Inertia::render('loans/Create');
    }

    public function store(StoreLoanRequest $request)
    {
        $validated = $request->validated();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            return DB::transaction(function () use ($validated, $user) {
                // 1. Simpan Data Pinjaman
                $loan = $user->loans()->create([
                    'uuid'                  => Str::uuid(),
                    'name'                  => $validated['name'],
                    'loan_type'             => $validated['loan_type'],
                    'credit_limit'          => $validated['credit_limit'],
                    'remaining_amount'      => $validated['credit_limit'],
                    'monthly_installment'   => $validated['monthly_installment'],
                    'total_tenor'           => $validated['tenor'],
                    'due_date'              => $validated['due_date'],
                    'interest_rate_monthly' => $validated['interest_rate_monthly'] ?? 0,
                    'status'                => 'active',
                ]);

                // 2. Logic Insert Mass ke Budget Allocation
                if ($loan->monthly_installment > 0 && $loan->total_tenor > 0) {
                    
                    $category = TransactionCategory::firstOrCreate(
                        ['user_id' => $user->id, 'slug' => 'bayar-pinjaman'],
                        [
                            'uuid'  => Str::uuid(),
                            'name'  => 'Bayar Pinjaman',
                            'type'  => 'expense',
                            'icon'  => 'banknotes',
                            'color' => '#4f46e5',
                        ]
                    );

                    $budgetData = [];
                    $startDate = Carbon::now();

                    for ($i = 0; $i < $loan->total_tenor; $i++) {
                        $budgetData[] = [
                            'uuid'                    => Str::uuid()->toString(),
                            'user_id'                 => $user->id,
                            'transaction_category_id' => $category->id,
                            'plan_amount'             => $loan->monthly_installment,
                            'month_year'              => $startDate->copy()->addMonths($i)->format('Y-m'),
                            'created_at'              => now(),
                            'updated_at'              => now(),
                        ];
                    }

                    // Mass Insert
                    BudgetAllocation::insert($budgetData);
                }

                return redirect()->route('loans.index')
                    ->with('success', "Pinjaman '{$loan->name}' dan {$loan->total_tenor} alokasi anggaran berhasil dibuat.");
            });

        } catch (Exception $e) {
            // Jika ada error, Laravel otomatis melakukan Rollback pada DB::transaction
            
            // Log error untuk kebutuhan internal developer
            \Log::error("Gagal membuat pinjaman: " . $e->getMessage());

            // Kembalikan ke halaman sebelumnya dengan pesan error untuk user
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function show($uuid)
    {
        $loan = $this->userLoans()
            ->with(['transactions' => fn($q) => $q->latest()])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return Inertia::render('loans/Show', [
            'loan' => $this->transformLoan($loan, true)
        ]);
    }

    public function edit($uuid)
    {
        $loan = $this->userLoans()->where('uuid', $uuid)->firstOrFail();
        return Inertia::render('loans/Edit', [
            'loan' => $loan
        ]);
    }

    public function update(UpdateLoanRequest $request, $uuid)
    {
        $loan = $this->userLoans()->where('uuid', $uuid)->firstOrFail();
        
        $loan->update($request->validated());

        return redirect()->route('loans.show', $loan->uuid)
            ->with('message', 'Pinjaman berhasil diperbarui');
    }

    public function destroy($uuid)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            return DB::transaction(function () use ($user, $uuid) {
                // 1. Find the Loan belonging to the user
                $loan = $user->loans()->where('uuid', $uuid)->firstOrFail();

                // 2. Identify the "Bayar Pinjaman" Category
                $category = TransactionCategory::where('user_id', $user->id)
                    ->where('slug', 'bayar-pinjaman')
                    ->first();

                // 3. Soft Delete related Budget Allocations
                // We match by category and the specific installment amount 
                // since your table doesn't have a 'loan_id' yet.
                if ($category) {
                    $user->budgets()
                        ->where('transaction_category_id', $category->id)
                        ->where('plan_amount', $loan->monthly_installment)
                        ->delete(); // This triggers Soft Delete
                }

                // 4. Soft Delete the Loan itself
                $loan->delete();

                return redirect()->route('loans.index')
                    ->with('success', "Loan '{$loan->name}' and its budget allocations have been deleted.");
            });

        } catch (\Exception $e) {
            \Log::error("Failed to delete loan: " . $e->getMessage());

            return back()->withErrors(['error' => 'Deletion failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Logic Transformer: Supaya rumus progress & tenor tidak berantakan di index/show
     */
    private function transformLoan(Loan $loan, bool $includeTransactions = false)
    {
        $remainingTenor = $loan->monthly_installment > 0 
            ? ceil($loan->remaining_amount / $loan->monthly_installment) 
            : 0;

        $data = [
            'uuid' => $loan->uuid,
            'name' => $loan->name,
            'status' => $loan->status,
            'remaining_amount' => (float) $loan->remaining_amount,
            'credit_limit' => (float) $loan->credit_limit,
            'monthly_installment' => (float) $loan->monthly_installment,
            'due_date' => $loan->due_date,
            'total_tenor' => $loan->total_tenor,
            'remaining_tenor' => $remainingTenor,
            'times_paid' => max(0, $loan->total_tenor - $remainingTenor),
            'progress' => ($loan->credit_limit > 0) 
                ? round((($loan->credit_limit - $loan->remaining_amount) / $loan->credit_limit) * 100) 
                : 0,
        ];

        if ($includeTransactions) {
            $data['transactions'] = ($loan->transactions ?? collect())->map(fn($trx) => [
                'uuid' => $trx->uuid,
                'amount' => (float) $trx->amount,
                'created_at' => $trx->created_at->toISOString(),
            ]);
        }

        return $data;
    }
}
