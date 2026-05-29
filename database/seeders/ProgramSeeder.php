<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Program::updateOrCreate(['slug' => 'bachelor-in-computer-application'], [
            'faculty_id' => 1,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Computer Application',
            'description' => 'Undergraduate program focused on software, databases, networks, and application development.',
            'is_active' => true,
        ]);
        $this->syncCourses($program->id, [1, 2, 5, 9]);

        $program = Program::updateOrCreate(['slug' => 'bachelor-in-business-administration'], [
            'faculty_id' => 2,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Business Administration',
            'description' => 'Undergraduate program in management, accounting, marketing, and business operations.',
            'is_active' => true,
        ]);
        $this->syncCourses($program->id, [3, 4, 5, 8]);

        $program = Program::updateOrCreate(['slug' => 'bachelor-in-commerce'], [
            'faculty_id' => 2,
            'level' => 'Bachelor',
            'name' => 'Bachelor In Commerce',
            'description' => 'Undergraduate commerce program covering finance, accounting, economics, and business law.',
            'is_active' => true,
        ]);
        $this->syncCourses($program->id, [3, 4, 6, 7]);
    }

    private function syncCourses(int $programId, array $courseIds): void
    {
        DB::table('course_program')->where('program_id', $programId)->delete();

        DB::table('course_program')->insert(array_map(fn($courseId) => [
            'course_id' => $courseId,
            'program_id' => $programId,
            'created_at' => now(),
            'updated_at' => now(),
        ], $courseIds));
    }
}
