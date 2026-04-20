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
        'anonymous_judging',
        'anonymity_level',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'anonymous_judging' => 'boolean',
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
     * Get the anonymous judge mappings for this event.
     */
    public function anonymousJudgeMappings()
    {
        return $this->hasMany(\App\Models\AnonymousJudgeMapping::class);
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
