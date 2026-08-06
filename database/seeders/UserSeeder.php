<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [

            [
                'id'                => 3,
                'user_id'           => '2023-1418',
                'name'              => 'chuychuy',
                'email'             => 'zedllagoso8grapes_student@gmail.com', // changed to avoid unique conflict with teacher
                'email_verified_at' => null,
                'password'          => Hash::make('chuychuy123'),
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-25 06:27:35',
                'updated_at'        => '2026-06-25 07:28:21',
            ],
            [
                'id'                => 4,
                'user_id'           => '2023-1419',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-25 07:25:58',
                'updated_at'        => '2026-06-25 07:25:58',
            ],
            [
                'id'                => 5,
                'user_id'           => 'bcd-123',
                'name'              => 'bcd123',
                'email'             => 'bcd@gmail.com',
                'email_verified_at' => null,
                'password'          => Hash::make('bcd-123'),
                'remember_token'    => null,
                'role'              => 'teacher',
                'created_at'        => '2026-06-25 07:37:59',
                'updated_at'        => '2026-06-25 07:39:13',
            ],
            [
                'id'                => 6,
                'user_id'           => '2023-1420',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 09:38:35',
                'updated_at'        => '2026-06-29 09:38:35',
            ],
            [
                'id'                => 7,
                'user_id'           => '2023-2021',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 09:41:30',
                'updated_at'        => '2026-06-29 09:41:30',
            ],
            [
                'id'                => 8,
                'user_id'           => '2023-2022',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 10:27:55',
                'updated_at'        => '2026-06-29 10:27:55',
            ],
            [
                'id'                => 9,
                'user_id'           => '2023-2025',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 10:28:16',
                'updated_at'        => '2026-06-29 10:28:16',
            ],
            [
                'id'                => 10,
                'user_id'           => '2023-2233',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 11:38:11',
                'updated_at'        => '2026-06-29 11:38:11',
            ],
            [
                'id'                => 11,
                'user_id'           => '2023-1344',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 11:38:33',
                'updated_at'        => '2026-06-29 11:38:33',
            ],
            [
                'id'                => 12,
                'user_id'           => '2223-1231',
                'name'              => null,
                'email'             => null,
                'email_verified_at' => null,
                'password'          => null,
                'remember_token'    => null,
                'role'              => 'student',
                'created_at'        => '2026-06-29 11:38:57',
                'updated_at'        => '2026-06-29 11:38:57',
            ],
        ];

        // Use insertOrIgnore to skip duplicates if table already has data
        DB::table('users')->insertOrIgnore($users);
    }
}