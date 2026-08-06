<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run()
    {
        // Only creates if no admin exists yet
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'user_id'  => '001',
                'name'     => 'admin',
                'email'    => 'admin@mcc.edu',
                'password' => Hash::make('admin12345'),
                'role'     => 'admin',
            ]);

            $this->command->info('✅ Default admin created: admin / admin12345');
        } else {
            $this->command->info('⚠️  Admin already exists, skipping.');
        }
    }
}
