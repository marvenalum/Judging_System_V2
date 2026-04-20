<?php

namespace App\Imports;

use App\Models\Score;
use App\Models\User;
use App\Models\Criteria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class ScoresImport
{
    protected $eventId;
    protected $categoryId;
    protected $importMode;
    protected $results = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];
    protected $errors = [];

    public function __construct($eventId, $categoryId = null, $importMode = 'upsert')
    {
        $this->eventId = $eventId;
        $this->categoryId = $categoryId;
        $this->importMode = $importMode;
    }

    public function importFromCsv(string $csvContent): array
    {
        $lines = explode("\n", trim($csvContent));
        $headers = str_getcsv(array_shift($lines));

        foreach ($lines as $lineNumber => $line) {
            if (empty(trim($line))) continue;

            $data = str_getcsv($line);

            if (count($data) !== count($headers)) {
                $this->errors[] = "Line " . ($lineNumber + 2) . ": Column count mismatch";
                $this->results['errors']++;
                continue;
            }

            $row = array_combine($headers, $data);
            $this->processRow($row, $lineNumber + 2);
        }

        return $this->results;
    }

    protected function processRow(array $row, int $lineNumber)
    {
        // Validate required fields
        $validator = Validator::make($row, [
            'participant_email' => 'required|email|exists:users,email',
            'criteria_name' => 'required|string',
            'score' => 'required|numeric|min:0|max:100',
            'judge_email' => 'nullable|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            $this->errors[] = "Line {$lineNumber}: " . implode(', ', $validator->errors()->all());
            $this->results['errors']++;
            return;
        }

        // Find participant
        $participant = User::where('email', $row['participant_email'])
            ->where('role', 'participant')
            ->first();

        if (!$participant) {
            $this->errors[] = "Line {$lineNumber}: Participant not found";
            $this->results['errors']++;
            return;
        }

        // Find criteria
        $criteriaQuery = Criteria::where('name', $row['criteria_name'])
            ->where('event_id', $this->eventId);

        if ($this->categoryId) {
            $criteriaQuery->where('category_id', $this->categoryId);
        }

        $criteria = $criteriaQuery->first();

        if (!$criteria) {
            $this->errors[] = "Line {$lineNumber}: Criteria not found";
            $this->results['errors']++;
            return;
        }

        // Find judge (optional - if not provided, use current user)
        $judgeId = auth()->id();
        if (!empty($row['judge_email'])) {
            $judge = User::where('email', $row['judge_email'])
                ->where('role', 'judge')
                ->first();

            if ($judge) {
                $judgeId = $judge->id;
            }
        }

        // Check if score already exists
        $existingScore = Score::where('judge_id', $judgeId)
            ->where('participant_id', $participant->id)
            ->where('criteria_id', $criteria->id)
            ->where('event_id', $this->eventId)
            ->first();

        $scoreData = [
            'score' => $row['score'],
            'comments' => $row['comments'] ?? '',
            'status' => $row['status'] ?? 'submitted',
        ];

        if ($existingScore) {
            if ($this->importMode === 'create') {
                $this->results['skipped']++;
                return;
            }

            $existingScore->update($scoreData);
            $this->results['updated']++;
        } else {
            if ($this->importMode === 'update') {
                $this->results['skipped']++;
                return;
            }

            Score::create([
                'judge_id' => $judgeId,
                'participant_id' => $participant->id,
                'event_id' => $this->eventId,
                'criteria_id' => $criteria->id,
                ...$scoreData
            ]);
            $this->results['created']++;
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
