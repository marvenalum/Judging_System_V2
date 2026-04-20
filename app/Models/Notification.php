<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'related_model_type',
        'related_model_id',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'json',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
