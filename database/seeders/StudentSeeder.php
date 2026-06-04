<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Aarav Sharma',
                'email' => 'aarav@student.com',
                'phone' => '9800000001',
                'date_of_birth' => '2004-04-12',
                'gender' => 'male',
            ],
            [
                'name' => 'Nisha Gurung',
                'email' => 'nisha@student.com',
                'phone' => '9800000002',
                'date_of_birth' => '2005-08-21',
                'gender' => 'female',
            ],
        ] as $student) {
            Student::updateOrCreate(['email' => $student['email']], $student + [
                'password' => Hash::make('password123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
