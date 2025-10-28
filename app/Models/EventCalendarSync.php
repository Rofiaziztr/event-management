<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventCalendarSync extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'google_event_id',
        'synced_at',
        'last_sync_attempt',
        'sync_status',
        'sync_error',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
        'last_sync_attempt' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markSynced(?string $googleEventId = null)
    {
        $this->update([
            'google_event_id' => $googleEventId,
            'synced_at' => now(),
            'last_sync_attempt' => now(),
            'sync_status' => 'synced',
            'sync_error' => null,
        ]);
    }

    public function markFailed(?string $error = null)
    {
        $this->update([
            'last_sync_attempt' => now(),
            'sync_status' => 'failed',
            'sync_error' => $error,
        ]);
    }
}
