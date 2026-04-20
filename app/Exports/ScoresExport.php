<?php

namespace App\Exports;

use App\Models\Score;
use App\Models\Criteria;
use App\Models\User;

class ScoresExport
{
    protected $eventId;
    protected $categoryId;
    protected $isTemplate;

    public function __construct($eventId, $categoryId = null, $isTemplate = false)
    {
        $this->eventId = $eventId;
        $this->categoryId = $categoryId;
        $this->isTemplate = $isTemplate;
    }

    public function generateCsv(): string
    {
        $headers = [
            'participant_email',
            'participant_name',
            'criteria_name',
            'criteria_max_score',
            'judge_email',
            'judge_name',
            'score',
            'comments',
            'status',
            'created_at',
            'updated_at'
        ];

        $rows = [$headers];

        if (!$this->isTemplate) {
            $scores = $this->getScoresData();
            $rows = array_merge($rows, $scores);
        } else {
            // Add sample rows for template
            $rows[] = [
                'participant@example.com',
                'John Doe',
                'Creativity',
                '100',
                'judge@example.com',
                'Jane Smith',
                '85.5',
                'Excellent work',
                'submitted',
                now()->format('Y-m-d H:i:s'),
                now()->format('Y-m-d H:i:s')
            ];
        }

        // Convert to CSV
        $csv = '';
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(function($field) {
                // Escape fields containing commas, quotes, or newlines
                if (str_contains($field, ',') || str_contains($field, '"') || str_contains($field, "\n")) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }
                return $field;
            }, $row)) . "\n";
        }

        return $csv;
    }

    protected function getScoresData(): array
    {
        $query = Score::with(['participant', 'judge', 'criteria.category', 'event'])
            ->where('event_id', $this->eventId);

        if ($this->categoryId) {
            $query->whereHas('criteria', function($q) {
                $q->where('category_id', $this->categoryId);
            });
        }

        $scores = $query->get();

        return $scores->map(function($score) {
            return [
                $score->participant->email ?? '',
                $score->participant->name ?? '',
                $score->criteria->name ?? '',
                $score->criteria->max_score ?? '',
                $score->judge->email ?? '',
                $score->judge->name ?? '',
                $score->score,
                $score->comments ?? '',
                $score->status,
                $score->created_at->format('Y-m-d H:i:s'),
                $score->updated_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    public function getTemplateHeaders(): array
    {
        return [
            'participant_email' => 'Email address of the participant (required)',
            'participant_name' => 'Full name of the participant (optional - for reference)',
            'criteria_name' => 'Name of the criteria being scored (required)',
            'criteria_max_score' => 'Maximum possible score for this criteria (optional)',
            'judge_email' => 'Email address of the judge (optional - defaults to current user)',
            'judge_name' => 'Full name of the judge (optional - for reference)',
            'score' => 'Numerical score given (required, 0-100)',
            'comments' => 'Comments or feedback from the judge (optional)',
            'status' => 'Status of the score: draft, pending, or submitted (optional - defaults to submitted)',
            'created_at' => 'Timestamp when score was created (optional)',
            'updated_at' => 'Timestamp when score was last updated (optional)',
        ];
    }
}
