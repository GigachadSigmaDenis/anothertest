<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('full_name')->after('id');
            $table->string('photo')->nullable()->after('full_name');
            $table->text('subjects')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'photo', 'subjects']);
        });
    }
};