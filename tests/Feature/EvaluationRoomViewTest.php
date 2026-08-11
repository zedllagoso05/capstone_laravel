<?php

namespace Tests\Feature;

use App\Models\EvaluationRoom;
use App\Models\Group;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationRoomViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that teachers can only see and evaluate groups in their assigned rooms,
     * and students can see their assigned room.
     */
    public function test_teacher_and_student_evaluation_room_features(): void
    {
        // 1. Create a teacher who will be assigned to a room (Teacher 1)
        $teacherUser1 = User::create([
            'user_id'  => 'teacher-01',
            'name'     => 'Teacher One',
            'email'    => 'teacher1@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher1 = Teacher::create([
            'user_id'             => 'teacher-01',
            'teacher_first_name'  => 'John',
            'teacher_last_name'   => 'Doe',
            'teacher_email'       => 'teacher1@example.com',
            'teacher_middle_name' => 'M',
            'contact_number'      => '09123456789',
        ]);

        // 2. Create another teacher who will NOT be assigned to that room (Teacher 2)
        $teacherUser2 = User::create([
            'user_id'  => 'teacher-02',
            'name'     => 'Teacher Two',
            'email'    => 'teacher2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher2 = Teacher::create([
            'user_id'             => 'teacher-02',
            'teacher_first_name'  => 'Jane',
            'teacher_last_name'   => 'Smith',
            'teacher_email'       => 'teacher2@example.com',
            'teacher_middle_name' => 'S',
            'contact_number'      => '09876543210',
        ]);

        // 3. Create a room and attach Teacher 1 as a panelist
        $room = EvaluationRoom::create([
            'room_name' => 'IT Lab 1',
        ]);
        $room->panelists()->attach($teacher1->id);

        // 4. Create a Section
        $section = Section::create([
            'section_name' => 'IT3A',
            'user_id'      => $teacherUser1->user_id,
        ]);

        // 5. Create Group 1 assigned to the room, and Group 2 NOT assigned to the room
        $group1 = Group::create([
            'group_name'     => 'Group Alpha',
            'capstone_title' => 'Alpha Title',
            'adviser_id'     => $teacher1->id,
            'section_id'     => $section->id,
            'room_id'        => $room->id,
        ]);

        $group2 = Group::create([
            'group_name'     => 'Group Beta',
            'capstone_title' => 'Beta Title',
            'adviser_id'     => $teacher2->id,
            'section_id'     => $section->id,
            'room_id'        => null, // Unassigned
        ]);

        // 6. Test Teacher 1 Dashboard contains Group 1 and Group 2 (since Group 2 is in the same section IT3A)
        $response = $this->actingAs($teacherUser1)->get(route('teacher.page'));
        $response->assertStatus(200);
        $response->assertSee('Group Alpha');
        $response->assertSee('Group Beta');
        $response->assertSee('id="assignedsections-section"', false);
        $response->assertSee('id="dashboard_detail_modal"', false);
        $response->assertSee('Assigned Section(s)', false);
        $response->assertSee('My Sections', false);
        $response->assertSee('as_toggle_btn_', false);
        $response->assertSee('as_section_students_view_', false);
        $response->assertSee('Rubric Scores', false);
        $response->assertSee('grid-cols-1 gap-6', false);
        $response->assertSee('toggleSectionCollapse', false);
        $response->assertSee('section_collapsible_content_', false);

        // 7. Test Student assigned to Group 1 can see their presentation room
        $studentUser = User::create([
            'user_id'  => 'student-01',
            'name'     => 'Student One',
            'email'    => 'student@example.com',
            'password' => bcrypt('password'),
            'role'     => 'student',
            'email_verified_at' => now(),
        ]);

        $student = Student::create([
            'user_id'             => 'student-01',
            'student_first_name'  => 'Alice',
            'student_last_name'   => 'In Wonderland',
            'student_email'       => 'student@example.com',
            'course'              => 'BSIT',
            'section'             => 'IT3A',
            'contact_number'      => '09123456789',
        ]);

        TeamMember::create([
            'group_id' => $group1->id,
            'user_id'  => 'student-01',
            'role'     => 'programmer',
        ]);

        $response = $this->actingAs($studentUser)->get(route('student.page'));
        $response->assertStatus(200);
        $response->assertSee('Presentation Room:');
        $response->assertSee('IT Lab 1');

        // 8. Test Teacher 2 is unauthorized to fetch group progress of Group 1
        $response = $this->actingAs($teacherUser2)->get(route('teacher.group_progress', ['group' => $group1->id]));
        $response->assertStatus(403);
    }

    /**
     * Test that a teacher can successfully join an evaluation room with a valid 6-char code via AJAX/JSON.
     */
    public function test_teacher_can_join_room_with_code(): void
    {
        // 1. Create a teacher
        $teacherUser = User::create([
            'user_id'  => 'teacher-03',
            'name'     => 'Teacher Three',
            'email'    => 'teacher3@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher = Teacher::create([
            'user_id'             => 'teacher-03',
            'teacher_first_name'  => 'Bob',
            'teacher_last_name'   => 'Builder',
            'teacher_email'       => 'teacher3@example.com',
            'teacher_middle_name' => 'B',
            'contact_number'      => '09122223333',
        ]);

        // 2. Create a room with a join code
        $room = EvaluationRoom::create([
            'room_name' => 'IT Lab 2',
            'join_code' => 'XYZ123',
        ]);

        // 3. Make post request to join route with valid code
        $response = $this->withoutMiddleware()
            ->actingAs($teacherUser)
            ->postJson(route('teacher.join_room'), [
                'join_code' => 'xyz123', // case-insensitive test
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success'   => true,
            'room_name' => 'IT Lab 2',
        ]);

        // 4. Assert database is updated (teacher attached as panelist to the room)
        $this->assertTrue($room->panelists->contains($teacher->id));
    }

    /**
     * Test that a teacher can successfully join an evaluation room via standard HTML form (redirect).
     */
    public function test_teacher_can_join_room_with_code_redirect(): void
    {
        // 1. Create a teacher
        $teacherUser = User::create([
            'user_id'  => 'teacher-04',
            'name'     => 'Teacher Four',
            'email'    => 'teacher4@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher = Teacher::create([
            'user_id'             => 'teacher-04',
            'teacher_first_name'  => 'Alice',
            'teacher_last_name'   => 'Smith',
            'teacher_email'       => 'teacher4@example.com',
            'teacher_middle_name' => 'M',
            'contact_number'      => '09122223334',
        ]);

        // 2. Create a room with a join code
        $room = EvaluationRoom::create([
            'room_name' => 'IT Lab 3',
            'join_code' => 'ABC456',
        ]);

        // 3. Make post request to join route with valid code (standard form POST)
        $response = $this->withoutMiddleware()
            ->actingAs($teacherUser)
            ->post(route('teacher.join_room'), [
                'join_code' => 'abc456',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 4. Assert database is updated (teacher attached as panelist to the room)
        $this->assertTrue($room->panelists->contains($teacher->id));
    }

    /**
     * Test that a user can reset their password using a valid reset verification code.
     */
    public function test_user_can_reset_password_with_valid_code(): void
    {
        // 1. Create a user
        $user = User::create([
            'user_id'  => 'test-user-99',
            'name'     => 'Test User 99',
            'email'    => 'user99@example.com',
            'password' => bcrypt('old-password'),
            'role'     => 'student',
            'email_verified_at' => now(),
        ]);

        // 2. Put a mock code in Cache
        \Illuminate\Support\Facades\Cache::put('reset_code_test-user-99', '111222', now()->addMinutes(10));

        // 3. Make POST request to reset route
        $response = $this->withoutMiddleware()
            ->post(route('password.update'), [
                'user_id'  => 'test-user-99',
                'code'     => '111222',
                'password' => 'new-secure-password',
            ]);

        // 4. Assert redirect back to login and session success message
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Your password has been reset successfully! Please log in.');

        // 5. Assert database password has been updated
        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-secure-password', $user->password));
    }
}
