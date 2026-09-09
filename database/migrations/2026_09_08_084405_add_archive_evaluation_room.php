<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_rooms', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('activity_name');
            $table->integer('archived_year')->nullable()->after('is_archived');
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_rooms', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'archived_year']);
        });
    }
};