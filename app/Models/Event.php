<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_name',
        'event_description',
        'event_type',
        'start_date',
        'end_date',
        'event_status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
