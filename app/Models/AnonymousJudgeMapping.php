<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnonymousJudgeMapping extends Model
{
    protected $fillable = [
        'event_id',
        'judge_id',
        'anonymous_code',
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
