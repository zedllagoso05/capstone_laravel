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
        Schema::create('evaluation_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name')->unique();
            $table->timestamps();
        });

        Schema::create('room_panelists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('evaluation_rooms')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->enum('panel_type', ['panelist', 'extended'])->default('panelist');
            $table->timestamps();
            $table->unique(['room_id', 'teacher_id']); // a teacher can't be added twice to the same room
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_panelists');   // drop child first — it has the FK
        Schema::dropIfExists('evaluation_rooms');
    }
};