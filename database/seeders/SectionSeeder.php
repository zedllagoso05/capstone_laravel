<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = ['East', 'West', 'North', 'South', 'SouthEast', 'SouthWest', 'NorthEast', 'NorthWest'];

        foreach ($sections as $name) {
            Section::create([
                'section_name' => $name,
                'user_id'      => null,          // no teacher assigned yet
            ]);
        }
    }
}