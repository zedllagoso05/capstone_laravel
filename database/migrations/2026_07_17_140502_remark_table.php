<?php

use App\Models\Remarks;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TABLE: remark_evaluations (replaces 'remarks' to better match the context of grading/checking)
        Schema::create('remark_evaluations', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete(); // Team being evaluated
            $table->foreignId('milestone_id')->constrained('milestones')->cascadeOnDelete(); // The specific milestone
            $table->string('adviser_id');
            $table->boolean('all_present')->nullable();

            // Data required by your document
            $table->boolean('compiled')->default(false); // Complied or Did Not Comply
            $table->integer('deduction_points')->default(0); // deduction of 10 points per day
            $table->text('feedback')->nullable(); // General notes
            $table->string('remarks');
            $table->dateTime('date_evaluated')->nullable(); // To track lateness

            $table->timestamps(); // created_at, updated_at
        });

        // 2. TABLE: absences (replaces 'absent')
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete(); // Team being evaluated
            $table->foreignId('milestone_id')->constrained('milestones')->cascadeOnDelete();
            $table->string('user_id');

            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remark_evaluations');
        Schema::dropIfExists('absences');
    }
};