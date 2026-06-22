<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('documents', 'category')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->string('category')->default('Документы')->after('title');
            });
        }

        DB::table('documents')
            ->whereNull('category')
            ->orWhere('category', '')
            ->update(['category' => 'Документы']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('documents', 'category')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
