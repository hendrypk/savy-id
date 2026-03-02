<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::first();
        
        if ($user) {
            (new \App\Actions\Fortify\CreateNewUser())->seedDefaultCategories($user);
            $this->command->info('Default categories seeded for: ' . $user->email);
        } else {
            $this->command->error('No user found to seed categories for.');
        }

    }
}