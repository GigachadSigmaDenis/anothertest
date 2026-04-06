<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('schedules')->insert([

            [
                'day' => 'Понедельник',
                'lesson_number' => 1,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Разговоры о важном',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Понедельник',
                'lesson_number' => 2,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Математика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Понедельник',
                'lesson_number' => 3,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Русский язык',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 1,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Литература',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 2,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Английский язык',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 3,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Физическая культура',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 1,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'География',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 2,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'Математика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 3,
                'class' => '6',
                'week_start_date' => '2026-03-23',
                'subject' => 'История',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'day' => 'Понедельник',
                'lesson_number' => 1,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Разговоры о важном',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Понедельник',
                'lesson_number' => 2,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Русский язык',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Понедельник',
                'lesson_number' => 3,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Математика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 1,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Физика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 2,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'География',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Вторник',
                'lesson_number' => 3,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Литература',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 1,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Информатика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 2,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Алгебра',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'day' => 'Среда',
                'lesson_number' => 3,
                'class' => '7',
                'week_start_date' => '2026-03-23',
                'subject' => 'Английский язык',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}