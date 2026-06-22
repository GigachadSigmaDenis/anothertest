<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('documents', 'category')) {
            return;
        }

        DB::table('documents')
            ->whereNull('category')
            ->orWhere('category', '')
            ->update(['category' => 'Документы']);
    }

    public function down(): void
    {
        // Откат не требуется: миграция только заполняет пустые категории безопасным значением по умолчанию.
    }
};
