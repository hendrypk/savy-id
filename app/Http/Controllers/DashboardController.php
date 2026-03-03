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
                'monthly_savings' => $user->totalSavings(),
                'growth_percentage' => 12, // Ini bisa dibuat dinamis nanti
            ],
            'recentTransactions' => $user->walletTransactions()
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($tx) => [
                    'id' => $tx->id,
                    'uuid' => $tx->uuid,
                    'title' => $tx->description,
                    'category' => $tx->type->label(),
                    'amount' => $tx->amount,
                    'type' => $tx->type->isInflow() ? 'in' : 'out',
                    'date' => $tx->created_at->diffForHumans(),
                ]),
            'activeLoans' => $user->loans()
                ->where('status', 'active')
                ->get()
                ->map(fn($loan) => [
                    'id' => $loan->id,
                    'provider' => $loan->provider, // Pastikan menggunakan 'provider' sesuai tabel
                    'amount' => $loan->remaining_amount,
                ]),
        ]);
    }
}