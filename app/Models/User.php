<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'status' => 'string',
        ];
    }

    /**
     * Get the scores received by this user as a participant.
     */
    public function receivedScores()
    {
        return $this->hasMany(Score::class, 'participant_id');
    }

    /**
     * Get the judge event assignments for this user.
     */
    public function judgeAssignments()
    {
        return $this->hasMany(\App\Models\JudgeEventAssignment::class, 'judge_id');
    }

    /**
     * Get the submissions for this user as a participant.
     */
    public function submissions()
    {
        return $this->hasMany(\App\Models\Submission::class, 'participant_id');
    }

    /**
     * Get the events assigned to this user (as a judge).
     */
    public function assignedEvents()
    {
        return $this->hasManyThrough(
            \App\Models\Event::class,
            \App\Models\JudgeEventAssignment::class,
            'judge_id',
            'id',
            'id',
            'event_id'
        );
    }



    /**
     * Get the scores given by this user (as a judge).
     */
    public function givenScores()
    {
        return $this->hasMany(\App\Models\Score::class, 'judge_id');
    }

    /**
     * Get the judge participant assignments for this user.
     */
    public function judgeParticipantAssignments()
    {
        return $this->hasMany(\App\Models\JudgeParticipantAssignment::class, 'judge_id');
    }

    /**
     * Get the participants assigned to this judge.
     */
    public function assignedParticipants()
    {
        return $this->hasManyThrough(
            User::class,
            \App\Models\JudgeParticipantAssignment::class,
            'judge_id',
            'id',
            'id',
            'participant_id'
        )->where('role', 'participant');
    }

    /**
     * Get the notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the unread notifications for this user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->unread();
    }

    /**
     * Get the notification preferences for this user.
     */
    public function notificationPreferences()
    {
        return $this->hasOne(\App\Models\NotificationPreference::class);
    }

    /**
     * Get participant profile.
     */
    public function participantProfile()
    {
        return $this->hasOne(\App\Models\ParticipantProfile::class);
    }

    /**
     * Check if user has complete participant profile.
     */
    public function hasCompleteProfile()
    {
        $profile = $this->participantProfile;
        return $profile && $profile->is_complete;
    }

    /**
     * Get display name (profile full_name preferred).
     */
    public function getDisplayNameAttribute()
    {
        return $this->participantProfile?->full_name ?? $this->name ?? $this->email;
    }

    /**
     * Scope for approved participants (active status).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'active');
    }
}