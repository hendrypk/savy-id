<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BudgetController extends Controller
{
    public function index()
    {
        return Inertia::render('Budget/Index', [
            'categories' => auth()->user()->transactionCategories()
                ->with(['allocations' => function($query) {
                    $query->where('month_year', now()->format('Y-m'));
                }])
                ->get()
                ->map(function ($cat) {
                    $allocation = $cat->allocations->first();
                    $plan = $allocation ? $allocation->plan_amount : 0;
                    $spent = 0;

                    return [
                        'uuid' => $cat->uuid,
                        'name' => $cat->name,
                        'type' => $cat->type,
                        'plan_amount' => $plan,
                        'spent_amount' => $spent,
                        'remaining' => $plan - $spent,
                        'percentage' => $plan > 0 ? ($spent / $plan) * 100 : 0
                    ];
                })
        ]);
    }
}
