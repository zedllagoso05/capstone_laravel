<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'id'                  => 1,
                'user_id'             => '2023-1418',
                'student_first_name'  => 'zed',
                'student_last_name'   => 'llagoso',
                'student_email'       => 'zedllagoso8grapes_student@gmail.com', // changed to avoid duplicate with teacher
                'student_middle_name' => 'canales',
                'contact_number'      => '09913123131',
                'course'              => 'BSIT',
                'section'             => 'East',
                'created_at'          => '2026-06-25 06:27:35',
                'updated_at'          => '2026-06-25 06:27:35',
            ],
            [
                'id'                  => 2,
                'user_id'             => '2023-1419',
                'student_first_name'  => 'ddas',
                'student_last_name'   => 'asdas',
                'student_email'       => 'fasf@gmail.cpom',
                'student_middle_name' => 'asdas',
                'contact_number'      => '09868767867',
                'course'              => 'BSIT',
                'section'             => 'West',
                'created_at'          => '2026-06-25 07:25:58',
                'updated_at'          => '2026-06-25 07:25:58',
            ],
            [
                'id'                  => 3,
                'user_id'             => '2023-1420',
                'student_first_name'  => 'adf',
                'student_last_name'   => 'asdfas',
                'student_email'       => 'asdfas@gmail.com',
                'student_middle_name' => 'asfd',
                'contact_number'      => '09123134423',
                'course'              => 'BSIT',
                'section'             => 'East',
                'created_at'          => '2026-06-29 09:38:35',
                'updated_at'          => '2026-06-29 09:38:35',
            ],
         
        ];

        // Insert ignoring duplicates, safe for re-seeding
        DB::table('students')->insertOrIgnore($students);
    }
}