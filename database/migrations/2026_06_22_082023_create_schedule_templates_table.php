<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->string('day');
            $table->integer('lesson_number');
            $table->string('subject')->nullable();
            $table->string('teacher_name')->nullable();
            $table->timestamps();

            $table->unique(['class', 'day', 'lesson_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_templates');
    }
};