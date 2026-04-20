<?php

namespace App\Services;

use App\Models\Score;
use App\Models\Criteria;
use App\Models\ScoreValidation;

class ScoringValidationService
{
    public static function validateScore(Score $score): array
    {
        $errors = [];

        // Check score is within valid range
        if ($score->score < 0 || $score->score > $score->criteria->max_score) {
            $errors[] = "Score must be between 0 and {$score->criteria->max_score}";
        }

        // Check for consistency with other judges
        $otherJudgesAvg = Score::where('participant_id', $score->participant_id)
            ->where('criteria_id', $score->criteria_id)
            ->where('event_id', $score->event_id)
            ->where('judge_id', '!=', $score->judge_id)
            ->average('score');

        if ($otherJudgesAvg !== null) {
            $deviation = abs($score->score - $otherJudgesAvg);
            if ($deviation > $score->criteria->max_score * 0.5) {
                // Flag for review but don't error
                ScoreValidation::create([
                    'score_id' => $score->id,
                    'validation_type' => 'consistency',
                    'is_valid' => true,
                    'message' => "Score deviates significantly from other judges (diff: {$deviation})",
                ]);
            }
        }

        return $errors;
    }

    public static function getAllValidationErrors(Score $score)
    {
        return ScoreValidation::where('score_id', $score->id)
            ->where('is_valid', false)
            ->get();
    }

    public static function getScoringConsistency(array $judgeIds, $criteria_id, $participant_id, $event_id)
    {
        $scores = Score::whereIn('judge_id', $judgeIds)
            ->where('criteria_id', $criteria_id)
            ->where('participant_id', $participant_id)
            ->where('event_id', $event_id)
            ->pluck('score')
            ->toArray();

        if (count($scores) < 2) {
            return null;
        }

        $variance = self::calculateVariance($scores);
        $standardDeviation = sqrt($variance);
        $mean = array_sum($scores) / count($scores);

        return [
            'mean' => $mean,
            'variance' => $variance,
            'std_dev' => $standardDeviation,
            'max' => max($scores),
            'min' => min($scores),
            'range' => max($scores) - min($scores),
            'outliers' => self::findOutliers($scores, $mean, $standardDeviation),
        ];
    }

    private static function calculateVariance(array $numbers)
    {
        if (count($numbers) < 2) return 0;
        $mean = array_sum($numbers) / count($numbers);
        $variance = 0;
        foreach ($numbers as $num) {
            $variance += pow($num - $mean, 2);
        }
        return $variance / count($numbers);
    }

    private static function findOutliers(array $scores, $mean, $stdDev)
    {
        return array_filter($scores, function ($score) use ($mean, $stdDev) {
            return abs($score - $mean) > 2 * $stdDev;
        });
    }
}