<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TransactionCategory extends Model
{
    use HasFactory, SoftDeletes; // Removed HasUuids

    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'slug',
        'icon',
        'color',
        'type',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            // 1. Manually assign UUID to the 'uuid' column
            $category->uuid = (string) Str::uuid();
            
            // 2. Generate the slug
            $category->slug = Str::slug($category->name);
            
            // 3. Set default color if empty
            if (!$category->color) {
                $category->color = '#4f46e5';
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Use the 'uuid' column for Route Model Binding
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the budget allocations for this category.
     */
    public function allocations(): HasMany
    {
        // Pastikan nama model 'BudgetAllocation' sesuai dengan file kamu
        return $this->hasMany(BudgetAllocation::class, 'transaction_category_id');
    }
}