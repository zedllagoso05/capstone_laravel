<?php

namespace Tests\Feature;

use App\Imports\StudentsImport;
use App\Imports\TeachersImport;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_student_with_missing_email_and_contact_number(): void
    {
        $import = new StudentsImport();
        
        $row = [
            'student_id'          => '2023-9999',
            'student_first_name'  => 'John',
            'student_middle_name' => 'Middle',
            'student_last_name'   => 'Doe',
            'student_email'       => null,
            'contact_number'      => null,
            'course'              => 'BSIT',
            'section'             => 'East',
        ];

        $result = $import->model($row);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Student::class, $result);
        $this->assertEquals('2023-9999', $result->user_id);
        $this->assertNull($result->student_email);
        $this->assertNull($result->contact_number);
        
        // Assert that user model was also created
        $this->assertTrue(User::where('user_id', '2023-9999')->exists());
    }

    public function test_can_import_teacher_with_missing_email_and_contact_number(): void
    {
        $import = new TeachersImport();

        $row = [
            'teacher_id'          => 'T-9999',
            'teacher_first_name'  => 'Jane',
            'teacher_middle_name' => 'Middle',
            'teacher_last_name'   => 'Smith',
            'teacher_email'       => null,
            'contact_number'      => null,
        ];

        $result = $import->model($row);

        $this->assertNotNull($result);
        $this->assertInstanceOf(Teacher::class, $result);
        $this->assertEquals('T-9999', $result->user_id);
        $this->assertNull($result->teacher_email);
        $this->assertNull($result->contact_number);

        // Assert that user model was also created
        $this->assertTrue(User::where('user_id', 'T-9999')->exists());
    }
}
