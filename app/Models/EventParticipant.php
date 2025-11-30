<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'user_id',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi attendance
    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'user_id', 'user_id')->where('event_id', $this->event_id);
    }

    /**
     * Override delete to support composite primary key (event_id + user_id).
     * Eloquent's default delete uses the 'id' primary key which doesn't exist
     * for this pivot table, causing delete queries like "where id = 0".
     */
    public function delete()
    {
        if (!$this->exists) {
            return false;
        }

        // Call observer deleting() to ensure pre-delete hooks run when using composite keys
        $observer = app(\App\Observers\EventParticipantObserver::class);
        try {
            $observer->deleting($this);
        } catch (\Throwable $e) {
            // Log but continue with deletion
            \Illuminate\Support\Facades\Log::warning('EventParticipant delete: observer deletion hook failed', ['error' => $e->getMessage()]);
        }

        $deleted = static::where('event_id', $this->event_id)
            ->where('user_id', $this->user_id)
            ->delete();

        try {
            $observer->deleted($this);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('EventParticipant delete: observer deleted hook failed', ['error' => $e->getMessage()]);
        }

        return $deleted;
    }
}
