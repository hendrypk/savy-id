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
        $user = User::first();

        if (!$user) {
            $this->command->error('No user found. Please run UserSeeder first.');
            return;
        }

        $categories = [
            [
                'name' => 'Bayar Pinjaman',
                'icon' => 'banknotes',
                'color' => '#4f46e5', // Indigo
                'type' => 'expense',
                'is_system' => 1, // Marked as system because your logic relies on this name
            ],
            [
                'name' => 'Makanan & Minuman',
                'icon' => 'cake',
                'color' => '#ef4444', // Red
                'type' => 'expense',
                'is_system' => 0,
            ],
            [
                'name' => 'Transportasi',
                'icon' => 'truck',
                'color' => '#f59e0b', // Amber
                'type' => 'expense',
                'is_system' => 0,
            ],
            [
                'name' => 'Gaji & Pendapatan',
                'icon' => 'currency-dollar',
                'color' => '#10b981', // Emerald
                'type' => 'income',
                'is_system' => 0,
            ],
        ];

        foreach ($categories as $cat) {
            TransactionCategory::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'slug' => Str::slug($cat['name']),
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                    'type' => $cat['type'],
                    'is_system' => $cat['is_system'],
                ]
            );
        }

        $this->command->info('Transaction categories seeded successfully!');
    }
}