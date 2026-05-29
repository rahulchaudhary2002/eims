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
            [
                'name' => 'Super Admin',
                'email' => 'admin@app.com',
                'phone' => '1234567890',
                'is_super_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Institution Manager',
                'email' => 'manager@app.com',
                'phone' => '9876543210',
                'is_super_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Admissions Officer',
                'email' => 'admissions@app.com',
                'phone' => '9841234567',
                'is_super_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
