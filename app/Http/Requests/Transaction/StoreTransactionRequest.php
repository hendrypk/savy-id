<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTransactionRequest extends FormRequest
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
            'wallet_id'               => 'required|exists:wallets,id',
            'transaction_category_id' => 'required|exists:transaction_categories,id',
            'amount'                  => 'required|numeric|min:0',
            'type'                    => ['required', new Enum(TransactionType::class)],
            'transaction_date'        => 'required|date',
            'description'             => 'nullable|string|max:255',
            'budget_allocation_id'    => 'nullable|exists:budget_allocations,id',
            'reference_id'            => 'nullable|integer',
            'ref_category'            => 'nullable|in:budget,loan',
        ];
    }
}
