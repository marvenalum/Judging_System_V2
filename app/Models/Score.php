<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'judge_id',
        'participant_id',
        'event_id',
        'criteria_id',
        'score',
        'comments',
        'status',
    ];

    // Removed redundant getJudgeIdAttribute() - use $score->judge_id directly or $score->judge()->id
    // Use getDisplayJudgeIdentifier() for proper display with anonymous judging support

    /**
     * Get the category ID through criteria relationship
     */
    public function getCategoryIdAttribute()
    {
        return $this->criteria?->category?->id;
    }

    /**
     * Get the criteria name
     */
    public function getCriteriaNameAttribute()
    {
        return $this->criteria?->name;
    }

    /**
     * Get the score value
     */
    public function getScoreAttribute()
    {
        return $this->attributes['score'];
    }

    /**
     * Calculate total score for the participant in the event
     * This is a computed value representing the sum of all scores
     * for this participant across all criteria in this event
     */
    public function getTotalScoreAttribute()
    {
        return static::where('participant_id', $this->participant_id)
            ->where('event_id', $this->event_id)
            ->sum('score');
    }

    /**
     * Calculate rank based on total scores for this participant in the event
     * Rank is computed by comparing this participant's total score
     * against all other participants in the same event
     */
    public function getRankAttribute()
    {
        // Get all participants' total scores for this event
        $participantScores = static::where('event_id', $this->event_id)
            ->select('participant_id')
            ->selectRaw('SUM(score) as total_score')
            ->groupBy('participant_id')
            ->orderByDesc('total_score')
            ->get();

        // Find the rank of this participant
        $rank = 1;
        foreach ($participantScores as $participantScore) {
            if ($participantScore->participant_id == $this->participant_id) {
                return $rank;
            }
            $rank++;
        }

        return null;
    }

    /**
     * Get the judge who gave this score.
     */
    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * Get the criteria for this score.
     */
    public function criteria()
    {
        return $this->belongsTo(\App\Models\Criteria::class);
    }

    /**
     * Get the event for this score.
     */
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }

    /**
     * Get the participant who received this score.
     */
    public function participant()
    {
        return $this->belongsTo(\App\Models\User::class, 'participant_id');
    }
}
