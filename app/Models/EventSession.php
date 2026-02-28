<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    protected $fillable = [
        'event_id',
        'token',
        'is_active',
        'expired_at',
        'created_by'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isValid() :bool
    {
    return $this->is_active && $this->expired_at->isFuture();
    }
}
