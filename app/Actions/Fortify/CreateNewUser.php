<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user with default categories.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {
            // 1. Create the User
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'], 
                'phone' => $input['phone']
            ]);

            // 2. Generate Default Categories for this specific user
            $this->seedDefaultCategories($user);

            return $user;
        });
    }

    /**
     * Internal helper to seed categories for a specific tenant (user).
     */
    public function seedDefaultCategories(User $user): void
    {
        $categories = [
            ['name' => 'Bayar Pinjaman', 'icon' => 'banknotes', 'color' => '#4f46e5', 'type' => 'expense', 'is_system' => 1],
            ['name' => 'Makanan & Minuman', 'icon' => 'cake', 'color' => '#ef4444', 'type' => 'expense', 'is_system' => 0],
            ['name' => 'Transportasi', 'icon' => 'truck', 'color' => '#f59e0b', 'type' => 'expense', 'is_system' => 0],
            ['name' => 'Gaji & Pendapatan', 'icon' => 'currency-dollar', 'color' => '#10b981', 'type' => 'income', 'is_system' => 0],
        ];

        
        foreach ($categories as $cat) {
            $slug = Str::slug($cat['name']) . '-' . $user->id;

            TransactionCategory::firstOrCreate(
                ['slug' => $slug], 
                [
                    'user_id'   => $user->id,
                    'uuid'      => (string) Str::uuid(),
                    'name'      => $cat['name'],
                    'icon'      => $cat['icon'],
                    'color'     => $cat['color'],
                    'type'      => $cat['type'],
                    'is_system' => $cat['is_system'],
                ]
            );
        }
    }
}