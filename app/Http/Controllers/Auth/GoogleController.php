<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
            ],

        );
            $this->seedDefaultCategories($user);

        Auth::login($user);

        return redirect()->route('dashboard');
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