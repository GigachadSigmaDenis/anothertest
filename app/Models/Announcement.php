<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'type',
        'audience',
        'title',
        'description',
        'image',
        'published_at',
        'event_at',
        'is_published',
        'created_by_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'event_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}