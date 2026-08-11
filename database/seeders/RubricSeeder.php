<?php

namespace Database\Seeders;

use App\Models\Milestone;
use App\Models\Rubric;
use App\Models\RubricCriteria;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Capstone 1 Oral Presentation Rubric
        $capstone1Milestone = Milestone::where('milestone_title', 'Capstone Oral Presentation')->first();

        if ($capstone1Milestone) {
            $rubric1 = Rubric::updateOrCreate(
                ['milestone_id' => $capstone1Milestone->id],
                ['rubric_name' => 'Capstone 1 Oral Presentation Rubric']
            );

            $criteria1 = [
                [
                    'criteria_name' => 'System Demo & Functional Prototype',
                    'weight' => 35,
                    'max_score' => 4,
                ],
                [
                    'criteria_name' => 'System Architecture & Technical Design',
                    'weight' => 25,
                    'max_score' => 4,
                ],
                [
                    'criteria_name' => 'Delivery, Presentation Skills & Clarity',
                    'weight' => 20,
                    'max_score' => 4,
                ],
                [
                    'criteria_name' => 'Response to Panelist Q&A',
                    'weight' => 20,
                    'max_score' => 4,
                ],
            ];

            foreach ($criteria1 as $c) {
                RubricCriteria::updateOrCreate(
                    ['rubric_id' => $rubric1->id, 'criteria_name' => $c['criteria_name']],
                    ['weight' => $c['weight'], 'max_score' => $c['max_score']]
                );
            }
        }

        // 2. Capstone 2 Oral Presentation Rubric
        $capstone2Milestone = Milestone::where('milestone_title', 'CAPSTONE PROJECT 2 ORAL PRESENTATION')->first();

        if ($capstone2Milestone) {
            $rubric2 = Rubric::updateOrCreate(
                ['milestone_id' => $capstone2Milestone->id],
                ['rubric_name' => 'Capstone 2 Oral Presentation Rubric']
            );

            $criteria2 = [
                [
                    'criteria_name' => 'Final System Quality, Usability & Completeness',
                    'weight' => 0.40,
                    'max_score' => 100.00,
                ],
                [
                    'criteria_name' => 'Research Contribution & Evaluation Results',
                    'weight' => 0.25,
                    'max_score' => 100.00,
                ],
                [
                    'criteria_name' => 'Presentation Delivery & Defense Performance',
                    'weight' => 0.20,
                    'max_score' => 100.00,
                ],
                [
                    'criteria_name' => 'Technical Documentation & Design Completeness',
                    'weight' => 0.15,
                    'max_score' => 100.00,
                ],
            ];

            foreach ($criteria2 as $c) {
                RubricCriteria::updateOrCreate(
                    ['rubric_id' => $rubric2->id, 'criteria_name' => $c['criteria_name']],
                    ['weight' => $c['weight'], 'max_score' => $c['max_score']]
                );
            }
        }
    }
}
