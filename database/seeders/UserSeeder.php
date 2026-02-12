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
                'first_name' => 'Student',
                'last_name' => 'User',
                'email' => 'user@app.com',
                'phone' => '1234567890',
                'dob' => '2000-01-01',
                'address' => '123 Main St',
                'education_level' => 'Bachelor',
                'field_of_interest' => 'Computer Science',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 1
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@app.com',
                'phone' => '9876543210',
                'dob' => '1990-05-15',
                'address' => '456 Elm St',
                'education_level' => 'Master',
                'field_of_interest' => 'Data Science',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 2
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@app.com',
                'phone' => '9841234567',
                'dob' => '1992-07-20',
                'address' => '789 Pine St',
                'education_level' => 'Bachelor',
                'field_of_interest' => 'Software Engineering',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 3
            [
                'first_name' => 'Rajesh',
                'last_name' => 'Sharma',
                'email' => 'rajesh.sharma@app.com',
                'phone' => '9851122334',
                'dob' => '1988-03-10',
                'address' => '321 Oak St',
                'education_level' => 'PhD',
                'field_of_interest' => 'Artificial Intelligence',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Additional user 4
            [
                'first_name' => 'Sita',
                'last_name' => 'Gurung',
                'email' => 'sita.gurung@app.com',
                'phone' => '9863344556',
                'dob' => '1995-09-25',
                'address' => '654 Maple St',
                'education_level' => 'Master',
                'field_of_interest' => 'Cybersecurity',
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
