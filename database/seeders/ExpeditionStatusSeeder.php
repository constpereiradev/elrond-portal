<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpeditionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expedition_status')->insert([
            [
                'status' => 'Em Análise',
                'slug' => 'ANALISE'
            ],
            [
                'status' => 'Autorizada',
                'slug' => 'AUTORIZADA'
            ],
            [
                'status' => 'Rejeitada',
                'slug' => 'REJEITADA'
            ]
        ]);
    }
}
