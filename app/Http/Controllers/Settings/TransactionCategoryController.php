<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionCategoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();        
        return Inertia::render('settings/TransactionCategories/Index', [
            'categories' => TransactionCategory::where('user_id', $userId)
                ->orderBy('name')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('settings/TransactionCategories/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
            'icon'  => 'required|string',
        ]);

        // Laravel will automatically fill user_id, uuid, and slug via the Model boot method
        $request->user()->transactionCategories()->create($validated);

        return redirect()->route('transaction-categories.index');
    }

    public function edit(TransactionCategory $transactionCategory)
    {
        return Inertia::render('settings/TransactionCategories/Edit', [
            'category' => $transactionCategory
        ]);
    }

    public function update(Request $request, TransactionCategory $transactionCategory)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
            'icon'  => 'required|string',
        ]);

        $transactionCategory->update($validated);

        return redirect()->route('transaction-categories.index');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        $transactionCategory->delete();

        return redirect()->route('transaction-categories.index');
    }
}
