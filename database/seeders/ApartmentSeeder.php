<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Apartment::factory(20)->create()->each(function ($ap) {
            ApartmentImage::factory(3)->create([
                'apartment_id' => $ap->id,
            ]);
        });
    }
}
