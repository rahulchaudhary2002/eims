<?php

namespace Database\Seeders;

use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        InstitutionProgram::with(['institution', 'program'])->limit(6)->get()->each(function (InstitutionProgram $institutionProgram): void {
            $title = ($institutionProgram->institution->name ?? 'Institution') . ' Merit Scholarship';

            Scholarship::updateOrCreate([
                'slug' => Str::slug($title . '-' . $institutionProgram->id),
            ], [
                'institution_id' => $institutionProgram->institution_id,
                'institution_program_id' => $institutionProgram->id,
                'type' => 'merit_based',
                'title' => $title,
                'description' => 'Merit scholarship for qualified applicants in ' . ($institutionProgram->program->name ?? 'selected program') . '.',
                'minimum_gpa' => 3.00,
                'minimum_percentage' => 70,
                'benefit_type' => 'percentage',
                'benefit_value' => 20,
                'total_slots' => 10,
                'used_slots' => 1,
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(60)->toDateString(),
                'status' => 'active',
            ]);
        });

        $institutionProgram = InstitutionProgram::with('institution')->first();

        if ($institutionProgram) {
            Scholarship::updateOrCreate([
                'slug' => 'platform-cashback-campaign-' . $institutionProgram->id,
            ], [
                'institution_id' => $institutionProgram->institution_id,
                'institution_program_id' => $institutionProgram->id,
                'type' => 'platform_cashback',
                'title' => 'Platform Cashback Campaign',
                'description' => 'Platform cashback campaign for early applicants.',
                'minimum_gpa' => null,
                'minimum_percentage' => null,
                'benefit_type' => 'fixed_amount',
                'benefit_value' => 5000,
                'total_slots' => 25,
                'used_slots' => 2,
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'status' => 'active',
            ]);
        }
    }
}
