<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Group;
use App\Models\Milestone;
use App\Models\Rubric;
use App\Models\RubricCriteria;
use App\Models\Evaluation;
use App\Models\EvaluationRoom;
use App\Models\CapstoneStages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRubricsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can assign a section to a teacher.
     */
    public function test_admin_can_assign_section(): void
    {
        // 1. Create admin user
        $adminUser = User::create([
            'user_id'  => 'admin-01',
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Create a teacher
        $teacherUser = User::create([
            'user_id'  => 'teacher-01',
            'name'     => 'Teacher One',
            'email'    => 'teacher@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id'            => 'teacher-01',
            'teacher_first_name' => 'John',
            'teacher_last_name'  => 'Doe',
            'teacher_email'      => 'teacher@example.com',
            'contact_number'     => '09123456789',
        ]);

        // 3. Create an unassigned section
        $section = Section::create([
            'section_name' => 'IT3B',
            'user_id'      => null,
        ]);

        // 4. Admin assigns the section to the teacher
        $response = $this->actingAs($adminUser)->withoutMiddleware()->post(route('admin.assign_section'), [
            'section_id'      => $section->id,
            'teacher_user_id' => 'teacher-01',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Section assigned successfully.');

        // Assert DB matches
        $this->assertEquals('teacher-01', $section->fresh()->user_id);
    }

    /**
     * Test teacher can evaluate a group with rubric criteria scores and view the result.
     */
    public function test_teacher_can_evaluate_with_rubric_scores_and_view_results(): void
    {
        // 1. Setup Stage & Milestone
        $stage = CapstoneStages::create([
            'stage_title' => 'Proposal Stage',
            'step_order'  => 1,
            'is_enabled'  => true,
        ]);

        $milestone = Milestone::create([
            'milestone_title'       => 'Title defense',
            'milestone_description' => 'Defend your title',
            'capstone_stage_id'     => $stage->id,
            'step_order'            => 1,
            'start_date'            => now()->subDays(5)->toDateString(),
            'due_date'              => now()->addDays(5)->toDateString(),
        ]);

        // 2. Setup Rubric and Criteria
        $rubric = Rubric::create([
            'rubric_name'  => 'Title Defense Rubric',
            'milestone_id' => $milestone->id,
        ]);

        $criterion1 = RubricCriteria::create([
            'rubric_id'     => $rubric->id,
            'criteria_name' => 'Originality',
            'weight'        => 40,
            'max_score'     => 10,
        ]);

        $criterion2 = RubricCriteria::create([
            'rubric_id'     => $rubric->id,
            'criteria_name' => 'Feasibility',
            'weight'        => 60,
            'max_score'     => 10,
        ]);

        // 3. Setup Teacher
        $teacherUser = User::create([
            'user_id'  => 'teacher-02',
            'name'     => 'Teacher Two',
            'email'    => 'teacher2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id'            => 'teacher-02',
            'teacher_first_name' => 'Jane',
            'teacher_last_name'  => 'Smith',
            'teacher_email'      => 'teacher2@example.com',
            'contact_number'     => '09876543210',
        ]);

        // 4. Setup Section, Group, and Room
        $section = Section::create([
            'section_name' => 'IT3C',
            'user_id'      => 'teacher-02',
        ]);

        $room = EvaluationRoom::create([
            'room_name' => 'Room 101',
        ]);
        $room->panelists()->attach($teacher->id);

        $group = Group::create([
            'group_name'     => 'Group Beta',
            'capstone_title' => 'Beta Title',
            'adviser_id'     => $teacher->id,
            'section_id'     => $section->id,
            'room_id'        => $room->id,
        ]);

        // Attach student to group
        $studentUser = User::create([
            'user_id'  => 'student-02',
            'name'     => 'Student Beta',
            'email'    => 'student2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'student',
        ]);

        $student = \App\Models\Student::create([
            'user_id'            => 'student-02',
            'student_first_name' => 'Alex',
            'student_last_name'  => 'Johnson',
            'student_email'      => 'student2@example.com',
            'contact_number'     => '09333333333',
            'course'             => 'BSIT',
            'section'            => 'IT3C',
        ]);

        \App\Models\TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-02',
            'role'     => 'programmer',
        ]);

        // 5. Submit evaluation with rubric scores
        $response = $this->actingAs($teacherUser)->withoutMiddleware()->post('/teacher/submit-evaluation', [
            'group_id'        => $group->id,
            'milestone_id'    => $milestone->id,
            'score'           => 90.00,
            'max_score'       => 100,
            'feedback'        => 'Excellent work!',
            'attendance'      => 'present',
            'feedback1'       => 'On-time presentation',
            'rubric_scores'   => [
                $criterion1->id => 9,
                $criterion2->id => 9,
            ]
        ]);

        $response->assertRedirect();

        // 6. View evaluation results in group progress (API)
        $progressResponse = $this->actingAs($teacherUser)->get("/teacher/get-group-progress/{$group->id}");
        $progressResponse->assertStatus(200);

        $data = $progressResponse->json();
        $this->assertNotEmpty($data['evaluations']);
        
        $evaluation = $data['evaluations'][0];
        $this->assertEquals(90.00, $evaluation['score']);
        $this->assertEquals('Excellent work!', $evaluation['feedback']);
        $this->assertNotEmpty($evaluation['criteria']);
        
        // Assert criteria scores are returned
        $criteria = $evaluation['criteria'];
        $this->assertEquals('Originality', $criteria[0]['criteria_name']);
        $this->assertEquals(9, $criteria[0]['given_score']);
        $this->assertEquals('Feasibility', $criteria[1]['criteria_name']);
        $this->assertEquals(9, $criteria[1]['given_score']);
    }

    /**
     * Test admin can add a milestone.
     */
    public function test_admin_can_add_milestone(): void
    {
        $adminUser = User::create([
            'user_id'  => 'admin-02',
            'name'     => 'Admin User 2',
            'email'    => 'admin2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $stage = CapstoneStages::create([
            'stage_title' => 'Proposal Stage 2',
            'step_order'  => 2,
            'is_enabled'  => true,
        ]);

        $response = $this->actingAs($adminUser)->withoutMiddleware()->post(route('admin.add_milestone'), [
            'milestone_title' => 'System Design',
            'capstone_stage'  => $stage->id,
            'order'           => 1,
            'description'     => 'Submit system architecture diagram.',
            'start_date'      => '2026-08-01',
            'due_date'        => '2026-08-05',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('milestones', [
            'milestone_title' => 'System Design',
            'step_order' => 1,
        ]);
    }

    /**
     * Test admin can edit a milestone and associate an optional rubric.
     */
    public function test_admin_can_update_milestone_with_rubric(): void
    {
        $adminUser = User::create([
            'user_id'  => 'admin-03',
            'name'     => 'Admin User 3',
            'email'    => 'admin3@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $stage = CapstoneStages::create([
            'stage_title' => 'Proposal Stage 3',
            'step_order'  => 3,
            'is_enabled'  => true,
        ]);

        $milestone = Milestone::create([
            'milestone_title'       => 'Drafting',
            'milestone_description' => 'Draft the report',
            'capstone_stage_id'     => $stage->id,
            'step_order'            => 1,
            'start_date'            => '2026-08-01',
            'due_date'              => '2026-08-05',
        ]);

        // Update the milestone with a rubric
        $response = $this->actingAs($adminUser)->withoutMiddleware()->put(route('admin.update_milestone', $milestone->id), [
            'milestone_title' => 'Drafting (Revised)',
            'capstone_stage'  => $stage->id,
            'order'           => 1,
            'description'     => 'Draft the report with revised guidelines',
            'start_date'      => '2026-08-01',
            'due_date'        => '2026-08-05',
            'add_rubric'      => 'on',
            'rubric_name'     => 'Drafting Evaluation Rubric',
            'criteria_name'   => ['Clarity', 'Grammar'],
            'weight'          => [50, 50],
            'score'           => [10, 10],
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('milestones', [
            'id'              => $milestone->id,
            'milestone_title' => 'Drafting (Revised)',
        ]);

        $this->assertDatabaseHas('rubrics', [
            'milestone_id' => $milestone->id,
            'rubric_name'  => 'Drafting Evaluation Rubric',
        ]);

        $this->assertDatabaseHas('rubric_criteria', [
            'criteria_name' => 'Clarity',
            'weight'        => 50,
            'max_score'     => 10,
        ]);
    }

    /**
     * Test multiple panelists can join a room but a panelist is restricted to at most one room.
     */
    public function test_room_multiple_panelists_and_panelist_single_room(): void
    {
        // 1. Create a room
        $room1 = EvaluationRoom::create([
            'room_name' => 'Room 201',
            'join_code' => 'ROOM201',
        ]);

        $room2 = EvaluationRoom::create([
            'room_name' => 'Room 202',
            'join_code' => 'ROOM202',
        ]);

        // 2. Create two teachers
        $teacherUser1 = User::create([
            'user_id'  => 'teacher-30',
            'name'     => 'Teacher Thirty',
            'email'    => 'teacher30@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $teacher1 = Teacher::create([
            'user_id'            => 'teacher-30',
            'teacher_first_name' => 'Michael',
            'teacher_last_name'  => 'Scott',
            'teacher_email'      => 'teacher30@example.com',
            'contact_number'     => '09123456789',
        ]);

        $teacherUser2 = User::create([
            'user_id'  => 'teacher-31',
            'name'     => 'Teacher Thirty One',
            'email'    => 'teacher31@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $teacher2 = Teacher::create([
            'user_id'            => 'teacher-31',
            'teacher_first_name' => 'Pam',
            'teacher_last_name'  => 'Beesly',
            'teacher_email'      => 'teacher31@example.com',
            'contact_number'     => '09123456780',
        ]);

        // 3. Both panelists can join the same Room 1 (multiple panelists in one room)
        $room1->panelists()->attach($teacher1->id);
        $room1->panelists()->attach($teacher2->id);

        $this->assertEquals(2, $room1->panelists()->count());

        // 4. Test that a panelist cannot join Room 2 if they are already in Room 1 (only 1 room joined allowed)
        $response = $this->actingAs($teacherUser1)->withoutMiddleware()->post('/teacher/join-room', [
            'join_code' => 'ROOM202',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You are already assigned to an evaluation room.');
        $this->assertFalse($room2->panelists->contains($teacher1->id));
    }
}
