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

    /**
     * Get the scores for this submission (via participant scoped to event)
     */
    public function scores()
    {
        return $this->hasMany(Score::class, 'participant_id', 'participant_id')
                    ->where('event_id', $this->event_id);
    }
}
