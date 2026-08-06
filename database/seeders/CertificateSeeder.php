<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('certificates')->insert([
            [
                'certificate_title' => 'Certificate of Completion',
                'certificate_description' => 'Awarded for successfully completing the course.',
                'milestone_id' => 6,
            ],
            [
                'certificate_title' => 'Certificate of Achievement',
                'certificate_description' => 'Recognizes outstanding performance in the program.',
                'milestone_id' => 12,
            ],
            [
                'certificate_title' => 'Certificate of Excellence',
                'certificate_description' => 'Given to individuals who demonstrate exceptional skills and dedication.',
                'milestone_id' => 18,
            ],
        ]);
    }
}
