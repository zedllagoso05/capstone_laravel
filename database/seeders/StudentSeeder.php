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
                'student_first_name'  => 'ZED',
                'student_last_name'   => 'LLAGOSO',
                'student_email'       => 'zedllagoso8grapes_student@gmail.com', // changed to avoid duplicate with teacher
                'student_middle_name' => 'CANALES',
                'contact_number'      => '09950388432',
                'course'              => 'BSIT',
                'section'             => 'East',
                'created_at'          => '2026-06-25 06:27:35',
                'updated_at'          => '2026-06-25 06:27:35',
            ],

         
        ];

        // Insert ignoring duplicates, safe for re-seeding
        DB::table('students')->insertOrIgnore($students);
    }
}