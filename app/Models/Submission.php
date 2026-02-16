<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'participant_id',
        'event_id',
        'title',
        'description',
        'file_path',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
