<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LoanController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $loans = $user->loans() // The red line should disappear now
            ->get()
            ->map(function ($loan) {
                $used = 1500000; 
                
                return [
                    'uuid' => $loan->uuid,
                    'name' => $loan->name,
                    'type' => $loan->loan_type,
                    'limit' => $loan->credit_limit,
                    'used' => $used,
                    'available' => $loan->credit_limit - $used,
                    'due_date' => $loan->due_date,
                    'interest' => $loan->interest_rate_monthly,
                    'percentage' => ($loan->credit_limit > 0) ? ($used / $loan->credit_limit) * 100 : 0
                ];
            });

        return Inertia::render('Loans/Index', [
            'loans' => $loans
        ]);
    }

    public function create()
    {
        return Inertia::render('Loans/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'loan_type' => 'required|in:credit_card,personal_loan,paylater',
            'credit_limit' => 'required|numeric|min:0',
            'interest_rate_monthly' => 'required|numeric|min:0',
            'due_date' => 'required|integer|min:1|max:31',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->loans()->create([
            'uuid' => Str::uuid(),
            'name' => $validated['name'],
            'loan_type' => $validated['loan_type'],
            'credit_limit' => $validated['credit_limit'],
            'interest_rate_monthly' => $validated['interest_rate_monthly'],
            'due_date' => $validated['due_date'],
        ]);

        return redirect()->route('loans.index')->with('success', 'Akun kredit berhasil ditambahkan');
    }
}
