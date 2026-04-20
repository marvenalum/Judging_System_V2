<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreAuditLog extends Model
{
    protected $fillable = [
        'score_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
        'reason',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relationships
    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Static methods for logging
public static function logScoreChange(Score $score, string $action, array $oldValues = null, array $newValues = null, string $reason = null, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        if (!$userId) {
            return; // Don't log if no user is authenticated
        }

        if (!$score->id) {
            return; // Don't log if score ID not available yet
        }

        return static::create([
            'score_id' => $score->id,
            'user_id' => $userId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Helper methods
    public function getChangeDescription(): string
    {
        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $field => $newValue) {
                $oldValue = $this->old_values[$field] ?? null;
                if ($oldValue != $newValue) {
                    $changes[] = "{$field}: {$oldValue} → {$newValue}";
                }
            }
        }

        return empty($changes) ? 'No changes recorded' : implode(', ', $changes);
    }

    public function wasScoreChanged(): bool
    {
        if (!$this->old_values || !$this->new_values) {
            return false;
        }

        return $this->old_values['score'] != $this->new_values['score'];
    }

    public function getScoreDifference(): ?float
    {
        if (!$this->wasScoreChanged()) {
            return null;
        }

        return $this->new_values['score'] - $this->old_values['score'];
    }
}
