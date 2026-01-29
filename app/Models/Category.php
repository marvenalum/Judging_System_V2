<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'event_id',
        'percentage_weight',
        'status',
    ];

    protected $casts = [
        'percentage_weight' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
