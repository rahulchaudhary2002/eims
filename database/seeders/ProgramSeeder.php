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
        Program::create([
            'name' => 'Bachelor In Computer Application',
            'slug' => 'bachelor-in-computer-application',
            'level_id' => 1,
            'affiliation_id' => 1,
            'category_id' => 1,
            'duration' => '3 years',
            'is_active' => true,
        ]);

        Program::first()->courses()->attach([1, 2, 5, 9]);
    }
}
