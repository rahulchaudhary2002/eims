<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Level;
use App\Models\Affiliation;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Get levels and affiliations for reference
        $levels = Level::all()->keyBy('id');
        $affiliations = Affiliation::all()->keyBy('id');

        $courses = [
            // === Technical/Engineering Courses ===
            [
                'name' => 'Bachelor in Computer Engineering',
                'code' => 'BCT',
                'description' => 'Four-year undergraduate program focusing on computer hardware and software engineering.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '4 years',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor in Civil Engineering',
                'code' => 'BCE',
                'description' => 'Four-year undergraduate program in civil engineering and infrastructure development.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '4 years',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor in Information Technology',
                'code' => 'BIT',
                'description' => 'Four-year undergraduate program focusing on information technology and software development.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'KU')->id ?? 2,
                'duration' => '4 years',
                'is_active' => true,
            ],

            // === Management Courses ===
            [
                'name' => 'Bachelor of Business Studies',
                'code' => 'BBS',
                'description' => 'Four-year undergraduate program in business administration and management.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '4 years',
                'is_active' => true,
            ],
            [
                'name' => 'Master of Business Administration',
                'code' => 'MBA',
                'description' => 'Two-year postgraduate program in business administration and management.',
                'level_id' => $levels->firstWhere('code', 'MASTER')->id ?? 4,
                'affiliation_id' => $affiliations->firstWhere('code', 'KU')->id ?? 2,
                'duration' => '2 years',
                'is_active' => true,
            ],

            // === Science Courses ===
            [
                'name' => 'Bachelor of Science in Physics',
                'code' => 'BSC-PHYSICS',
                'description' => 'Four-year undergraduate program in physics and applied sciences.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '4 years',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor of Science in Mathematics',
                'code' => 'BSC-MATH',
                'description' => 'Four-year undergraduate program in pure and applied mathematics.',
                'level_id' => $levels->firstWhere('code', 'BACHELOR')->id ?? 3,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '4 years',
                'is_active' => true,
            ],

            // === Diploma/Certificate Courses ===
            [
                'name' => 'Diploma in Computer Engineering',
                'code' => 'DCTEVT',
                'description' => 'Three-year diploma program in computer engineering under CTEVT.',
                'level_id' => $levels->firstWhere('code', 'DIP')->id ?? 2,
                'affiliation_id' => $affiliations->firstWhere('code', 'CTEVT')->id ?? 5,
                'duration' => '3 years',
                'is_active' => true,
            ],
            [
                'name' => 'Certificate in Hotel Management',
                'code' => 'CHM',
                'description' => 'One-year certificate program in hotel management and hospitality.',
                'level_id' => $levels->firstWhere('code', 'CERT')->id ?? 1,
                'affiliation_id' => $affiliations->firstWhere('code', 'CTEVT')->id ?? 5,
                'duration' => '1 year',
                'is_active' => true,
            ],

            // === Master Level Courses ===
            [
                'name' => 'Master of Science in Computer Science',
                'code' => 'MSC-CS',
                'description' => 'Two-year postgraduate program in computer science and research.',
                'level_id' => $levels->firstWhere('code', 'MASTER')->id ?? 4,
                'affiliation_id' => $affiliations->firstWhere('code', 'TU')->id ?? 1,
                'duration' => '2 years',
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
