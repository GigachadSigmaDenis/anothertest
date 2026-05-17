<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryAssignment extends Model
{
    protected $fillable = [
        'class',
        'week_start_date',
        'day',
        'lesson_number',
        'subject',
        'text',
        'created_by_id',
    ];

    public function links()
    {
        return $this->hasMany(DiaryAssignmentLink::class);
    }

    public function files()
    {
        return $this->hasMany(DiaryAssignmentFile::class);
    }

    public function grades()
    {
        return $this->hasMany(DiaryGrade::class, 'diary_assignment_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}