<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        return Inertia::render('wallets/Index', [
            'wallets' => Auth::user()->wallets()->latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('wallets/Create', [
            'types' => Wallet::TYPES
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:cash,bank,kartu kredit,e wallet',
            'balance' => 'required|numeric',
            'color' => 'required|string',
        ]);

        Auth::user()->wallets()->create($validated);
        return redirect()->route('wallets.index');
    }

    public function edit(Wallet $wallet)
    {
        return Inertia::render('wallets/Edit', [
            'types' => Wallet::TYPES,
            'wallet' => [
                'uuid'    => $wallet->uuid,
                'name'    => $wallet->name,
                'type'    => $wallet->type,
                'balance' => $wallet->balance,
                'color'   => $wallet->color,
            ]
        ]);
    }

    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'balance' => 'required|numeric',
            'color' => 'required|string',
        ]);

        $wallet->update($validated);
        return redirect()->route('wallets.show', $wallet->uuid);
    }

    public function show(Wallet $wallet)
    {
        return inertia('wallets/Show', [
            'wallet' => $wallet,
            'transactions' => $wallet->transactions() 
                ->latest()
                ->limit(20)
                ->get()
        ]);
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        return redirect()->route('wallets.index');
    }
}
