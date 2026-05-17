<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryAssignmentLink extends Model
{
    protected $fillable = [
        'diary_assignment_id',
        'title',
        'url',
    ];

    public function assignment()
    {
        return $this->belongsTo(DiaryAssignment::class, 'diary_assignment_id');
    }
}