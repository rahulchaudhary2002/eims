<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Program::updateOrCreate(['slug' => 'bachelor-in-computer-application'], [
            'faculty_id' => 1,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Computer Application',
            'description' => 'Undergraduate program focused on software, databases, networks, and application development.',
            'is_active' => true,
        ]);

        Program::updateOrCreate(['slug' => 'bachelor-in-business-administration'], [
            'faculty_id' => 2,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Business Administration',
            'description' => 'Undergraduate program in management, accounting, marketing, and business operations.',
            'is_active' => true,
        ]);

        Program::updateOrCreate(['slug' => 'bachelor-in-commerce'], [
            'faculty_id' => 2,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Commerce',
            'description' => 'Undergraduate commerce program covering finance, accounting, economics, and business law.',
            'is_active' => true,
        ]);
    }
}
