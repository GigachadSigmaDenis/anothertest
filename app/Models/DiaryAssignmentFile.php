<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryAssignmentFile extends Model
{
    protected $fillable = [
        'diary_assignment_id',
        'path',
        'original_name',
        'mime',
    ];

    public function assignment()
    {
        return $this->belongsTo(DiaryAssignment::class, 'diary_assignment_id');
    }
}