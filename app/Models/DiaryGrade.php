<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryGrade extends Model
{
    protected $fillable = [
        'diary_assignment_id',
        'user_id',
        'grade',
    ];

    public function assignment()
    {
        return $this->belongsTo(DiaryAssignment::class, 'diary_assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}