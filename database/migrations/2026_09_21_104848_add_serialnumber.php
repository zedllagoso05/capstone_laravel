<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('group_certificates', function (Blueprint $table) {
            // Unique, nullable at first so the backfill command can populate it
            $table->string('serial_number', 50)->nullable()->after('id');
        });

        // Add the unique index after backfill has run (see §4).
        // If you prefer enforcing it up-front, add ->unique() above and
        // run the backfill inside this same migration *before* adding the index.
    }

    public function down(): void
    {
        Schema::table('group_certificates', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
    }
};