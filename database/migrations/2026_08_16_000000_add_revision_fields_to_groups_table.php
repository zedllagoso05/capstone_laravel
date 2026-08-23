<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Create child tables first ──
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('panelist_id')->constrained('teachers')->cascadeOnDelete();
            $table->text('overall_remarks');
            $table->timestamps();
        });

        Schema::create('revisions_documentation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_id')->constrained('revisions')->cascadeOnDelete();
            $table->string('chapter');
            $table->text('findings');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('revisions_enhancements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_id')->constrained('revisions')->cascadeOnDelete();
            $table->text('enhancement');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('revisions_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_id')->constrained('revisions')->cascadeOnDelete();
            $table->string('objective');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // ── Now alter the groups table ──
        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'revision_status')) {
                $table->string('revision_status')->default('none');
            }
            if (!Schema::hasColumn('groups', 'revision_description')) {
                $table->text('revision_description')->nullable();
            }
            if (!Schema::hasColumn('groups', 'revision_id')) {
                $table->foreignId('revision_id')
                      ->nullable()
                      ->constrained('revisions')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Remove foreign key first, then drop columns and tables
        Schema::table('groups', function (Blueprint $table) {
            if (Schema::hasColumn('groups', 'revision_id')) {
                $table->dropConstrainedForeignId('revision_id');
            }
            if (Schema::hasColumn('groups', 'revision_status')) {
                $table->dropColumn('revision_status');
            }
            if (Schema::hasColumn('groups', 'revision_description')) {
                $table->dropColumn('revision_description');
            }
        });

        Schema::dropIfExists('revisions_objectives');
        Schema::dropIfExists('revisions_enhancements');
        Schema::dropIfExists('revisions_documentation');
        Schema::dropIfExists('revisions');
    }
};