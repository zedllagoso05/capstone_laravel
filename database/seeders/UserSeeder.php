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
            
        ];

        // Use insertOrIgnore to skip duplicates if table already has data
        DB::table('users')->insertOrIgnore($users);
    }
}