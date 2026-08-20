<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Group;
use App\Models\Milestone;
use App\Models\Rubric;
use App\Models\RubricCriteria;
use App\Models\CapstoneStages;
use App\Models\TeamMember;
use App\Models\Student;
use App\Models\Evaluation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardRevisionsTest extends TestCase
{
    use RefreshDatabase;

    private function setupBaseData()
    {
        // 1. Create Capstone Stage
        $stage = CapstoneStages::create([
            'stage_title' => 'Proposal Stage',
            'step_order'  => 1,
            'is_enabled'  => true,
        ]);

        // 2. Create Section
        $section = Section::create([
            'section_name' => 'IT3B',
            'user_id'      => null,
        ]);

        // 3. Create Adviser Teacher
        $adviserUser = User::create([
            'user_id'           => 'teacher-adviser',
            'name'              => 'John Adviser',
            'email'             => 'adviser@example.com',
            'password'          => bcrypt('password'),
            'role'              => 'teacher',
            'email_verified_at' => now(),
        ]);

        $adviserTeacher = Teacher::create([
            'user_id'            => 'teacher-adviser',
            'teacher_first_name' => 'John',
            'teacher_last_name'  => 'Adviser',
            'teacher_email'      => 'adviser@example.com',
            'contact_number'     => '09123456789',
        ]);

        // 4. Create Milestone
        $milestone = Milestone::create([
            'milestone_title'       => 'Title defense',
            'milestone_description' => 'Defend your title',
            'capstone_stage_id'     => $stage->id,
            'step_order'            => 1,
            'start_date'            => now()->subDays(5)->toDateString(),
            'due_date'              => now()->addDays(5)->toDateString(),
        ]);

        // 5. Create Student
        $studentUser = User::create([
            'user_id'           => 'student-01',
            'name'              => 'John Student',
            'email'             => 'student@example.com',
            'password'          => bcrypt('password'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);

        $student = Student::create([
            'user_id'            => 'student-01',
            'student_first_name' => 'John',
            'student_last_name'  => 'Student',
            'student_email'      => 'student@example.com',
            'section'            => 'IT3B',
            'course'             => 'BSIT',
            'contact_number'     => '09123456789',
        ]);

        return compact('stage', 'section', 'adviserUser', 'adviserTeacher', 'milestone', 'studentUser', 'student');
    }

    public function test_student_without_group_sees_not_in_group_warning(): void
    {
        $data = $this->setupBaseData();

        $response = $this->actingAs($data['studentUser'])
            ->get('/sections/student');

        $response->assertStatus(200);
        $response->assertSee('Not in a Group');
        $response->assertSee('You haven');
    }

    public function test_student_sees_active_revision_request(): void
    {
        $data = $this->setupBaseData();

        // Create Group with revision request
        $group = Group::create([
            'group_name'        => 'Binary Beasts',
            'capstone_title'    => 'AI Classroom Assistant',
            'adviser_id'        => $data['adviserTeacher']->id,
            'section_id'        => $data['section']->id,
            'capstone_stage_id' => $data['stage']->id,
            'revision_status'   => 'needs_revision',
            'revision_description' => 'Please revise Chapter 1 and the methodology section.',
        ]);

        TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-01',
            'role'     => 'Leader',
        ]);

        $response = $this->actingAs($data['studentUser'])
            ->get('/sections/student');

        $response->assertStatus(200);
        $response->assertSee('Active Revision Request');
        $response->assertSee('Please revise Chapter 1 and the methodology section.');
        $response->assertSee('John Adviser');
    }

    public function test_student_sees_revised_status_under_review(): void
    {
        $data = $this->setupBaseData();

        // Create Group with revised status
        $group = Group::create([
            'group_name'        => 'Binary Beasts',
            'capstone_title'    => 'AI Classroom Assistant',
            'adviser_id'        => $data['adviserTeacher']->id,
            'section_id'        => $data['section']->id,
            'capstone_stage_id' => $data['stage']->id,
            'revision_status'   => 'revised',
            'revision_description' => 'Addressed chapter 1 issues.',
        ]);

        TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-01',
            'role'     => 'Leader',
        ]);

        $response = $this->actingAs($data['studentUser'])
            ->get('/sections/student');

        $response->assertStatus(200);
        $response->assertSee('Revisions Submitted');
        $response->assertSee('Your group has addressed the requested revisions!');
        $response->assertSee('Addressed chapter 1 issues.');
    }

    public function test_student_sees_all_evaluations_and_parsed_json_feedback(): void
    {
        $data = $this->setupBaseData();

        // Create Group
        $group = Group::create([
            'group_name'        => 'Binary Beasts',
            'capstone_title'    => 'AI Classroom Assistant',
            'adviser_id'        => $data['adviserTeacher']->id,
            'section_id'        => $data['section']->id,
            'capstone_stage_id' => $data['stage']->id,
            'revision_status'   => 'none',
        ]);

        TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-01',
            'role'     => 'Leader',
        ]);

        // Create Rubric
        $rubric = Rubric::create([
            'rubric_name'  => 'Title Defense Rubric',
            'milestone_id' => $data['milestone']->id,
        ]);

        $criterion = RubricCriteria::create([
            'rubric_id'     => $rubric->id,
            'criteria_name' => 'Significance of Study',
            'weight'        => 100,
            'max_score'     => 10,
        ]);

        // Create Evaluation with JSON feedback
        $feedbackPayload = json_encode([
            'feedback_text' => 'Excellent proposal work. Highly detailed literature review.',
            'rubric_scores' => [
                $criterion->id => 9.5
            ]
        ]);

        Evaluation::create([
            'group_id'        => $group->id,
            'milestone_id'    => $data['milestone']->id,
            'student_id'      => 'student-01',
            'teacher_id'      => $data['adviserTeacher']->id,
            'score'           => 9.5,
            'max_score'       => 10.0,
            'feedback'        => $feedbackPayload,
            'evaluation_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($data['studentUser'])
            ->get('/sections/student');

        $response->assertStatus(200);
        // Should show "All Revisions Clear"
        $response->assertSee('All Revisions Clear');
        // Should show milestone and evaluator name
        $response->assertSee('Title defense');
        $response->assertSee('John Adviser');
        // Should show parsed textual feedback
        $response->assertSee('Excellent proposal work. Highly detailed literature review.');
        // Should show score
        $response->assertSee('9.5 / 10.0');
    }
}
