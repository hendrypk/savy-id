<?php

namespace App\Http\Requests\Loan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanRequest extends FormRequest
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
            'loan_type' => ['required', 'string'],
            'remaining_amount' => ['required', 'numeric', 'min:0'],
            'monthly_installment' => ['required', 'numeric', 'min:0'],
            'total_tenor' => ['required', 'integer', 'min:0'],
            'due_date' => ['required', 'integer', 'between:1,31'],
            'status' => ['required', 'string']
        ];
    }

    /**
     * Custom error messages (Optional).
     */
    public function messages(): array
    {
        return [
            'due_date.between' => 'The due date must be a day between 1 and 31.',
            'remaining_amount.min' => 'The remaining balance cannot be negative.',
        ];
    }
}