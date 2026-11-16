<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// Attaches the supplied sample photos to listings that have none (demo data only)
class SampleImagesSeeder extends Seeder
{
    public function run(): void
    {
        $byType = [
            'land'      => ['07_sample_land_property.jpg'],
            'new_house' => ['08_sample_new_house.jpg', '10_sample_villa.jpg'],
            'resale'    => ['09_sample_resale_house.jpg'],
        ];
        foreach (Property::whereNull('image')->get() as $i => $p) {
            $files = $byType[$p->type] ?? [];
            if (! $files) {
                continue;
            }
            $src = public_path('img/' . $files[$i % count($files)]);
            if (File::exists($src)) {
                $dest = 'properties/sample-' . basename($src);
                Storage::disk('public')->put($dest, File::get($src));
                $p->update(['image' => $dest]);
            }
        }
    }
}
