<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public const DEFAULT_CATEGORY = 'Документы';

    public const CATEGORIES = [
        'Основные документы',
        'Нормативные документы',
        'Локальные акты',
        'Локальные',
        'Федеральные',
        'Рабочие программы',
        'Точка роста',
        'Кванториум',
        'IT-куб',
        'Прочие',
    ];

    protected $fillable = [
        'title',
        'category',
        'link',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public static function categories(): array
    {
        return self::CATEGORIES;
    }

    public function getCategoryLabelAttribute(): string
    {
        $category = trim((string) $this->category);

        return $category !== '' ? $category : self::DEFAULT_CATEGORY;
    }
}
