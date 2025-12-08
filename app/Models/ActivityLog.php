<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // user thực hiện action
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // model bị tác động (movie, booking...)
    public function subject()
    {
        return $this->morphTo();
    }
}
