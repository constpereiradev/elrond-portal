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
                'id' => 1,
                'name' => 'Administrador',
                'slug' => 'ADMIN',
                'status' => 'a'
            ],
            [
                'id' => 2,
                'name' => 'Reino',
                'slug' => 'REINO',
                'status' => 'a'
            ],
            [
                'id' => 3,
                'name' => 'Conselho',
                'slug' => 'CONSELHO',
                'status' => 'a'
            ],
            [
                'id' => 4,
                'name' => 'Membro',
                'slug' => 'MEMBRO',
                'status' => 'a'
            ],
        ]);
    }
}
