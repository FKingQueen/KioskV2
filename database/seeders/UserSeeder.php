<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'college' => 'university',
            'password' => Hash::make('12345678'),
        ]);

        DB::table('users')->insert([
            'role_id' => '3',
            'name' => 'Patrick Borja',
            'email' => 'p@gmail.com',
            'username' => 'Patty',
            'college' => 'CCIS',
            'password' => Hash::make('12345678'),
        ]);
    }
}
