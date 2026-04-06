<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'title' => 'Осторожно! Тонкий лед!',
                'image' => 'news1.jpg',
                'category' => 'безопасность',
                'published_at' => '2026-03-28',
                'content' => 'Помните, несоблюдение правил безопасности...',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Билет в будущее',
                'image' => 'news2.jpg',
                'category' => 'профориентация',
                'published_at' => '2026-03-28',
                'content' => 'Специалисты центра занятости провели...',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}