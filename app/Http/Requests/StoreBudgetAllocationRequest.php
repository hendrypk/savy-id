<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetAllocationRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'transaction_category_id.unique' => 'A budget for this category already exists for the selected period.',
        ];
    }
}
