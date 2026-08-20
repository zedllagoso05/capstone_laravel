<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Group;
use App\Models\Milestone;
use App\Models\Rubric;
use App\Models\RubricCriteria;
use App\Models\EvaluationRoom;
use App\Models\CapstoneStages;
use App\Models\TeamMember;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupRevisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_panelist_can_request_revision_and_adviser_can_mark_revised(): void
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
            'user_id'  => 'teacher-adviser',
            'name'     => 'John Adviser',
            'email'    => 'adviser@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $adviserTeacher = Teacher::create([
            'user_id'            => 'teacher-adviser',
            'teacher_first_name' => 'John',
            'teacher_last_name'  => 'Adviser',
            'teacher_email'      => 'adviser@example.com',
            'contact_number'     => '09123456789',
        ]);

        // 4. Create Panelist Teacher
        $panelistUser = User::create([
            'user_id'  => 'teacher-panelist',
            'name'     => 'Mary Panelist',
            'email'    => 'panelist@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $panelistTeacher = Teacher::create([
            'user_id'            => 'teacher-panelist',
            'teacher_first_name' => 'Mary',
            'teacher_last_name'  => 'Panelist',
            'teacher_email'      => 'panelist@example.com',
            'contact_number'     => '09987654321',
        ]);

        // 5. Create Milestone
        $milestone = Milestone::create([
            'milestone_title'       => 'Title defense',
            'milestone_description' => 'Defend your title',
            'capstone_stage_id'     => $stage->id,
            'step_order'            => 1,
            'start_date'            => now()->subDays(5)->toDateString(),
            'due_date'              => now()->addDays(5)->toDateString(),
        ]);

        // 6. Create Evaluation Room
        $room = EvaluationRoom::create([
            'room_name'             => 'Room 101',
            'join_code'             => 'ABCDEF',
            'required_milestone_id' => $milestone->id,
        ]);

        // Attach panelist to the room
        $room->panelists()->attach($panelistTeacher->id);

        // 7. Create Group
        $group = Group::create([
            'group_name'        => 'Binary Beasts',
            'capstone_title'    => 'AI Classroom Assistant',
            'adviser_id'        => $adviserTeacher->id,
            'section_id'        => $section->id,
            'room_id'           => $room->id,
            'capstone_stage_id' => $stage->id,
        ]);

        // Add a student to team members (needed for evaluation submission validation)
        $studentUser = User::create([
            'user_id'  => 'student-01',
            'name'     => 'Student One',
            'email'    => 'student@example.com',
            'password' => bcrypt('password'),
            'role'     => 'student',
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

        TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-01',
            'role'     => 'Leader',
        ]);

        // --- PHASE A: Panelist Requests Revision ---
        // Verify initially revision_status is 'none'
        $this->assertEquals('none', $group->fresh()->revision_status);

        // Act: Panelist requests revision
        $response = $this->actingAs($panelistUser)
            ->withoutMiddleware()
            ->post("/teacher/group/{$group->id}/request-revision", [
                'revision_description' => 'Please revise your methodology section.',
            ]);

        $response->assertRedirect();
        $this->assertEquals('needs_revision', $group->fresh()->revision_status);
        $this->assertEquals('Please revise your methodology section.', $group->fresh()->revision_description);

        // --- PHASE B: Adviser Marks as Revised ---
        // Act: Adviser marks it as revised
        $response = $this->actingAs($adviserUser)
            ->withoutMiddleware()
            ->post("/teacher/group/{$group->id}/mark-revised");

        $response->assertRedirect();
        $this->assertEquals('revised', $group->fresh()->revision_status);

        // --- PHASE C: Clear Revision on Evaluation Submission ---
        // Setup Rubric and Criteria for evaluation
        $rubric = Rubric::create([
            'rubric_name'  => 'Title Defense Rubric',
            'milestone_id' => $milestone->id,
        ]);

        $criterion = RubricCriteria::create([
            'rubric_id'     => $rubric->id,
            'criteria_name' => 'Significance of Study',
            'weight'        => 100,
            'max_score'     => 10,
        ]);

        // Act: Panelist submits evaluation
        $response = $this->actingAs($panelistUser)
            ->withoutMiddleware()
            ->post('/teacher/submit-evaluation', [
                'group_id'        => $group->id,
                'milestone_id'    => $milestone->id,
                'score'           => 8,
                'max_score'       => 10,
                'feedback'        => 'Good methodology revisions.',
                'attendance'      => 'present',
                'rubric_scores'   => [
                    $criterion->id => 8
                ]
            ]);

        $response->assertRedirect();
        // Assert revision status is successfully reset to 'none' and description is cleared
        $this->assertEquals('none', $group->fresh()->revision_status);
        $this->assertNull($group->fresh()->revision_description);
    }
}
