<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234'),
                'role_id' => 1,
                'kingdom_id' => null,
                'council_id' => null,
            ],
            [
                'name' => 'Aragorn II Elessar',
                'email' => 'aragorn@gmail.com',
                'password' => bcrypt('1234'),
                'role_id' => 2,
                'kingdom_id' => 1,
                'council_id' => null,
            ],
            [
                'name' => 'Elrond',
                'email' => 'elrond@gmail.com',
                'password' => bcrypt('1234'),
                'role_id' => 3,
                'kingdom_id' => null,
                'council_id' => 1
            ]
        ]);
    }
}
