<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // === Technical/Engineering Courses ===
            [
                'name' => 'Bachelor in Computer Engineering',
                'code' => 'BCT',
                'description' => 'Four-year undergraduate program focusing on computer hardware and software engineering.',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor in Civil Engineering',
                'code' => 'BCE',
                'description' => 'Four-year undergraduate program in civil engineering and infrastructure development.',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor in Information Technology',
                'code' => 'BIT',
                'description' => 'Four-year undergraduate program focusing on information technology and software development.',
                'is_active' => true,
            ],

            // === Management Courses ===
            [
                'name' => 'Bachelor of Business Studies',
                'code' => 'BBS',
                'description' => 'Four-year undergraduate program in business administration and management.',
                'is_active' => true,
            ],
            [
                'name' => 'Master of Business Administration',
                'code' => 'MBA',
                'description' => 'Two-year postgraduate program in business administration and management.',
                'is_active' => true,
            ],

            // === Science Courses ===
            [
                'name' => 'Bachelor of Science in Physics',
                'code' => 'BSC-PHYSICS',
                'description' => 'Four-year undergraduate program in physics and applied sciences.',
                'is_active' => true,
            ],
            [
                'name' => 'Bachelor of Science in Mathematics',
                'code' => 'BSC-MATH',
                'description' => 'Four-year undergraduate program in pure and applied mathematics.',
                'is_active' => true,
            ],

            // === Diploma/Certificate Courses ===
            [
                'name' => 'Diploma in Computer Engineering',
                'code' => 'DCTEVT',
                'description' => 'Three-year diploma program in computer engineering under CTEVT.',
                'is_active' => true,
            ],
            [
                'name' => 'Certificate in Hotel Management',
                'code' => 'CHM',
                'description' => 'One-year certificate program in hotel management and hospitality.',
                'is_active' => true,
            ],

            // === Master Level Courses ===
            [
                'name' => 'Master of Science in Computer Science',
                'code' => 'MSC-CS',
                'description' => 'Two-year postgraduate program in computer science and research.',
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
