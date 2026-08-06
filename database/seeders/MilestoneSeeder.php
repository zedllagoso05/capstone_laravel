<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MilestoneSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('milestones')->count() === 0) {
            DB::table('milestones')->insert([
                // Capstone 1
                [
                    'milestone_title' => 'Subject Orientation',
                    'milestone_description' => 'Subject Orientation',
                    'start_date' => '2026-05-07',
                    'due_date' => '2026-05-08',
                    'step_order' => 1,
                    'capstone_stage_id' => 1,
                ],
                [
                    'milestone_title' => 'Title Hearing',
                    'milestone_description' => 'Title Hearing',
                    'start_date' => '2026-05-09',
                    'due_date' => '2026-05-10',
                    'step_order' => 2,
                    'capstone_stage_id' => 1,
                ],
                [
                    'milestone_title' => 'Initial Checking',
                    'milestone_description' => 'For IS:
                    initial checking of the both the system and the documents(chapter 1 highlighting 1st and 2nd objectives)
                    
                    For IOT:
                    initial checking of the both the prototype and the documents(chapter 1 highlighting 1st and 2nd objectives)',
                    'start_date' => '2026-05-11',
                    'due_date' => '2026-05-15',
                    'step_order' => 3,
                    'capstone_stage_id' => 1,
                ],
                [
                    'milestone_title' => 'Checking',
                    'milestone_description' => 'For IS:
                    initial checking of the both the system and the documents(chapter 2)
                    
                    For IOT:
                    initial checking of the both the prototype and the documents(chapter 2)',
                    'start_date' => '2026-05-19',
                    'due_date' => '2026-05-22',
                    'step_order' => 4,
                    'capstone_stage_id' => 1,
                ],
                [
                    'milestone_title' => 'ISSUANCE OF RECOMMENDATION SHEET',
                    'milestone_description' => 'submission of the expert evaluation and feasibility endorsement letter for IOT
                    
                   Submission of the 3-min video highlighting the objectives of the system for IS',
                    'start_date' => '2026-05-25',
                    'due_date' => '2026-05-29',
                    'step_order' => 5,
                    'capstone_stage_id' => 1,

                ],
                [
                    'milestone_title' => 'Capstone Oral Presentation',
                    'milestone_description' => 'No Recommendation Sheet, No Oral Presentation',
                    'start_date' => '2026-05-30',
                    'due_date' => '2026-05-30',
                    'step_order' => 6,
                    'capstone_stage_id' => 1,

                ],
                [
                    'milestone_title' => 'Issuance of Approval Sheet',
                    'milestone_description' => 'Signing of Clearance Sheet and Approval Sheet',
                    'start_date' => '2026-06-01',
                    'due_date' => '2026-06-05',
                    'step_order' => 7,
                    'capstone_stage_id' => 1,

                ],
                // Capstone 2
                [
                    'milestone_title' => 'Subject Orientation 2',
                    'milestone_description' => 'Subject Orientation',
                    'start_date' => '2026-06-15',
                    'due_date' => '2026-06-20',
                    'step_order' => 8,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Initial Checking 2',
                    'milestone_description' => 'For IS:
                    initial checking of the both the system and the documents(chapter 1 highlighting 1st and 1st additional objectives)
                    
                    For IOT:
                    initial checking of the both the prototype and the documents(chapter 1 highlighting 1st and 1st additional objectives)',
                    'start_date' => '2026-07-13',
                    'due_date' => '2026-07-18',
                    'step_order' => 9,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Checking',
                    'milestone_description' => 'For IS:
                    initial checking of the both the system and the documents(chapter 1 highlighting 1st and 2nd additional objectives)
                    
                    For IOT:
                    initial checking of the both the prototype and the documents(chapter 1 highlighting 1st and 2nd additional objectives)',
                    'start_date' => '2026-08-03',
                    'due_date' => '2026-08-08',
                    'step_order' => 10,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Initial Checking (Chapter 2)',
                    'milestone_description' => 'For IS and IOT:
                    initial checking of the both the system/prototype and the documents chapter 2(highlighting the prior arts used); cross-checking the prior arts with the system/prototype)',
                    'start_date' => '2026-08-10',
                    'due_date' => '2026-08-15',
                    'step_order' => 11,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Initial Checking (Chapter 3)',
                    'milestone_description' => 'For IS and IOT:
                    highlighting the use of RAD or Agile methodology in the development of the system/prototype; highlighting the use of UML diagrams in the development of the system/prototype
                    
                    The Presence of Gant chart is highly required.',
                    'start_date' => '2026-08-17',
                    'due_date' => '2026-08-22',
                    'step_order' => 12,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Checking of the Self Made Questionnaire based on the approved objectives',
                    'milestone_description' => '*assessment will be based on the requirements of the advisers',
                    'start_date' => '2026-09-01',
                    'due_date' => '2026-09-05',
                    'step_order' => 13,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Conduct Survey',
                    'milestone_description' => '•	Evaluation and Testing Phase of the System

•	Analysis of scores

*assessment will be based on the requirements of the advisers',
                    'start_date' => '2026-09-07',
                    'due_date' => '2026-09-12',
                    'step_order' => 14,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'ISSUANCE OF CERTIFICATION OF FREE FROM GRAMMATICAL ERRORS AND PLAGIARISM',
                    'milestone_description' => '•	Certificate will be issued by the College Librarian – Mrs. Relina J. Balili, MLIS
•	Present the Certificate to your respective advisers.

*assessment will be based on the requirements set by the College Librarian',
                    'start_date' => '2026-09-21',
                    'due_date' => '2026-09-26',
                    'step_order' => 15,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Checking of Chapter 4 and 5',
                    'milestone_description' => 'Checking of Chapter 4 and 5; results must be based on the survey of the systems testing phase.

*assessment will be based on the requirements of the advisers',
                    'start_date' => '2026-09-28',
                    'due_date' => '2026-09-30',
                    'step_order' => 16,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'Issuance of Recommendation Sheet',
                    'milestone_description' => 'SUBMISSION OF THE FULLY-FORMATTED DOCUMENTS CONTAINING CHAPTERS 1 THROUGH 5',
                    'start_date' => '2026-09-28',
                    'due_date' => '2026-10-03',
                    'step_order' => 17,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'CAPSTONE PROJECT 2 ORAL PRESENTATION',
                    'milestone_description' => '•	No Recommendation Sheet, Oral Presentation',
                    'start_date' => '2026-10-05',
                    'due_date' => '2026-10-05',
                    'step_order' => 18,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'ISSUANCE OF APPROVAL SHEET',
                    'milestone_description' => 'SUBMISSION OF THE FINAL REVISION OF THE FULLY-FORMATTED DOCUMENTS FOR BOOK-BINDING PURPOSES',
                    'start_date' => '2026-10-12',
                    'due_date' => '2026-10-17',
                    'step_order' => 19,
                    'capstone_stage_id' => 2,

                ],
                [
                    'milestone_title' => 'SIGNING OF CLEARANCE',
                    'milestone_description' => '•	Only those who have submitted their full-blown documents and have secured the fully-signed Approval Sheets will be allowed to ENROLL THEIR INTERNSHIP',
                    'start_date' => '2026-10-12',
                    'due_date' => '2026-10-17',
                    'step_order' => 20,
                    'capstone_stage_id' => 2,

                ],
            ]);
        }
    }
}
