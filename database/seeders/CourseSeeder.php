<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'C Programming',
                'code' => 'CPROG',
                'description' => 'Introductory course on C programming language and software development.',
                'is_active' => true,
            ],
            [
                'name' => 'Data Structures',
                'code' => 'DS',
                'description' => 'Course covering fundamental data structures and their applications.',
                'is_active' => true,
            ],
            [
                'name' => 'Database Management Systems',
                'code' => 'DBMS',
                'description' => 'Course on database design, SQL, and database management systems.',
                'is_active' => true,
            ],
            [
                'name' => 'Operating Systems',
                'code' => 'OS',
                'description' => 'Course on operating system concepts, design, and implementation.',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Networks',
                'code' => 'CN',
                'description' => 'Course on computer networking principles, protocols, and technologies.',
                'is_active' => true,
            ],
            [
                'name' => 'Software Engineering',
                'code' => 'SE',
                'description' => 'Course on software development methodologies, project management, and best practices.',
                'is_active' => true,
            ],
            [
                'name' => 'Web Development',
                'code' => 'WEBDEV',
                'description' => 'Course on web development technologies, frameworks, and best practices.',
                'is_active' => true,
            ],
            [
                'name' => 'Mobile App Development',
                'code' => 'MOBILEDEV',
                'description' => 'Course on mobile application development for Android and iOS platforms.',
                'is_active' => true,
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'AI',
                'description' => 'Course on artificial intelligence concepts, techniques, and applications.',
                'is_active' => true,
            ],
            [
                'name' => 'Machine Learning',
                'code' => 'ML',
                'description' => 'Course on machine learning algorithms, models, and applications.',
                'is_active' => true,
            ],
            [
                'name' => 'Cybersecurity',
                'code' => 'CYBERSEC',
                'description' => 'Course on cybersecurity principles, threats, and defense mechanisms.',
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Computing',
                'code' => 'CLOUD',
                'description' => 'Course on cloud computing concepts, services, and deployment models.',
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'code' => 'DATASCI',
                'description' => 'Course on data science techniques, tools, and applications.',
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
