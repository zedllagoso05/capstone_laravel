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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('capstone_year_id')->nullable()->constrained('capstone_years')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('capstone_year_id')->nullable()->constrained('capstone_years')->onDelete('cascade');
        });

        Schema::table('capstone_stages', function (Blueprint $table) {
            $table->foreignId('capstone_year_id')->nullable()->constrained('capstone_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('capstone_stages', function (Blueprint $table) {
            $table->dropForeign(['capstone_year_id']);
            $table->dropColumn('capstone_year_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['capstone_year_id']);
            $table->dropColumn('capstone_year_id');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['capstone_year_id']);
            $table->dropColumn('capstone_year_id');
        });
    }
};
