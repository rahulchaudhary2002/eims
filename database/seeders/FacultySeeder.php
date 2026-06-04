<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Information Technology', 'slug' => 'information-technology'],
            ['name' => 'Management', 'slug' => 'management'],
            ['name' => 'Science', 'slug' => 'science'],
        ] as $faculty) {
            Faculty::updateOrCreate(['slug' => $faculty['slug']], $faculty + ['is_active' => true]);
        }
    }
}
