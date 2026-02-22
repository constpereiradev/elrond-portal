<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KingdomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kingdoms')->insert(  
            [
                'name' => 'Reino Gondor',
                'description' => 'O maior reino dos Homens no sul, principal bastião contra Mordor.',
                'status' => 'a',
            ],
        );
    }
}
