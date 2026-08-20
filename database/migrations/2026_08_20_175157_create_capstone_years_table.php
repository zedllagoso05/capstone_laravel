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
        Schema::create('capstone_years', function (Blueprint $table) {
            $table->id();
            $table->string('year')->unique(); // e.g. '2025–2026'
            $table->boolean('is_active')->default(false);
            $table->boolean('capstone_1_enabled')->default(true);
            $table->boolean('capstone_2_enabled')->default(true);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capstone_years');
    }
};
