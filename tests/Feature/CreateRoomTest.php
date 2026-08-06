<?php

namespace Tests\Feature;

use App\Models\EvaluationRoom;
use App\Models\Group;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRoomTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating multiple evaluation rooms and distributing groups.
     */
    public function test_can_create_multiple_rooms_and_divide_groups_evenly(): void
    {
        // 1. Create an admin user and act as admin
        $admin = User::create([
            'user_id'  => 'admin-01',
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Create a teacher (will be adviser and panelist)
        $teacherUser = User::create([
            'user_id'  => 'teacher-01',
            'name'     => 'Teacher User',
            'email'    => 'teacher@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher = Teacher::create([
            'user_id'             => 'teacher-01',
            'teacher_first_name'  => 'John',
            'teacher_last_name'   => 'Doe',
            'teacher_email'       => 'teacher@example.com',
            'teacher_middle_name' => 'M',
            'contact_number'      => '09123456789',
        ]);

        // 3. Create a Section
        $section = Section::create([
            'section_name' => 'IT3A',
            'user_id'      => $teacherUser->id,
        ]);

        // 4. Create multiple Groups in this Section
        $group1 = Group::create([
            'group_name'     => 'Group Alpha',
            'capstone_title' => 'Capstone Alpha Title',
            'adviser_id'     => $teacher->id,
            'section_id'     => $section->id,
        ]);

        $group2 = Group::create([
            'group_name'     => 'Group Beta',
            'capstone_title' => 'Capstone Beta Title',
            'adviser_id'     => $teacher->id,
            'section_id'     => $section->id,
        ]);

        $group3 = Group::create([
            'group_name'     => 'Group Gamma',
            'capstone_title' => 'Capstone Gamma Title',
            'adviser_id'     => $teacher->id,
            'section_id'     => $section->id,
        ]);

        // 5. Send POST request as Admin to create rooms and divide groups
        $response = $this->withoutMiddleware()
            ->actingAs($admin)
            ->from(route('admin.page'))
            ->post(route('admin.create_room'), [
                'room_count' => 2,
                'panelists'  => [$teacher->id],
            ]);

        // 6. Assert successful redirect back to admin page
        $response->assertRedirect(route('admin.page'));
        $response->assertSessionHas('success');

        // 7. Assert that 2 rooms were created with names continuing from count
        $this->assertEquals(2, EvaluationRoom::count());
        $room1 = EvaluationRoom::where('room_name', 'Room 1 - ' . now()->format('Y-m-d'))->first();
        $room2 = EvaluationRoom::where('room_name', 'Room 2 - ' . now()->format('Y-m-d'))->first();

        $this->assertNotNull($room1);
        $this->assertNotNull($room2);

        // 8. Assert that panelists were attached
        $this->assertTrue($room1->panelists->contains($teacher->id));
        $this->assertFalse($room2->panelists->contains($teacher->id));

        // 9. Assert that groups are divided evenly (round-robin)
        // Group1 (index 0) -> Room1, Group2 (index 1) -> Room2, Group3 (index 2) -> Room1
        $group1->refresh();
        $group2->refresh();
        $group3->refresh();

        $this->assertEquals($room1->id, $group1->room_id);
        $this->assertEquals($room2->id, $group2->room_id);
        $this->assertEquals($room1->id, $group3->room_id);
    }
}
