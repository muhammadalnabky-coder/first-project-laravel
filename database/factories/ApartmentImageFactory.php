<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Apartment_Images;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'apartment_id' => Apartment::inRandomOrder()->first()->id ?? Apartment::factory()->create()->id,
            'image_url' => $this->faker->imageUrl(800, 600, 'Apartment', true),
        ];
    }
}
