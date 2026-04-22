<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JudgeParticipantAssignment extends Model
{
    protected $table = 'jpa_assignments';

    protected $fillable = [
        'judge_id',
        'participant_id',
        'event_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
