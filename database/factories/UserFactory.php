<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name'    => $this->faker->firstName(),
            'last_name'     => $this->faker->lastName(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'birth_date'    => $this->faker->date(),
            'profile_image' => 'default.png',
            'id_image' => 'default.png',
            'gender'        => 'male',
            'status'        =>  'pending ',
            'role_id'       => 1, // افتراضي عميل
            'password'      => bcrypt('password'), // كلمة المرور الافتراضية
            'remember_token'=>Str::random(10),
        ];
    }
}
