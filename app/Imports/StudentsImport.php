<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Hash;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        if (User::where('user_id', $row['student_id'])->exists()) {
            return null;
        }

        User::create([
            'user_id' => $row['student_id'],
            'name' => null,
            'email' => null,
            'password' => null,
            'role' => 'student',
        ]);

        return new Student([
            'user_id' => $row['student_id'],
            'student_first_name' => $row['student_first_name'],
            'student_middle_name' => $row['student_middle_name'] ?? null,
            'student_last_name' => $row['student_last_name'],
            'student_email' => $row['student_email'] ?? null,
            'contact_number' => $row['contact_number'] ?? null,
            'course' => $row['course'] ?? 'BSIT',
            'section' => $row['section'],
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|string|unique:students,user_id',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'nullable|email',
            'section' => 'required|string',
        ];
    }
}