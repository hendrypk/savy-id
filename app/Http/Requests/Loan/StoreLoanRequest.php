<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'loan_type' => [
                'required', 
                'string', 
                'in:credit_card,pinjol,personal,bank,kasbon_kantor,other'
            ],
            'credit_limit' => ['required', 'numeric', 'min:1'],
            
            // Required only if not in Flexible Mode (calculated/filled in frontend)
            'monthly_installment' => ['nullable', 'numeric', 'min:0'],
            'tenor' => ['nullable', 'integer', 'min:0'],
            
            'due_date' => ['required', 'integer', 'between:1,31'],
            'interest_rate_monthly' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Custom error messages in English to match your new UI.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the account or bank name.',
            'loan_type.in' => 'The selected loan type is invalid.',
            'credit_limit.required' => 'Total loan amount is required.',
            'credit_limit.min' => 'Total loan must be at least 1.',
            'due_date.between' => 'Due date must be a day between 1 and 31.',
            'tenor.integer' => 'Tenor must be a whole number of months.',
        ];
    }

    /**
     * Prepare data for validation (Optional: ensuring defaults)
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'monthly_installment' => $this->monthly_installment ?? 0,
            'tenor' => $this->tenor ?? 0,
            'interest_rate_monthly' => $this->interest_rate_monthly ?? 0,
        ]);
    }
}