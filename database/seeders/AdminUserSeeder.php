<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'login' => 'admin',
            'full_name' => 'Администратор',
            'email' => 'admin@school.ru',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
    }
}