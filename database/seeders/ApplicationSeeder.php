<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::orderBy('id')->get();
        $institutionPrograms = InstitutionProgram::with('institution')->orderBy('id')->limit(2)->get();
        $changedBy = User::where('is_super_admin', true)->first();

        foreach ($students as $index => $student) {
            $institutionProgram = $institutionPrograms[$index] ?? $institutionPrograms->first();

            if (! $institutionProgram) {
                continue;
            }

            $scholarship = Scholarship::where('institution_program_id', $institutionProgram->id)->first();
            $status = $index === 0 ? 'admitted' : 'submitted';

            $application = Application::updateOrCreate([
                'application_number' => 'APP-' . now()->format('Y') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
            ], [
                'student_id' => $student->id,
                'institution_id' => $institutionProgram->institution_id,
                'applicable_type' => \App\Models\InstitutionProgram::class,
                'applicable_id'   => $institutionProgram->id,
                'scholarship_id' => $scholarship?->id,
                'source' => $scholarship ? 'scholarship' : 'direct',
                'status' => $status,
                'student_message' => 'I am interested in this program and would like to apply.',
                'institution_remarks' => $status === 'admitted' ? 'Applicant meets admission requirements.' : null,
                'admin_remarks' => null,
                'submitted_at' => now()->subDays(3),
                'reviewed_at' => $status === 'admitted' ? now()->subDays(2) : null,
                'referred_at' => null,
                'admitted_at' => $status === 'admitted' ? now()->subDay() : null,
            ]);

            ApplicationStatusLog::updateOrCreate([
                'application_id' => $application->id,
                'from_status' => null,
                'to_status' => 'submitted',
            ], [
                'changed_by_type' => $changedBy ? User::class : null,
                'changed_by_id' => $changedBy?->id,
                'remarks' => 'Application submitted.',
            ]);

            if ($status === 'admitted') {
                ApplicationStatusLog::updateOrCreate([
                    'application_id' => $application->id,
                    'from_status' => 'submitted',
                    'to_status' => 'admitted',
                ], [
                    'changed_by_type' => $changedBy ? User::class : null,
                    'changed_by_id' => $changedBy?->id,
                    'remarks' => 'Application admitted after review.',
                ]);
            }
        }
    }
}
