<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Seeder;

class InstitutionUserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('email', 'manager@app.com')->first();
        $officer = User::where('email', 'admissions@app.com')->first();

        $primaryInstitution = Institution::where('slug', 'st-xaviers-college')->first();
        $secondaryInstitution = Institution::where('slug', 'nepal-college-of-information-technology')->first();

        if ($manager && $primaryInstitution) {
            $manager->institutions()->syncWithoutDetaching([
                $primaryInstitution->id => [
                    'role'       => 'manager',
                    'is_primary' => true,
                    'is_active'  => true,
                    'joined_at'  => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if ($officer && $secondaryInstitution) {
            $officer->institutions()->syncWithoutDetaching([
                $secondaryInstitution->id => [
                    'role'       => 'admission_officer',
                    'is_primary' => true,
                    'is_active'  => true,
                    'joined_at'  => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
