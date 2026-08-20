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
                'year' => 2027,
            ]);

        $response->assertRedirect();
        $customYears = Setting::get('custom_years');
        $this->assertStringContainsString('2027', $customYears);

        // 3. Test Enable Capstone Year
        $response = $this->actingAs($adminUser)
            ->withoutMiddleware()
            ->post('/admin/capstone/enable-year', [
                'year' => 2027,
            ]);

        $response->assertRedirect();
        $this->assertEquals('2027', Setting::get('active_year'));

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
}
