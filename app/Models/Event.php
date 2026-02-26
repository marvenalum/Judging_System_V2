<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_name',
        'event_description',
        'event_type',
        'start_date',
        'end_date',
        'event_status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the judge assignments for this event.
     */
    public function judgeAssignments()
    {
        return $this->hasMany(\App\Models\JudgeEventAssignment::class, 'event_id');
    }

    /**
     * Get the user who created this event.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the submissions for this event.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if a specific user has applied to this event.
     */
    public function hasApplied($userId)
    {
        return $this->submissions()->where('participant_id', $userId)->exists();
    }
}
