<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeachersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teachers')->insert([
            [
                'full_name' => 'Александрова Зинаида Степановна',
                'photo' => 'teacher1.png',
                'subjects' => 'Алгебра, Геометрия, Математика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Меркулова Тамара Николаевна',
                'photo' => 'teacher2.webp',
                'subjects' => 'Русский язык',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Булков Валерий Николаевич',
                'photo' => 'teacher3.png',
                'subjects' => 'Технология, Физика',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}