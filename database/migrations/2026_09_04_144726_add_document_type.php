<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('certificates', function (Blueprint $table) {
        $table->enum('document_type', ['recommendation', 'approval'])
              ->nullable()
              ->after('certificate_title');
    });
}

public function down()
{
    Schema::table('certificates', function (Blueprint $table) {
        $table->dropColumn('document_type');
    });
}
};
