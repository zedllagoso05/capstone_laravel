<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('name')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->enum('role', ['student', 'teacher', 'admin'])->default('student');
            $table->timestamps();
        });
        

        // Password reset tokens (standard Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Students
 // Students
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('user_id');
    $table->foreign('user_id')
          ->references('user_id')
          ->on('users')
          ->onDelete('cascade');
    $table->string('student_first_name');
    $table->string('student_last_name');
    $table->string('student_email')->unique();
    $table->string('student_middle_name')->nullable();
    $table->string('contact_number');
    $table->string('course');
    $table->string('section');
    $table->timestamps();
});

// Teachers
Schema::create('teachers', function (Blueprint $table) {
    $table->id();
    $table->string('user_id');
    $table->foreign('user_id')
          ->references('user_id')
          ->on('users')
          ->onDelete('cascade');
    $table->string('teacher_first_name');
    $table->string('teacher_last_name');
    $table->string('teacher_email')->unique();
    $table->string('teacher_middle_name')->nullable();
    $table->string('contact_number');
    $table->timestamps();
});

// Admin
Schema::create('admin', function (Blueprint $table) {
    $table->id();
    $table->string('user_id');
    $table->foreign('user_id')
          ->references('user_id')
          ->on('users')
          ->onDelete('cascade');
    $table->string('admin_first_name');
    $table->string('admin_last_name');
    $table->string('admin_email')->unique();
    $table->string('admin_middle_name')->nullable();
    $table->string('contact_number');
    $table->timestamps();
});

    Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('section_name');
 
            $table->timestamps();
        });

        // Groups
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');   
            $table->string('capstone_title')->nullable();
            $table->foreignId('adviser_id')->constrained('teachers');
            $table->foreignId('section_id')->constrained('sections');
            $table->timestamps();
        });

        // Team members (pivot)
            Schema::create('team_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->string('user_id'); // make it NOT nullable if every member is a user
                $table->string('role');
                $table->timestamps();

                // Use 'user_id' instead of 'student_id'
                $table->unique(['group_id', 'user_id']);
            });

        // Milestones
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('milestone_title');
            $table->text('milestone_description');
            $table->string('capstone_stage_id');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('step_order');
            $table->timestamps();
        });

        // Group milestones
        Schema::create('group_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('milestone_id')->constrained('milestones')->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'completed'])->default('pending');
            $table->date('completion_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->unique(['group_id', 'milestone_id']);
        });

        // Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_title');
            $table->text('certificate_description');
            $table->foreignId('milestone_id')->constrained('milestones');   
            $table->boolean('is_locked')->default(true);
            $table->timestamps();
        });

        // Group certificates
        Schema::create('group_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
            $table->date('issued_date');
            $table->timestamps();
            $table->unique(['group_id', 'certificate_id']);
        });

        // Evaluations – now includes student_id
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('milestone_id')->constrained('milestones')->onDelete('cascade');
            $table->string('student_id');                     // <-- added
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->text('feedback');
            $table->date('evaluation_date');
            $table->timestamps();
            $table->unique(['group_id', 'milestone_id', 'teacher_id']);
        });

        // Rubrics
        Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->string('rubric_name');
            $table->foreignId('milestone_id')->constrained('milestones')->onDelete('cascade');
            $table->timestamps();
        });

        // Rubric criteria
        Schema::create('rubric_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubric_id')->constrained('rubrics')->onDelete('cascade')->nullable();
            $table->string('criteria_name');
            $table->decimal('weight', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_criteria');
        Schema::dropIfExists('rubrics');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('group_certificates');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('group_milestones');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};      