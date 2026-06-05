<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $verifiedBy = User::where('is_super_admin', true)->first();

        Application::where('status', 'admitted')->get()->each(function (Application $application) use ($verifiedBy): void {
            Admission::updateOrCreate([
                'application_id' => $application->id,
            ], [
                'student_id' => $application->student_id,
                'institution_id' => $application->institution_id,
                'applicable_type' => $application->applicable_type,
                'applicable_id'   => $application->applicable_id,
                'admission_number' => 'ADM-' . now()->format('Y') . '-' . str_pad((string) $application->id, 5, '0', STR_PAD_LEFT),
                'admission_date' => now()->toDateString(),
                'paid_amount' => 25000,
                'payment_proof' => null,
                'verification_status' => 'verified',
                'verified_by' => $verifiedBy?->id,
                'verified_at' => now(),
                'remarks' => 'Seed admission generated from admitted application.',
            ]);
        });
    }
}
