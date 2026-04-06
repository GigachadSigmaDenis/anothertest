<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'day',
        'lesson_number',
        'class',
        'subject',
        'week_start_date'
    ];
}
