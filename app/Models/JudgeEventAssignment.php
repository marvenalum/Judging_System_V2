<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JudgeEventAssignment extends Model
{
    protected $fillable = [
        'judge_id',
        'event_id',
        'status',
    ];

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
