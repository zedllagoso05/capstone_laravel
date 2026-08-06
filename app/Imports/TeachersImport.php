<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        if (User::where('user_id', $row['teacher_id'])->exists()) {
            return null;
        }

        User::create([
            'user_id' => $row['teacher_id'],
            'name'    => null,   // set later when the teacher registers, same as students
            'email'   => null,
            'password'=> null,
            'role'    => 'teacher',
        ]);

        return new Teacher([
            'user_id'             => $row['teacher_id'],
            'teacher_first_name'  => $row['teacher_first_name'],
            'teacher_middle_name' => $row['teacher_middle_name'] ?? null,
            'teacher_last_name'   => $row['teacher_last_name'],
            'teacher_email'       => $row['teacher_email'] ?? null,
            'contact_number'      => $row['contact_number'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'teacher_id'    => 'required|string|unique:teachers,user_id',
            'teacher_first_name' => 'required|string',
            'teacher_last_name'  => 'required|string',
            'teacher_email' => 'nullable|email|unique:teachers,teacher_email',
        ];
    }
}