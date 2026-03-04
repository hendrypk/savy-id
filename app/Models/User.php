<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\ResetPasswordNotification;
use App\Mail\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'email_verified_at',
        'inspiring_quote'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get all transaction categories created by the user.
     */
    public function transactionCategories(): HasMany
    {
        return $this->hasMany(TransactionCategory::class);
    }

    /**
     * Get all expenses recorded by the user.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get all loan/credit accounts owned by the user.
     */
    public function loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all wallet owned by the user.
     */
    public function wallets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Get all budget allocation owned by the user.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(BudgetAllocation::class);
    }

    /**
     * Calculate total balance across all wallets (Net Worth)
     */
    public function currentBalance(): float
    {
        return (float) $this->wallets()->sum('balance');
    }

    /**
     * Calculate total outstanding debt from active loans
     */
    public function totalDebt(): float
    {
        return (float) $this->loans()->where('status', 'active')->sum('remaining_amount');
    }

    /**
     * Calculate total budget
     */
    public function totalBudget(): float
    {
        return (float) $this->budgets()
            ->where('month_year', now()->format('Y-m'))
            ->sum('plan_amount');
    }


    /**
     * Get the monthly savings total (from budget allocations)
     */
    public function totalSavings(): float
    {
        return (float) $this->savings()->sum('current_amount');
    }

    public function savings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Saving::class);
    }

    /**
     * Access transactions through wallets (HasManyThrough)
     */
    public function walletTransactions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(WalletTransaction::class, Wallet::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function balanceAsOf(\DateTimeInterface $date)
    {
        // 1. Get current total balance (Equity)
        $currentBalance = $this->currentBalance();

        // 2. Calculate the sum of all transactions from the target date until now
        // We "reverse" these to find what the balance was back then
        $changes = $this->walletTransactions()
            ->where('transaction_date', '>', $date)
            ->get()
            ->reduce(function ($carry, $transaction) {
                // If it was an inflow (money in), we subtract it to go back in time
                if ($transaction->type->isInflow()) {
                    return $carry - $transaction->amount;
                } 
                // If it was an outflow (money out), we add it back
                return $carry + $transaction->amount;
            }, 0);

        return $currentBalance + $changes;
    }

    public function getGrowthPercentage()
    {
        $currentEquity = $this->currentBalance();
        
        $lastMonthEquity = $this->balanceAsOf(now()->subMonth()->endOfMonth());

        if ($lastMonthEquity <= 0) {
            return $currentEquity > 0 ? 100 : 0;
        }

        $growth = (($currentEquity - $lastMonthEquity) / $lastMonthEquity) * 100;

        return round($growth, 2);
    }


    public function getCurrentBudget()
    {
        $currentMonth = now()->format('Y-m');
        return $this->budgets()->where('month_year', $currentMonth)->get();
    }

    public function getBudgetUsage(): float
    {
        return (float) $this->budgets()
            ->where('month_year', now()->format('Y-m'))
            ->sum('used_amount');
    }

    public function getBudgetUsagePercentage(): float
    {
        $total = $this->totalBudget();
        $used = $this->getBudgetUsage();

        if ($total <= 0) {
            return 0.00;
        }

        $percentage = ($used / $total) * 100;

        return round($percentage, 2);
    }


}
