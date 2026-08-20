<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'revision_status')) {
                $table->string('revision_status')->default('none'); // 'none', 'needs_revision', 'revised'
            }
            if (!Schema::hasColumn('groups', 'revision_description')) {
                $table->text('revision_description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            if (Schema::hasColumn('groups', 'revision_status')) {
                $table->dropColumn('revision_status');
            }
            if (Schema::hasColumn('groups', 'revision_description')) {
                $table->dropColumn('revision_description');
            }
        });
    }
};
