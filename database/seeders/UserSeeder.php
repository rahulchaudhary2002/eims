<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users except maybe admin
        // User::where('email', '!=', 'admin@example.com')->delete();

        $users = [
            // Original user
            [
                'name' => 'User',
                'email' => 'user@app.com',
                'phone' => '1234567890',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 1
            [
                'name' => 'John Doe',
                'email' => 'john.doe@app.com',
                'phone' => '9876543210',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 2
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@app.com',
                'phone' => '9841234567',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 3
            [
                'name' => 'Rajesh Sharma',
                'email' => 'rajesh.sharma@app.com',
                'phone' => '9851122334',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 4
            [
                'name' => 'Sita Gurung',
                'email' => 'sita.gurung@app.com',
                'phone' => '9863344556',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
