<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\InstitutionProgram;
use Illuminate\Database\Seeder;

class InstitutionProgramSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = Institution::all();

        foreach ($institutions as $institution) {
            foreach ([1, 2] as $programId) {
                InstitutionProgram::updateOrCreate([
                    'institution_id' => $institution->id,
                    'program_id' => $programId,
                ], [
                    'title' => null,
                    'admission_fee' => 25000,
                    'monthly_fee' => 12000,
                    'semester_fee' => 65000,
                    'annual_fee' => 120000,
                    'total_fee' => 480000,
                    'duration_months' => 48,
                    'total_seats' => 48,
                    'available_seats' => 32,
                    'minimum_gpa' => 2.50,
                    'minimum_percentage' => 55,
                    'admission_start_date' => now()->subDays(10)->toDateString(),
                    'admission_end_date' => now()->addDays(45)->toDateString(),
                    'status' => 'open',
                ]);
            }
        }
    }
}
