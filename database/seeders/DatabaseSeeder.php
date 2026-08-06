<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
  $this->call(AdminSeeder::class);
  $this->call(SectionSeeder::class);
  $this->call(MilestoneSeeder::class);
  $this->call(CertificateSeeder::class);
  $this->call(CapstonestageSeeder::class);
  $this->call(UserSeeder::class);
  $this->call(StudentSeeder::class);
  $this->call(TeacherSeeder::class);
  $this->call(AdminprofileSeeder::class);


  

    }
   

}
