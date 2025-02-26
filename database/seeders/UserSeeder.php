<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'widget_ids' => json_encode([1, 2, 3, 4, 5]),
        ]);

        // Create regular users with different widget access
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'widget_ids' => json_encode([1, 3]),
        ]);

        User::create([
            'name' => 'Marketing User',
            'email' => 'marketing@example.com',
            'password' => Hash::make('password'),
            'widget_ids' => json_encode([2, 4]),
        ]);

        // Create 10 random users with random widget access
        for ($i = 0; $i < 10; $i++) {
            $randomWidgets = array_slice([1, 2, 3, 4, 5], 0, rand(1, 5));
            User::create([
                'name' => 'User ' . ($i + 1),
                'email' => 'user' . ($i + 1) . '@example.com',
                'password' => Hash::make('password'),
                'widget_ids' => json_encode($randomWidgets),
            ]);
        }
    }
}
