<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Administrador',
                'slug' => 'ADMIN',
                'status' => 'a'
            ],
            [
                'name' => 'Reino',
                'slug' => 'REINO',
                'status' => 'a'
            ],
            [
                'name' => 'Conselho',
                'slug' => 'CONSELHO',
                'status' => 'a'
            ],
        ]);
    }
}
