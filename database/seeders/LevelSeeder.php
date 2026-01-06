<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Certificate Level',
                'code' => 'CERT',
                'description' => 'Basic certificate level programs typically lasting 6 months to 1 year.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Diploma Level',
                'code' => 'DIP',
                'description' => 'Diploma programs usually 1-2 years in duration with practical focus.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor Level',
                'code' => 'BACHELOR',
                'description' => 'Undergraduate degree programs typically 3-4 years in duration.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Master Level',
                'code' => 'MASTER',
                'description' => 'Postgraduate degree programs usually 1-2 years after bachelor.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'PhD Level',
                'code' => 'PHD',
                'description' => 'Doctoral research programs for advanced academic studies.',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($levels as $level) {
            Level::create($level);
        }
    }
}
