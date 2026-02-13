<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $institutions = [
            [
                'name' => 'Budhanilkantha School',
                'address' => 'Budhanilkantha, Kathmandu, Nepal',
                'phone' => '+977-1-4371004',
                'email' => 'info@bnks.edu.np',
                'established_year' => 1972,
                'type' => 'school',
                'institution_category_id' => 1,
                'logo_filename' => 'budhanilkantha-logo.png',
                'cover_filename' => 'budhanilkantha-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Little Angels\' School',
                'address' => 'Hattiban, Lalitpur, Nepal',
                'phone' => '+977-1-5549224',
                'email' => 'info@las.edu.np',
                'established_year' => 1986,
                'type' => 'school',
                'institution_category_id' => 2,
                'logo_filename' => 'little-angels-logo.png',
                'cover_filename' => 'little-angels-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'St. Xavier\'s School',
                'address' => 'Jawalakhel, Lalitpur, Nepal',
                'phone' => '+977-1-5521611',
                'email' => 'info@sxsm.edu.np',
                'established_year' => 1951,
                'type' => 'school',
                'institution_category_id' => 3,
                'logo_filename' => 'sxs-logo.jpg',
                'cover_filename' => 'sxs-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'GEMS School',
                'address' => 'Baluwatar, Kathmandu, Nepal',
                'phone' => '+977-1-4434980',
                'email' => 'info@gems.edu.np',
                'established_year' => 2000,
                'type' => 'school',
                'institution_category_id' => 4,
                'logo_filename' => 'gems-logo.png',
                'cover_filename' => 'gems-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Lincoln School',
                'address' => 'Lazimpat, Kathmandu, Nepal',
                'phone' => '+977-1-4006200',
                'email' => 'info@lsnepal.com',
                'established_year' => 1954,
                'type' => 'school',
                'institution_category_id' => 5,
                'logo_filename' => 'lincoln-logo.jpg',
                'cover_filename' => 'lincoln-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'St. Xavier\'s College',
                'address' => 'Maitighar, Kathmandu, Nepal',
                'phone' => '+977-1-5970013',
                'email' => 'info@sxc.edu.np',
                'established_year' => 1988,
                'type' => 'college',
                'institution_category_id' => 6,
                'logo_filename' => 'sxc-logo.jpg',
                'cover_filename' => 'sxc-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kathmandu Engineering College',
                'address' => 'Kalimati, Kathmandu, Nepal',
                'phone' => '+977-1-4270595',
                'email' => 'info@keckist.edu.np',
                'established_year' => 1998,
                'type' => 'college',
                'institution_category_id' => 7,
                'logo_filename' => 'kec-logo.jpg',
                'cover_filename' => 'kec-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Nepal College of Information Technology',
                'address' => 'Balkumari, Lalitpur, Nepal',
                'phone' => '+977-1-5186036',
                'email' => 'info@ncit.edu.np',
                'established_year' => 2001,
                'type' => 'college',
                'institution_category_id' => 8,
                'logo_filename' => 'ncit-logo.jpeg',
                'cover_filename' => 'ncit-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Trinity International College',
                'address' => 'Dillibazar, Kathmandu, Nepal',
                'phone' => '+977-1-4533543',
                'email' => 'info@trinitycollege.edu.np',
                'established_year' => 1999,
                'type' => 'college',
                'institution_category_id' => 9,
                'logo_filename' => 'trinity-logo.jpg',
                'cover_filename' => 'trinity-cover.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Prime College',
                'address' => 'Nayabazar, Kathmandu, Nepal',
                'phone' => '+977-1-4382349',
                'email' => 'info@prime.edu.np',
                'established_year' => 1999,
                'type' => 'college',
                'institution_category_id' => 10,
                'logo_filename' => 'prime-logo.jpeg',
                'cover_filename' => 'prime-cover.jpeg',
                'is_active' => true,
            ],
        ];

        foreach ($institutions as $data) {
            // Copy logo
            $publicLogoPath = 'assets/images/institutions/' . $data['logo_filename'];
            $storageLogoPath = 'institutions/logos/' . $data['logo_filename'];

            if (File::exists(public_path($publicLogoPath))) {
                // Use the public disk
                Storage::disk('public')->makeDirectory('institutions/logos');

                Storage::disk('public')->put(
                    $storageLogoPath,
                    File::get(public_path($publicLogoPath))
                );

                $this->command?->info("Logo copied: {$data['logo_filename']}");
            } else {
                $this->command?->warn("Logo not found: {$publicLogoPath}");
                $storageLogoPath = null;
            }

            // Copy cover
            $publicCoverPath = 'assets/images/institutions/' . $data['cover_filename'];
            $storageCoverPath = 'institutions/covers/' . $data['cover_filename'];

            if (File::exists(public_path($publicCoverPath))) {
                // Use the public disk
                Storage::disk('public')->makeDirectory('institutions/covers');

                Storage::disk('public')->put(
                    $storageCoverPath,
                    File::get(public_path($publicCoverPath))
                );

                $this->command?->info("Cover copied: {$data['cover_filename']}");
            } else {
                $this->command?->warn("Cover not found: {$publicCoverPath}");
                $storageCoverPath = null;
            }

            Institution::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'institution_category_id' => $data['institution_category_id'],
                'established_year' => $data['established_year'],
                'type' => $data['type'],
                'logo' => $storageLogoPath,
                'cover_image' => $storageCoverPath,
                'is_active' => $data['is_active'],
            ]);

            $this->command?->info("Institution created: {$data['name']}");
        }
    }
}
