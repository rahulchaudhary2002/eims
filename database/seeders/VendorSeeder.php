<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::create([
            'name' => 'Vendor',
            'email' => 'vendor@app.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => Hash::make('123456789'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
