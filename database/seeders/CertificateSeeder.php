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
        $m6 = DB::table('milestones')->where('step_order', 6)->value('id');
        $m12 = DB::table('milestones')->where('step_order', 12)->value('id');
        $m18 = DB::table('milestones')->where('step_order', 18)->value('id');

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

        $m6 = $m6 ?? $allMilestones[0] ?? null;
        $m12 = $m12 ?? $allMilestones[1] ?? $allMilestones[0] ?? null;
        $m18 = $m18 ?? $allMilestones[2] ?? $allMilestones[0] ?? null;

        if ($m6 && $m12 && $m18) {
            DB::table('certificates')->insert([
                [
                    'certificate_title' => 'Certificate of Completion',
                    'certificate_description' => 'Awarded for successfully completing the course.',
                    'milestone_id' => $m6,
                ],
                [
                    'certificate_title' => 'Certificate of Achievement',
                    'certificate_description' => 'Recognizes outstanding performance in the program.',
                    'milestone_id' => $m12,
                ],
                [
                    'certificate_title' => 'Certificate of Excellence',
                    'certificate_description' => 'Given to individuals who demonstrate exceptional skills and dedication.',
                    'milestone_id' => $m18,
                ],
            ]);
        }
    }
}
