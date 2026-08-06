<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapstonestageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('capstone_stages')->count() === 0) {
            DB::table('capstone_stages')->insert([
                ['id' => 1, 'stage_title' => 'Capstone 1'],
                ['id' => 2, 'stage_title' => 'Capstone 2'],
            ]);
        }
    }
}
