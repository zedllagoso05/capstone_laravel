<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use App\Models\CapstoneStages;
use App\Models\Group;
use App\Models\Milestone;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapstoneYearTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_enable_and_archive_capstone_years(): void
    {
        $this->withoutExceptionHandling();

        // 1. Create Admin User
        $adminUser = User::create([
            'user_id'  => 'admin-01',
            'name'     => 'System Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Test Add Capstone Year
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/add-year', [
                'year' => '2027–2028',
                'capstone_1_enabled' => '1',
                'capstone_2_enabled' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect();
        $customYears = Setting::get('custom_years');
        $this->assertStringContainsString('2027–2028', $customYears);

        // 3. Test Enable Capstone Year
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/enable-year', [
                'year' => '2027–2028',
            ]);

        $response->assertRedirect();
        $this->assertEquals('2027–2028', Setting::get('active_year'));

        // 4. Test Archive All Capstones for a year
        // Create an active stage
        $stage1 = CapstoneStages::create([
            'stage_title' => 'Capstone 1 - Initial',
            'stage_type'  => 1,
            'is_enabled'  => true,
            'is_archived' => false,
        ]);

        $stage2 = CapstoneStages::create([
            'stage_title' => 'Capstone 2 - Initial',
            'stage_type'  => 2,
            'is_enabled'  => false,
            'is_archived' => false,
        ]);

        // Create milestone
        $milestone = Milestone::create([
            'milestone_title'       => 'Proposal hearing',
            'milestone_description' => 'Proposal hearing',
            'capstone_stage_id'     => $stage1->id,
            'step_order'            => 1,
            'start_date'            => '2026-05-10',
            'due_date'              => '2026-05-20',
        ]);

        // Create Section
        $section = Section::create([
            'section_name' => 'IT3B',
            'user_id'      => null,
        ]);

        // Create Teacher User
        $teacherUser = User::create([
            'user_id'  => 'teacher-01',
            'name'     => 'John Adviser',
            'email'    => 'adviser@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        // Create Teacher
        $adviserTeacher = Teacher::create([
            'user_id'            => 'teacher-01',
            'teacher_first_name' => 'John',
            'teacher_last_name'  => 'Adviser',
            'teacher_email'      => 'adviser@example.com',
            'contact_number'     => '09123456789',
        ]);

        // Create active group
        $group = Group::create([
            'group_name'        => 'AI Dream Team',
            'capstone_title'    => 'Smart Analytics',
            'adviser_id'        => $adviserTeacher->id,
            'section_id'        => $section->id,
            'capstone_stage_id' => $stage1->id,
            'is_archived'       => false,
        ]);

        // Act: Archive All Stages for year 2026
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/archive', [
                'year' => 2026,
            ]);

        $response->assertRedirect();

        // Assert existing stages are archived
        $this->assertTrue((bool) $stage1->fresh()->is_archived);
        $this->assertEquals(2026, $stage1->fresh()->archived_year);
        $this->assertFalse((bool) $stage1->fresh()->is_enabled);

        $this->assertTrue((bool) $stage2->fresh()->is_archived);
        $this->assertEquals(2026, $stage2->fresh()->archived_year);
        $this->assertFalse((bool) $stage2->fresh()->is_enabled);

        // Assert group is archived
        $this->assertTrue((bool) $group->fresh()->is_archived);
        $this->assertEquals(2026, $group->fresh()->archived_year);

        // Assert new stages are created
        $newStage1 = CapstoneStages::where('stage_type', 1)->where('is_archived', false)->first();
        $this->assertNotNull($newStage1);
        $this->assertEquals('Capstone 1 - Cycle 2026', $newStage1->stage_title);
        $this->assertTrue((bool) $newStage1->is_enabled);

        $newStage2 = CapstoneStages::where('stage_type', 2)->where('is_archived', false)->first();
        $this->assertNotNull($newStage2);
        $this->assertEquals('Capstone 2 - Cycle 2026', $newStage2->stage_title);
        $this->assertFalse((bool) $newStage2->is_enabled);

        // Assert milestones are duplicated
        $newMilestone = Milestone::where('capstone_stage_id', $newStage1->id)->first();
        $this->assertNotNull($newMilestone);
        $this->assertEquals('Proposal hearing', $newMilestone->milestone_title);
    }

    public function test_admin_can_restore_and_delete_archived_group(): void
    {
        $this->withoutExceptionHandling();

        // 1. Create Admin User
        $adminUser = User::create([
            'user_id'  => 'admin-02',
            'name'     => 'System Admin 2',
            'email'    => 'admin2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Setup archived stage and active stage
        $archivedStage = CapstoneStages::create([
            'stage_title' => 'Capstone 1 - Archived Stage',
            'stage_type'  => 1,
            'is_enabled'  => false,
            'is_archived' => true,
        ]);

        $activeStage = CapstoneStages::create([
            'stage_title' => 'Capstone 1 - Active Stage',
            'stage_type'  => 1,
            'is_enabled'  => true,
            'is_archived' => false,
        ]);

        // 3. Setup Adviser, Section, Student, and Archived Group
        $teacherUser = User::create([
            'user_id'  => 'teacher-02',
            'name'     => 'Adviser Two',
            'email'    => 'adviser2@example.com',
            'password' => bcrypt('password'),
            'role'     => 'teacher',
        ]);

        $studentUser = User::create([
            'user_id'  => 'student-03',
            'name'     => 'Archived Student',
            'email'    => 'archived@example.com',
            'password' => bcrypt('password'),
            'role'     => 'student',
        ]);

        $adviserTeacher = Teacher::create([
            'user_id'            => 'teacher-02',
            'teacher_first_name' => 'Adviser',
            'teacher_last_name'  => 'Two',
            'teacher_email'      => 'adviser2@example.com',
            'contact_number'     => '09123456780',
            'is_archived'        => true,
        ]);

        $section = Section::create([
            'section_name' => 'IT3C',
            'user_id'      => null,
            'is_archived'  => true,
        ]);

        $student = \App\Models\Student::create([
            'user_id'            => 'student-03',
            'student_first_name' => 'Archived',
            'student_last_name'  => 'Student',
            'student_email'      => 'archived@example.com',
            'contact_number'     => '09333333334',
            'course'             => 'BSIT',
            'section'            => 'IT3C',
            'is_archived'        => true,
        ]);

        $group = Group::create([
            'group_name'        => 'Archived Team',
            'capstone_title'    => 'Archived Project',
            'adviser_id'        => $adviserTeacher->id,
            'section_id'        => $section->id,
            'capstone_stage_id' => $archivedStage->id,
            'is_archived'       => true,
            'archived_year'     => 2026,
        ]);

        \App\Models\TeamMember::create([
            'group_id' => $group->id,
            'user_id'  => 'student-03',
            'role'     => 'Leader',
        ]);

        // 4. Act: Restore the group
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/restore-group/{$group->id}");

        $response->assertRedirect();

        // Assert group is restored and associated with active stage
        $group = $group->fresh();
        $this->assertFalse((bool) $group->is_archived);
        $this->assertNull($group->archived_year);
        $this->assertEquals($activeStage->id, $group->capstone_stage_id);

        // Assert adviser, section, and student are restored
        $this->assertFalse((bool) $adviserTeacher->fresh()->is_archived);
        $this->assertFalse((bool) $section->fresh()->is_archived);
        $this->assertFalse((bool) $student->fresh()->is_archived);

        // 5. Setup for deletion (Archive it again)
        $group->update(['is_archived' => true, 'archived_year' => 2026]);

        // Act: Delete the archived group
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/delete-archived-group/{$group->id}");

        $response->assertRedirect();

        // Assert group is permanently deleted
        $this->assertNull(Group::find($group->id));
        $this->assertDatabaseMissing('team_members', [
            'group_id' => $group->id,
        ]);
    }

    public function test_admin_can_manage_active_capstone_stages(): void
    {
        $this->withoutExceptionHandling();

        // 1. Create Admin User
        $adminUser = User::create([
            'user_id'  => 'admin-03',
            'name'     => 'System Admin 3',
            'email'    => 'admin3@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Add Capstone Stage
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/add-stage', [
                'stage_title' => 'Capstone 1 - New Stage',
                'stage_type'  => 1,
                'is_enabled'  => '1',
            ]);

        $response->assertRedirect();
        
        $stage = CapstoneStages::where('stage_title', 'Capstone 1 - New Stage')->first();
        $this->assertNotNull($stage);
        $this->assertEquals(1, $stage->stage_type);
        $this->assertTrue((bool) $stage->is_enabled);

        // 3. Update Capstone Stage
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/update-stage/{$stage->id}", [
                'stage_title' => 'Capstone 1 - Updated Title',
                'stage_type'  => 2,
            ]);

        $response->assertRedirect();
        $this->assertEquals('Capstone 1 - Updated Title', $stage->fresh()->stage_title);
        $this->assertEquals(2, $stage->fresh()->stage_type);
    }

    public function test_admin_can_manage_capstone_years_lifecycle(): void
    {
        $this->withoutExceptionHandling();

        // 1. Create Admin User
        $adminUser = User::create([
            'user_id'  => 'admin-04',
            'name'     => 'System Admin 4',
            'email'    => 'admin4@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Create multiple Capstone Years
        $year1 = \App\Models\CapstoneYear::create([
            'year' => '2024–2025',
            'is_active' => true,
            'capstone_1_enabled' => true,
            'capstone_2_enabled' => false,
        ]);

        $year2 = \App\Models\CapstoneYear::create([
            'year' => '2025–2026',
            'is_active' => false,
            'capstone_1_enabled' => true,
            'capstone_2_enabled' => true,
        ]);

        // 3. Ensure Year 1 is active, Year 2 is archived
        $this->assertTrue($year1->fresh()->is_active);
        $this->assertFalse($year2->fresh()->is_active);

        // Create Section and Student for Year 2 (archived)
        $section = Section::create([
            'section_name' => 'IT3D',
            'user_id'      => null,
        ]);

        $studentUser = User::create([
            'user_id'  => 'student-04',
            'name'     => 'Year Two Student',
            'email'    => 'student4@example.com',
            'password' => bcrypt('password'),
            'role'     => 'student',
        ]);

        $student = \App\Models\Student::create([
            'user_id'            => 'student-04',
            'student_first_name' => 'Year',
            'student_last_name'  => 'Two',
            'student_email'      => 'student4@example.com',
            'contact_number'     => '09444444444',
            'course'             => 'BSIT',
            'section'            => 'IT3D',
            'is_archived'        => true,
            'capstone_year_id'   => $year2->id,
        ]);

        // 4. Activate Year 2
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/activate-year/{$year2->id}");

        $response->assertRedirect();

        // 5. Assert Year 1 is now archived, Year 2 is active
        $this->assertFalse($year1->fresh()->is_active);
        $this->assertTrue($year2->fresh()->is_active);
        $this->assertNotNull($year1->fresh()->archived_at);

        // 6. Assert Year 2 students are now active
        $this->assertFalse((bool) $student->fresh()->is_archived);

        // 7. Update year configuration (e.g., disable Capstone 2)
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/update-year/{$year2->id}", [
                'year' => '2025–2026',
                'capstone_1_enabled' => '1',
            ]); // Capstone 2 is disabled implicitly (checkbox omitted)

        $response->assertRedirect();
        $this->assertTrue($year2->fresh()->capstone_1_enabled);
        $this->assertFalse($year2->fresh()->capstone_2_enabled);

        // 8. Delete Capstone Year 1 (which is now archived) with password confirmation
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post("/admin/capstone/delete-year/{$year1->id}", [
                'admin_password' => 'password',
            ]);

        $response->assertRedirect();
        $this->assertNull(\App\Models\CapstoneYear::find($year1->id));
    }

    public function test_admin_adding_year_automatically_adds_stages_and_milestones(): void
    {
        $this->withoutExceptionHandling();

        // 1. Create Admin User
        $adminUser = User::create([
            'user_id'  => 'admin-05',
            'name'     => 'System Admin 5',
            'email'    => 'admin5@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // 2. Add Capstone Year via post request
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/add-year', [
                'year' => '2028–2029',
                'capstone_1_enabled' => '1',
                'capstone_2_enabled' => '1',
                'is_active' => '1',
            ]);

        $response->assertRedirect();

        // 3. Assert Year is created
        $year = \App\Models\CapstoneYear::where('year', '2028–2029')->first();
        $this->assertNotNull($year);

        // 4. Assert Capstone 1 and Capstone 2 stages are automatically created under this year
        $c1Stage = CapstoneStages::where('capstone_year_id', $year->id)->where('stage_type', 1)->first();
        $c2Stage = CapstoneStages::where('capstone_year_id', $year->id)->where('stage_type', 2)->first();
        $this->assertNotNull($c1Stage);
        $this->assertNotNull($c2Stage);

        // 5. Assert default milestones are automatically created under these stages
        $this->assertGreaterThan(0, Milestone::where('capstone_stage_id', $c1Stage->id)->count());
        $this->assertGreaterThan(0, Milestone::where('capstone_stage_id', $c2Stage->id)->count());
    }
}
