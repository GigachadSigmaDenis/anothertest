<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title',
        'link',
        'sort_order',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}