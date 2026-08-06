<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CapstonestageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('capstone_stages')->insert([
            ['stage_title' => 'Capstone 1'],
            ['stage_title' => 'Capstone 2'],
        ]);
        //
    }
}
