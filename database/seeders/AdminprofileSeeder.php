<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminprofileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $admin =[
            [
                        'id'                  => 1,
                'user_id'             => '001',
                'admin_first_name'  => 'admin',
                'admin_last_name'   => 'kani',
                'admin_email'       => 'admin@gmail.cpom',
                'admin_middle_name' => 'pogi',
                'contact_number'      => '09868769969',
                'created_at'          => '2026-06-25 07:25:58',
                'updated_at'          => '2026-06-25 07:25:58',
            ]
        ];
                DB::table('admin')->insertOrIgnore($admin);
    }
}
