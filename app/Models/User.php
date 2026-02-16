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
}
