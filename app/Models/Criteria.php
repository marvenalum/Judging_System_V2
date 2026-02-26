<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'name',
        'description',
        'event_id',
        'category_id',
        'max_score',
        'weight',
        'percentage_weight',
        'status',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the scores for this criterion.
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
