<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'age',
        'gender',
        'contact_number',
        'address',
        'bio',
        'height',
        'weight',
        'measurements',
        'photo',
        'is_complete',
    ];

    protected $casts = [
        'measurements' => 'array',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_complete' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
