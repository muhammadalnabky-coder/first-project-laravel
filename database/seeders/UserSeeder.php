<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use  Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('phone', '0999999990')->exists()) {
            User::create([
                'first_name'    => 'muhammad',
                'last_name'     => 'alnabky',
                'phone'         => '0999999999',
                'birth_date'    => '1996-02-11',
                'profile_image' => 'default.png',
                'id_image'      => 'default.png',
                'gender'        => 'male',
                'status'        =>  'approved',
                'role_id'       => 2,
                'password'      => bcrypt('12345678'),
                'remember_token'=>Str::random(10),
            ]);
        }

        User::factory()->count(10)->create();
    }
}
