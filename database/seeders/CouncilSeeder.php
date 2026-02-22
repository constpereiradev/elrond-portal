<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouncilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('councils')->insert(
            [
                'name' => 'Conselho de Elrond',
                'description' => 'O Conselho de Elrond foi uma reunião crucial em Valfenda, liderada por Elrond para decidir o destino do Um Anel.',
                'status' => 'a',
            ],
        );
    }
}
