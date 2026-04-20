<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreValidation extends Model
{
    protected $fillable = [
        'score_id',
        'validation_type',
        'is_valid',
        'message',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    public function score()
    {
        return $this->belongsTo(Score::class);
    }
}
