<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // No well-known default password: use ADMIN_EMAIL / ADMIN_PASSWORD from .env,
        // or generate a random password and print it once.
        $email = env('ADMIN_EMAIL', 'admin@dreamhomes.test');
        $pass  = env('ADMIN_PASSWORD') ?: \Illuminate\Support\Str::password(16, symbols: false);
        User::updateOrCreate(['email' => $email], ['name' => 'Admin', 'password' => Hash::make($pass)]);
        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Admin login: $email / $pass  (save it now, it is not shown again)");
        }

        $defaults = [
            'site_name' => 'Dream Homes', 'tagline' => 'Real Estate & Builders',
            'whatsapp' => '919999999999', 'phone' => '+91 99999 99999',
            'hero_title' => 'Find Your', 'hero_highlight' => 'Dream Property',
            'hero_text' => 'We build homes, sell lands and help you find the right property.',
            'cta_title' => 'Custom House Construction',
            'cta_text' => 'Tell us your requirements. We will design and build your dream home.',
            'trust_1' => 'Trusted by 1000+ Families', 'trust_2' => 'Quality Construction',
            'trust_3' => 'Serving Tamil Nadu & Kerala',
        ];
        foreach ($defaults as $k => $v) {
            Setting::put($k, $v);
        }

        // Sample listings (no images: placeholder is shown until uploaded from admin)
        if (Property::count() === 0) {
            foreach ([['Kanyakumari', 1200, 1800000], ['Nagercoil', 1500, 2250000], ['Trivandrum', 1800, 2800000], ['Kollam', 1250, 2000000]] as [$loc, $sq, $pr]) {
                Property::create(['title' => "Residential Land", 'type' => 'land', 'location' => $loc, 'area_sqft' => $sq, 'price' => $pr, 'is_featured' => $loc === 'Nagercoil']);
            }
            foreach ([['3 BHK Independent House', 'Kanyakumari', 3, 7500000], ['2 BHK Modern Home', 'Nagercoil', 2, 5500000], ['3 BHK Villa', 'Trivandrum', 3, 9000000], ['2 BHK Home', 'Kollam', 2, 6000000]] as [$t, $loc, $bhk, $pr]) {
                Property::create(['title' => $t, 'type' => 'new_house', 'location' => $loc, 'bedrooms' => $bhk, 'price' => $pr]);
            }
        }

        $this->call(SampleImagesSeeder::class); // demo photos for listings without one
    }
}
