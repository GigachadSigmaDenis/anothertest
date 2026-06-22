<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTemplate extends Model
{
    protected $fillable = [
        'class',
        'day',
        'lesson_number',
        'subject',
        'teacher_name',
    ];

    protected $table = 'schedule_templates';

    public static function getTemplate($class, $day)
    {
        return self::where('class', $class)
            ->where('day', $day)
            ->orderBy('lesson_number')
            ->get();
    }

    public static function hasTemplate($class, $day)
    {
        return self::where('class', $class)
            ->where('day', $day)
            ->exists();
    }
}