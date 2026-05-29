<?php

namespace Database\Seeders;

use App\Models\InstitutionProgram;
use App\Models\InstitutionProgramSubject;
use Illuminate\Database\Seeder;

class InstitutionProgramSubjectSeeder extends Seeder
{
    public function run(): void
    {
        InstitutionProgram::with('program')->get()->each(function (InstitutionProgram $institutionProgram): void {
            $subjects = str_contains(strtolower($institutionProgram->program?->name ?? ''), 'business')
                ? ['Principles of Management', 'Business Communication', 'Accounting', 'Business Statistics']
                : ['Programming Fundamentals', 'Database Systems', 'Computer Networks', 'Software Engineering'];

            foreach ($subjects as $index => $subject) {
                InstitutionProgramSubject::updateOrCreate([
                    'institution_program_id' => $institutionProgram->id,
                    'subject_name' => $subject,
                ], [
                    'is_optional' => $index === 3,
                ]);
            }
        });
    }
}
