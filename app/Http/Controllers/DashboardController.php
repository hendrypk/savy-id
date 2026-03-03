<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $fullQuote = $user->inspiring_quote ?? ''; // ambil dari DB

        // Pecah quote dan author
        $quote = '';
        $author = '';

        if (!empty($fullQuote) && str_contains($fullQuote, '—')) {
            [$quotePart, $authorPart] = explode('—', $fullQuote, 2);
            $quote = trim($quotePart);
            $author = trim($authorPart);
        } else {
            $quote = $fullQuote; // fallback
        }

        return Inertia::render('Dashboard', [
            'inspiringQuote' => [
                'text' => $quote,
                'author' => $author,
            ],
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