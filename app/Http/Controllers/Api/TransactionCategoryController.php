<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionCategoryResource;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TransactionCategoryResource::collection(
            TransactionCategory::orderBy('name')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
            'icon'  => 'required|string',
        ]);

        $category = TransactionCategory::create($validated);

        return new TransactionCategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionCategory $transactionCategory)
    {
        return new TransactionCategoryResource($transactionCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->is_system) {
            return response()->json(['message' => 'System category cannot be edited.'], 403);
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
            'icon'  => 'required|string',
        ]);

        $transactionCategory->update($validated);

        return new TransactionCategoryResource($transactionCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->is_system) {
            return response()->json(['message' => 'System category cannot be deleted.'], 403);
        }

        $transactionCategory->delete();

        return response()->json(null, 204);
    }
}
