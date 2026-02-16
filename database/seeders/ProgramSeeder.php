<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Program::create([
            'name' => 'Bachelor In Computer Application',
            'slug' => 'bachelor-in-computer-application',
            'level_id' => 1,
            'affiliation_id' => 1,
            'category_id' => 1,
            'duration' => '3 years',
            'fee' => 550000,
            'is_active' => true,
        ]);
        $program->courses()->attach([1, 2, 5, 9]);

        $program = Program::create([
            'name' => 'Bachelor In Business Administration',
            'slug' => 'bachelor-in-business-administration',
            'level_id' => 1,
            'affiliation_id' => 1,
            'category_id' => 1,
            'duration' => '3 years',
            'fee' => 350000,
            'is_active' => true,
        ]);
        $program->courses()->attach([3, 4, 5, 8]);

        $program = Program::create([
            'name' => 'Bachelor In Commerce',
            'slug' => 'bachelor-in-commerce',
            'level_id' => 1,
            'affiliation_id' => 1,
            'category_id' => 1,
            'duration' => '3 years',
            'fee' => 400000,
            'is_active' => true,
        ]);
        $program->courses()->attach([3, 4, 6, 7]);
    }
}
