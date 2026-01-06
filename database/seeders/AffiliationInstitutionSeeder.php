<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AffiliationInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pivot data
        DB::table('affiliation_institution')->truncate();

        // Define specific affiliations for each institution
        $affiliations = [
            // === SCHOOLS ===
            // Budhanilkantha School - Affiliated with TU
            ['institution_id' => 1, 'affiliation_id' => 1], // TU

            // Little Angels' School - Affiliated with TU
            ['institution_id' => 2, 'affiliation_id' => 1], // TU

            // St. Xavier's School - Affiliated with TU
            ['institution_id' => 3, 'affiliation_id' => 1], // TU

            // GEMS School - Affiliated with TU
            ['institution_id' => 4, 'affiliation_id' => 1], // TU

            // Lincoln School - Affiliated with TU
            ['institution_id' => 5, 'affiliation_id' => 1], // TU

            // === COLLEGES ===
            // St. Xavier's College - Affiliated with TU and CTEVT
            ['institution_id' => 6, 'affiliation_id' => 1], // TU
            ['institution_id' => 6, 'affiliation_id' => 5], // CTEVT

            // Kathmandu Engineering College - Affiliated with KU
            ['institution_id' => 7, 'affiliation_id' => 2], // KU

            // Nepal College of Information Technology - Affiliated with Pokhara University
            ['institution_id' => 8, 'affiliation_id' => 3], // Pokhara University

            // Trinity International College - Affiliated with TU
            ['institution_id' => 9, 'affiliation_id' => 1], // TU

            // Prime College - Affiliated with TU
            ['institution_id' => 10, 'affiliation_id' => 1], // TU
        ];

        DB::table('affiliation_institution')->insert($affiliations);
    }
}
