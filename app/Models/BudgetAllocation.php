<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class BudgetAllocation extends Model
{
    use SoftDeletes, BelongsToUser;

    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'transaction_category_id',
        'loan_id',
        'plan_amount',
        'month_year',
        'is_settled'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'plan_amount' => 'float',
        'is_settled'  => 'boolean',
    ];

    // --- Relationships ---

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(WalletTransaction::class, 'reference');
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // --- Accessors ---

    /**
     * Get actual spending for this specific budget instance
     */
    public function getUsedAmountAttribute(): float
    {
        if (array_key_exists('spent_amount', $this->attributes)) {
            return (float) $this->attributes['spent_amount'];
        }

        return (float) $this->transactions()
            ->whereIn('type', ['budget_spending', 'expense'])
            ->sum('amount');
    }

    /**
     * Get remaining balance for this budget instance
     */
    public function getRemainingAmountAttribute(): float
    {
        return $this->plan_amount - $this->used_amount;
    }

    // --- Static Logic for Dashboard & Analysis ---

    /**
     * Total planned budget for a specific month
     */
    public static function totalPlanned(int $userId, string $monthYear): float
    {
        return (float) self::where('user_id', $userId)
            ->where('month_year', $monthYear)
            ->sum('plan_amount');
    }

    /**
     * Total actual spending for a specific month (Aggregated)
     */
    public static function totalSpent(int $userId, string $monthYear): float
    {
        return (float) WalletTransaction::where('user_id', $userId)
            ->whereIn('type', ['budget_spending', 'expense'])
            ->whereHasMorph('reference', [self::class], function ($query) use ($monthYear) {
                $query->where('month_year', $monthYear);
            })
            ->sum('amount');
    }

    /**
     * Usage percentage for a specific month
     */
    public static function usagePercentage(int $userId, string $monthYear): float
    {
        $planned = self::totalPlanned($userId, $monthYear);
        $spent = self::totalSpent($userId, $monthYear);

        return $planned > 0 ? round(($spent / $planned) * 100, 2) : 0;
    }

    /**
     * Monthly Analysis (Comparison with previous month)
     */
    public static function getMonthlyAnalysis(int $userId, string $monthYear): array
    {
        $currentSpent = self::totalSpent($userId, $monthYear);
        
        // Calculate previous month string
        $prevMonth = Carbon::parse($monthYear)->subMonth()->format('Y-m');
        $lastMonthSpent = self::totalSpent($userId, $prevMonth);
        if ($lastMonthSpent <= 0) {
            return [
                'value' => 0,
                'status' => 'stable',
                'message' => 'No comparison data available from last month.'
            ];
        }

        $diff = $lastMonthSpent - $currentSpent;
        $percentage = round(($diff / $lastMonthSpent) * 100);

        return [
            'value'   => (float) abs($percentage),
            'status'  => $percentage >= 0 ? 'saving' : 'spending',
            'message' => $percentage >= 0 
                ? "You saved " . abs($percentage) . "% more than last month! 🥳" 
                : "Your spending increased by " . abs($percentage) . "% compared to last month. 💸"
        ];
    }
}