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
        try {
            // Retrieve user data from Google
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed.');
        }

        /**
         * updateOrCreate will:
         * 1. Create a new user if the email doesn't exist (Registration).
         * 2. Update the google_id and verification status if the email exists (Login).
         */
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                // Ensure the user is marked as verified since they came from a trusted provider
                'email_verified_at' => isset($googleUser->user['email_verified']) && $googleUser->user['email_verified'] 
                    ? now() 
                    : null,
            ]
        );

        // 1. REGISTRATION LOGIC
        // This runs ONLY the first time the account is created
        if ($user->wasRecentlyCreated) {
            $this->seedDefaultCategories($user);
            
            // You could also create a default wallet here
            // $user->wallets()->create(['name' => 'Main Wallet', 'balance' => 0]);

            session()->flash('status', 'Welcome! Your account has been successfully created.');
        } 
        
        // 2. LOGIN LOGIC
        // This runs for returning users
        else {
            session()->flash('status', 'Welcome back!');
        }

        // Log the user in
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