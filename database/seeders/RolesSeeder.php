<?php

namespace Database\Seeders;

use App\Models\Role;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([ 'name' => 'client']);
        Role::create(['name' => 'admin']);



//        DB::table('roles')->updateOrInsert([
//            'name' => 'client',
//            'created_at' => now(),
//            'updated_at' => now()
//        ]);
//        DB::table('roles')->updateOrInsert(['name' => 'admin']);
//        DB::table('roles')->updateOrInsert(['name' => 'owner']);
    }
}
