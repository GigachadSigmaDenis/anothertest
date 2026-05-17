<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diary_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('class');
            $table->date('week_start_date');
            $table->string('day');
            $table->unsignedTinyInteger('lesson_number');
            $table->string('subject');
            $table->text('text')->nullable();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['class', 'week_start_date', 'day', 'lesson_number', 'subject'],
                'diary_assignment_unique'
            );
        });

        Schema::create('diary_assignment_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_assignment_id')->constrained('diary_assignments')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('url', 700);
            $table->timestamps();
        });

        Schema::create('diary_assignment_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_assignment_id')->constrained('diary_assignments')->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime')->nullable();
            $table->timestamps();
        });

        Schema::create('diary_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diary_assignment_id')->constrained('diary_assignments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('grade', ['2', '3', '4', '5'])->nullable();
            $table->timestamps();

            $table->unique(['diary_assignment_id', 'user_id'], 'diary_grade_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diary_grades');
        Schema::dropIfExists('diary_assignment_files');
        Schema::dropIfExists('diary_assignment_links');
        Schema::dropIfExists('diary_assignments');
    }
};