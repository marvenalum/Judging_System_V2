<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_judge_assignment',
        'email_submission_deadline',
        'email_scores_submitted',
        'email_results_published',
        'in_app_notifications',
    ];

    protected $casts = [
        'email_judge_assignment' => 'boolean',
        'email_submission_deadline' => 'boolean',
        'email_scores_submitted' => 'boolean',
        'email_results_published' => 'boolean',
        'in_app_notifications' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
