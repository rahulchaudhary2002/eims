<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Affiliation;

class AffiliationSeeder extends Seeder
{
    public function run(): void
    {
        $affiliations = [
            [
                'name' => 'Tribhuvan University',
                'slug' => 'tribhuvan-university',
                'code' => 'TU',
                'description' => 'The oldest and largest university in Nepal, established in 1959.',
            ],
            [
                'name' => 'Kathmandu University',
                'slug' => 'kathmandu-university',
                'code' => 'KU',
                'description' => 'An autonomous, not-for-profit, public university established in 1991.',
            ],
            [
                'name' => 'Pokhara University',
                'slug' => 'pokhara-university',
                'code' => 'POKU',
                'description' => 'A public university located in Pokhara, Kaski district, established in 1997.',
            ],
            [
                'name' => 'Purwanchal University',
                'slug' => 'purwanchal-university',
                'code' => 'PU',
                'description' => 'A public university located in Biratnagar, established in 1994.',
            ],
            [
                'name' => 'Nepal Sanskrit University',
                'slug' => 'nepal-sanskrit-university',
                'code' => 'NSU',
                'description' => 'A university dedicated to Sanskrit studies, established in 1986.',
            ],
        ];

        foreach ($affiliations as $affiliation) {
            Affiliation::create($affiliation);
        }
    }
}
