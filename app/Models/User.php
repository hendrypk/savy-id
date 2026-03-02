<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
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
        'phone'
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
}
