<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            AffiliationSeeder::class,
            FacultySeeder::class,
            InstitutionSeeder::class,
            InstitutionUserSeeder::class,
            StudentSeeder::class,
            CourseSeeder::class,
            AffiliationInstitutionSeeder::class,
            CourseDescriptionSeeder::class,
            QuestionSeeder::class,
            ReplySeeder::class,
            ProgramSeeder::class,
            InstitutionProgramSeeder::class,
            InstitutionProgramSubjectSeeder::class,
            ScholarshipSeeder::class,
            ApplicationSeeder::class,
            AdmissionSeeder::class,
        ]);
    }
}
