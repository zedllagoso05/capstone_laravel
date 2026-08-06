<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher =[
            [
                        'id'                  => 2,
                'user_id'             => 'bcd-123',
                'teacher_first_name'  => 'sooo',
                'teacher_last_name'   => 'pogi',
                'teacher_email'       => 'pogilang12@gmail.cpom',
                'teacher_middle_name' => 'fcking',
                'contact_number'      => '09868767869',
                'created_at'          => '2026-06-25 07:25:58',
                'updated_at'          => '2026-06-25 07:25:58',
            ]
        ];
                DB::table('teachers')->insertOrIgnore($teacher);
    }
}
