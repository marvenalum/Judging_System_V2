<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public static function notifyJudgeAssignment(User $judge, $event)
    {
        $preferences = $judge->notificationPreferences;

        // Create in-app notification
        Notification::create([
            'user_id' => $judge->id,
            'type' => 'judge_assigned',
            'title' => 'New Judging Assignment',
            'message' => "You have been assigned to judge event: {$event->event_name}",
            'related_model_type' => \App\Models\Event::class,
            'related_model_id' => $event->id,
            'data' => [
                'event_name' => $event->event_name,
                'event_id' => $event->id,
                'deadline' => $event->end_date,
            ],
        ]);

        // Send email if enabled
        if ($preferences && $preferences->email_judge_assignment) {
            // For now, we'll just create the notification
            // Email sending can be implemented later with proper mail configuration
        }
    }

    public static function notifySubmissionDeadline(User $participant, $event, $daysUntil)
    {
        $preferences = $participant->notificationPreferences;

        Notification::create([
            'user_id' => $participant->id,
            'type' => 'submission_deadline',
            'title' => "Submission Deadline Approaching - {$daysUntil} days left",
            'message' => "{$event->event_name} submission deadline in {$daysUntil} days",
            'related_model_type' => \App\Models\Event::class,
            'related_model_id' => $event->id,
        ]);

        if ($preferences && $preferences->email_submission_deadline) {
            // Email implementation
        }
    }

    public static function notifyScoresSubmitted(User $participant, $event, $judge)
    {
        $preferences = $participant->notificationPreferences;

        Notification::create([
            'user_id' => $participant->id,
            'type' => 'scores_submitted',
            'title' => 'Scores Submitted for ' . $event->event_name,
            'message' => "Judge {$judge->name} has submitted scores for the {$event->event_name} event",
            'related_model_type' => \App\Models\Event::class,
            'related_model_id' => $event->id,
        ]);

        if ($preferences && $preferences->email_scores_submitted) {
            // Email implementation
        }
    }

    public static function notifyResultsPublished(User $user, $event, $result)
    {
        $preferences = $user->notificationPreferences;

        Notification::create([
            'user_id' => $user->id,
            'type' => 'results_published',
            'title' => "{$event->event_name} Results Published",
            'message' => "Results are now available. Your placement: {$result}",
            'related_model_type' => \App\Models\Event::class,
            'related_model_id' => $event->id,
            'data' => ['placement' => $result],
        ]);

        if ($preferences && $preferences->email_results_published) {
            // Email implementation
        }
    }
}