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
        // 1. Try to find milestone IDs by step_order
        $m5 = DB::table('milestones')->where('step_order', 5)->value('id');

        // 2. If any of them are null, try to fallback to first available milestones in the table
        $allMilestones = DB::table('milestones')->pluck('id')->toArray();
        
        if (empty($allMilestones)) {
            // No milestones exist at all! Let's insert fallback milestones to ensure the foreign key works
            $fallbackId = DB::table('milestones')->insertGetId([
                'milestone_title' => 'Placeholder Milestone',
                'milestone_description' => 'Placeholder Milestone description',
                'capstone_stage_id' => '1',
                'step_order' => 1,
            ]);
            $allMilestones = [$fallbackId];
        }

        $m5 = $m5 ?? $allMilestones[0] ?? null;

        if ($m5 !== null) {
            DB::table('certificates')->insert([
                [
                    'certificate_title' => 'Recommendation Sheet',
                    'certificate_description' => 'partial fulfillment of the requirements for the degree of Bachelor of Science in Information Technology has been examined, accepted, and recommended for Oral Presentation.',
                    'milestone_id' => $m5,
                ],

            ]);
        }
    }
}
