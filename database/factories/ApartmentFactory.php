<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'city' => $this->faker->city(),
            'Governorate'=>$this->faker->country(),
            'address' => $this->faker->address(),
            'price_per_day' => $this->faker->randomFloat(2, 20, 200),
            'rooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'area' => $this->faker->randomFloat(2, 50, 250),
        ];
    }
}
