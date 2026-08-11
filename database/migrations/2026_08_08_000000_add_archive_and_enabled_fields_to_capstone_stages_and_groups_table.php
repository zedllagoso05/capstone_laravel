<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capstone_stages', function (Blueprint $table) {
            if (!Schema::hasColumn('capstone_stages', 'is_enabled')) {
                $table->boolean('is_enabled')->default(true);
            }
            if (!Schema::hasColumn('capstone_stages', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
            if (!Schema::hasColumn('capstone_stages', 'archived_year')) {
                $table->integer('archived_year')->nullable();
            }
            if (!Schema::hasColumn('capstone_stages', 'stage_type')) {
                $table->integer('stage_type')->default(1); // 1 = Capstone 1, 2 = Capstone 2
            }
        });

        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'capstone_stage_id')) {
                $table->unsignedBigInteger('capstone_stage_id')->nullable()->default(1);
            }
            if (!Schema::hasColumn('groups', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
            if (!Schema::hasColumn('groups', 'archived_year')) {
                $table->integer('archived_year')->nullable();
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
        });

        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
        });

        Schema::table('sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sections', 'is_archived')) {
                $table->boolean('is_archived')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('capstone_stages', function (Blueprint $table) {
            $table->dropColumn('is_enabled');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['capstone_stage_id', 'is_archived', 'archived_year']);
        });
    }
};
