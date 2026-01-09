<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enquiry;
use App\Models\Institution;

class EnquirySeeder extends Seeder
{
    public function run(): void
    {
        $institutions = Institution::all();

        if ($institutions->isEmpty()) {
            return;
        }

        $enquiries = [
            [
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+977-9841000001',
                'message' => 'I would like to know more about the admission process.',
                'type' => 'admission',
                'status' => 'pending',
            ],
            [
                'full_name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+977-9841000002',
                'message' => 'Need information about available courses.',
                'type' => 'course',
                'status' => 'read',
            ],
            [
                'full_name' => 'Robert Johnson',
                'email' => 'robert@example.com',
                'phone' => '+977-9841000003',
                'message' => 'Fee structure for MBA program.',
                'type' => 'fee',
                'status' => 'replied',
            ],
        ];

        foreach ($enquiries as $enquiryData) {
            $institution = $institutions->random();

            Enquiry::create(array_merge($enquiryData, [
                'institution_id' => $institution->id,
            ]));
        }
    }
}
