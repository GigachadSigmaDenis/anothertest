<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'full_name',
        'name',
        'subject',
        'subjects',
        'photo',
    ];

    public function getSubjectListAttribute(): array
    {
        return self::splitSubjects($this->subjects ?: $this->subject);
    }

    public function getSubjectsTextAttribute(): string
    {
        return implode(PHP_EOL, $this->subject_list);
    }

    public function getSubjectsInlineAttribute(): string
    {
        return implode(', ', $this->subject_list);
    }

    public static function splitSubjects(?string $value): array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return [];
        }

        $items = preg_split('/\r\n|\r|\n|,|;/', $value) ?: [];

        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter(fn ($item) => $item !== '')
            ->unique(fn ($item) => mb_strtolower($item))
            ->values()
            ->all();
    }

    public static function normalizeSubjects(array|string|null $subjects): array
    {
        if (is_array($subjects)) {
            $subjects = implode(PHP_EOL, $subjects);
        }

        return self::splitSubjects($subjects);
    }

    public static function subjectsToText(array|string|null $subjects): string
    {
        return implode(PHP_EOL, self::normalizeSubjects($subjects));
    }

    public static function subjectsToInline(array|string|null $subjects): string
    {
        return implode(', ', self::normalizeSubjects($subjects));
    }
}