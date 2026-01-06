<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use Carbon\Carbon;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            // === 5 SCHOOLS ===
            [
                'name' => 'Budhanilkantha School',
                'address' => 'Budhanilkantha, Kathmandu, Nepal',
                'phone' => '+977-1-4371004',
                'email' => 'info@bnks.edu.np',
                'established_year' => 1972,
                'type' => 'school',
                'logo' => 'institutions/logos/bnks.png',
                'cover_image' => 'institutions/covers/bnks.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Little Angels\' School',
                'address' => 'Hattiban, Lalitpur, Nepal',
                'phone' => '+977-1-5549224',
                'email' => 'info@las.edu.np',
                'established_year' => 1986,
                'type' => 'school',
                'logo' => 'institutions/logos/las.png',
                'cover_image' => 'institutions/covers/las.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'St. Xavier\'s School',
                'address' => 'Jawalakhel, Lalitpur, Nepal',
                'phone' => '+977-1-5521611',
                'email' => 'info@sxsm.edu.np',
                'established_year' => 1951,
                'type' => 'school',
                'logo' => 'institutions/logos/sxs.png',
                'cover_image' => 'institutions/covers/sxs.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'GEMS School',
                'address' => 'Baluwatar, Kathmandu, Nepal',
                'phone' => '+977-1-4434980',
                'email' => 'info@gems.edu.np',
                'established_year' => 2000,
                'type' => 'school',
                'logo' => 'institutions/logos/gems.png',
                'cover_image' => 'institutions/covers/gems.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Lincoln School',
                'address' => 'Lazimpat, Kathmandu, Nepal',
                'phone' => '+977-1-4006200',
                'email' => 'info@lsnepal.com',
                'established_year' => 1954,
                'type' => 'school',
                'logo' => 'institutions/logos/lincoln.png',
                'cover_image' => 'institutions/covers/lincoln.jpg',
                'is_active' => true,
            ],

            // === 5 COLLEGES ===
            [
                'name' => 'St. Xavier\'s College',
                'address' => 'Maitighar, Kathmandu, Nepal',
                'phone' => '+977-1-5970013',
                'email' => 'info@sxc.edu.np',
                'established_year' => 1988,
                'type' => 'college',
                'logo' => 'institutions/logos/sxc.png',
                'cover_image' => 'institutions/covers/sxc.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kathmandu Engineering College',
                'address' => 'Kalimati, Kathmandu, Nepal',
                'phone' => '+977-1-4270595',
                'email' => 'info@keckist.edu.np',
                'established_year' => 1998,
                'type' => 'college',
                'logo' => 'institutions/logos/kec.png',
                'cover_image' => 'institutions/covers/kec.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Nepal College of Information Technology',
                'address' => 'Balkumari, Lalitpur, Nepal',
                'phone' => '+977-1-5186036',
                'email' => 'info@ncit.edu.np',
                'established_year' => 2001,
                'type' => 'college',
                'logo' => 'institutions/logos/ncit.png',
                'cover_image' => 'institutions/covers/ncit.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Trinity International College',
                'address' => 'Dillibazar, Kathmandu, Nepal',
                'phone' => '+977-1-4533543',
                'email' => 'info@trinitycollege.edu.np',
                'established_year' => 1999,
                'type' => 'college',
                'logo' => 'institutions/logos/trinity.png',
                'cover_image' => 'institutions/covers/trinity.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Prime College',
                'address' => 'Nayabazar, Kathmandu, Nepal',
                'phone' => '+977-1-4382349',
                'email' => 'info@prime.edu.np',
                'established_year' => 1999,
                'type' => 'college',
                'logo' => 'institutions/logos/prime.png',
                'cover_image' => 'institutions/covers/prime.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($institutions as $institution) {
            Institution::create($institution);
        }
    }
}
