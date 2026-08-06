<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_panelists', function (Blueprint $table) {
            $table->dropColumn('panel_type');
        });
    }

    public function down(): void
    {
        Schema::table('room_panelists', function (Blueprint $table) {
            $table->enum('panel_type', ['panelist', 'extended'])->default('panelist');
        });
    }
};