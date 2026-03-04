<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WalletTransaction extends Model
{
    use SoftDeletes, BelongsToUser;
    
    protected $fillable = [
        'uuid', 
        'user_id', 
        'wallet_id', 
        'transaction_category_id',
        'description', 
        'amount', 
        'type', 
        'transaction_date',
        'reference_type',
        'reference_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'type' => TransactionType::class, // Link to your new Enum
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    /**
     * Auto-generate UUID on creation.
     */
    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->uuid = (string) Str::uuid();
        });
    }
    
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The Polymorphic Relationship.
     * This allows the transaction to belong to a Loan, Budget, or Saving.
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function wallet(): BelongsTo 
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo 
    {
        // Make sure the second argument matches your actual column name
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }
}