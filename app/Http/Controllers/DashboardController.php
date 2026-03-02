<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_equity' => $user->currentBalance(),
                'total_debt' => $user->totalDebt(),
                'monthly_savings' => $user->targetSavings(),
                'growth_percentage' => 12,
            ],
            'recentTransactions' => $user->walletTransactions()
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($tx) => [
                    'id' => $tx->id,
                    'title' => $tx->description,
                    'category' => ucfirst($tx->type),
                    'amount' => $tx->amount,
                    'type' => ($tx->type === 'income' || $tx->type === 'in') ? 'in' : 'out',
                    'date' => $tx->created_at->diffForHumans(),
                ]),
            'activeLoans' => $user->loans()
                ->where('status', 'active')
                ->get()
                ->map(fn($loan) => [
                    'id' => $loan->id,
                    'provider' => $loan->name,
                    'amount' => $loan->remaining_amount,
                ]),
        ]);
    }
}