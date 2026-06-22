<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'class',
        'day',
        'lesson_number',
        'subject',
        'teacher_name',
        'week_start_date',
    ];
}
