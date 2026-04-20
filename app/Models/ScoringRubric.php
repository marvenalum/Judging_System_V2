<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringRubric extends Model
{
    protected $fillable = [
        'criteria_id',
        'score_level',
        'level_name',
        'description',
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public static function getRubricForScore(Criteria $criteria, $score)
    {
        return static::where('criteria_id', $criteria->id)
            ->where('score_level', '<=', $score)
            ->orderBy('score_level', 'desc')
            ->first();
    }
}
