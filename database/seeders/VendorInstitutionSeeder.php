<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pivot data
        DB::table('vendor_institution')->truncate();

        // Get the vendor (assuming ID 1)
        $vendorId = 1; // Your existing vendor ID

        // Define which institutions this vendor serves
        // For example: assign to all colleges only (ID 6-10)
        // $institutionIds = [6, 7, 8, 9, 10]; // College IDs

        // Or assign to specific schools and colleges:
        $institutionIds = [1, 3, 6, 8, 10]; // Mix of schools and colleges

        $pivotData = [];
        $now = now();

        foreach ($institutionIds as $institutionId) {
            $pivotData[] = [
                'vendor_id' => $vendorId,
                'institution_id' => $institutionId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('vendor_institution')->insert($pivotData);
    }
}
