<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluation_rooms', function (Blueprint $table) {
            $table->foreignId('required_milestone_id')->nullable()->after('join_code')->constrained('milestones')->nullOnDelete();
            $table->string('activity_name')->nullable()->after('required_milestone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_rooms', function (Blueprint $table) {
            $table->dropForeign(['required_milestone_id']);
            $table->dropColumn(['required_milestone_id', 'activity_name']);
        });
    }
};
