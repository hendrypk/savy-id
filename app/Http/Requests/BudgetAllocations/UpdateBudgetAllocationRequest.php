<?php

namespace App\Http\Requests\BudgetAllocations;

use App\Models\BudgetAllocation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBudgetAllocationRequest extends FormRequest
{

    protected $budgetRecord;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
    // Ensure the user owns this budget using the uuid from the route
        $this->budgetRecord = BudgetAllocation::where('uuid', $this->route('budget'))
            ->where('user_id', $this->user()->id)
            ->withSum(['transactions as spent_amount' => function ($query) {
                $query->whereIn('type', ['budget_spending', 'expense']);
            }], 'amount')
            ->firstOrFail();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plan_amount' => 'required|numeric|min:1000',
            'month_year' => 'required|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $currentMonth = now()->format('Y-m');
            $spent = $this->budgetRecord->spent_amount ?? 0;

            // RULE 1: Past Month Lock
            if ($this->budgetRecord->month_year !== $currentMonth) {
                $validator->errors()->add('plan_amount', 'You cannot edit budgets from previous months.');
            }

            // RULE 2: Minimum Amount check
            if ($this->input('plan_amount') < $spent) {
                $validator->errors()->add('plan_amount', "Budget cannot be less than current spending (Rp " . number_format($spent, 0, ',', '.') . ")");
            }
        });
    }

    public function getBudget()
    {
        return $this->budgetRecord;
    }
}
