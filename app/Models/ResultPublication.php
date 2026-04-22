<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ResultPublication extends Model
{
    protected $fillable = [
        'event_id',
        'category_id',
        'publication_code',
        'status',
        'published_at',
        'results_data',
        'metadata',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'results_data' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Methods
    public static function generatePublicationCode(): string
    {
        do {
            $code = 'PUB-' . strtoupper(Str::random(8));
        } while (self::where('publication_code', $code)->exists());

        return $code;
    }

    public function publish(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        return $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function archive(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        return $this->update(['status' => 'archived']);
    }

    public function getPublicUrl(): string
    {
        return route('public.results.show', $this->publication_code);
    }

    public function computeResults(): array
    {
        // Get all participants in this category
        $participants = User::where('role', 'participant')
            ->whereHas('submissions', function($q) {
                $q->where('event_id', $this->event_id)
                  ->where('status', 'reviewed');
            })
            ->with(['receivedScores' => function($q) {
                $q->whereHas('criteria', function($criteriaQ) {
                    $criteriaQ->where('category_id', $this->category_id);
                })->with('criteria');
            }])
            ->get();

        $results = [];

        foreach ($participants as $participant) {
            $categoryScores = $participant->receivedScores->filter(function($score) {
                return $score->criteria->category_id == $this->category_id;
            });

            if ($categoryScores->isEmpty()) {
                continue;
            }

            $totalScore = $categoryScores->sum('score');
            $maxPossible = $categoryScores->sum(function($score) {
                return $score->criteria->max_score;
            });

            $results[] = [
                'participant_id' => $participant->id,
                'participant_name' => $this->event->anonymous_judging ? 'Anonymous' : $participant->name,
                'total_score' => $totalScore,
                'max_possible' => $maxPossible,
                'percentage' => $maxPossible > 0 ? round(($totalScore / $maxPossible) * 100, 2) : 0,
                'criteria_scores' => $categoryScores->map(function($score) {
                    return [
                        'criteria_name' => $score->criteria->name,
                        'score' => $score->score,
                        'max_score' => $score->criteria->max_score,
                        'percentage' => $score->criteria->max_score > 0 ? round(($score->score / $score->criteria->max_score) * 100, 2) : 0,
                    ];
                })->toArray(),
            ];
        }

        // Sort by total score descending
        usort($results, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Add rankings
        foreach ($results as $index => &$result) {
            $result['rank'] = $index + 1;
        }

        return $results;
    }

    public function updateResultsData(): bool
    {
        $results = $this->computeResults();

        return $this->update([
            'results_data' => $results,
            'metadata' => array_merge($this->metadata ?? [], [
                'last_computed_at' => now()->toISOString(),
                'total_participants' => count($results),
            ])
        ]);
    }
}
