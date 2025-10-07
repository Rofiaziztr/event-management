<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nip',
        'full_name',
        'position',
        'division',
        'email',
        'password',
        'role',
        'institution',
        'phone_number',
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'google_calendar_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
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
            'google_token_expires_at' => 'datetime',
        ];
    }

    // --- Fungsi relasi yang sudah kita buat tadi ---

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function participatedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploader_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function hasGoogleCalendarAccess()
    {
        return !empty($this->google_access_token) && !$this->isGoogleTokenExpired();
    }

    public function isEventSyncedToCalendar(Event $event)
    {
        return $this->eventCalendarSyncs()
            ->where('event_id', $event->id)
            ->where('sync_status', 'synced')
            ->exists();
    }

    public function eventCalendarSyncs()
    {
        return $this->hasMany(EventCalendarSync::class);
    }

    public function isGoogleTokenExpired()
    {
        return $this->google_token_expires_at && $this->google_token_expires_at->isPast();
    }

    public function getGoogleAccessToken()
    {
        if ($this->isGoogleTokenExpired() && $this->google_refresh_token) {
            // Token refresh logic will be handled in service
            return null;
        }
        return $this->google_access_token;
    }
}
