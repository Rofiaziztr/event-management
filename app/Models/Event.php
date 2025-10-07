<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'location',
        'status',
        'code',
        'category_id',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->status)) {
                $event->status = 'Terjadwal';
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Mendapatkan user (admin) yang membuat event ini.
     * Relasi One-to-Many (Inverse): Satu Event hanya dimiliki oleh satu User.
     */
    public function creator(): Belongsto
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Mendapatkan semua user (peserta) yang mengikuti event ini.
     * Relasi Many-to-Many: Satu Event bisa memiliki banyak User (peserta).
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants', 'event_id', 'user_id');
    }

    /**
     * Mendapatkan semua dokumen yang terkait dengan event ini.
     * Relasi One-to-Many: Satu Event bisa memiliki banyak Dokumen.
     */
    public function documents(): Hasmany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Mendapatkan semua catatan kehadiran untuk event ini.
     * Relasi One-to-Many: Satu Event bisa memiliki banyak catatan kehadiran.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mendapatkan kategori dari event ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCountdownStatusAttribute(): string
    {
        $now = now();

        // Jika sudah selesai atau dibatalkan → langsung return
        if (in_array($this->status, ['Selesai', 'Dibatalkan'])) {
            return $this->status;
        }

        // Jika event sedang berlangsung
        if ($now->between($this->start_time, $this->end_time)) {
            return 'Berlangsung';
        }

        // Jika event sudah lewat
        if ($now->greaterThan($this->end_time)) {
            return 'Selesai';
        }

        // Hitung selisih hari ke depan
        $daysUntil = $now->diffInDays($this->start_time, false);

        return match (true) {
            $daysUntil < 0  => 'Selesai',
            $daysUntil === 0 => 'Hari Ini',
            $daysUntil === 1 => 'Besok',
            default          => 'H-' . ceil($now->diffInHours($this->start_time) / 24),
        };
    }

    /**
     * Mendapatkan status event berdasarkan waktu saat ini.
     */
    public function getStatusAttribute()
    {
        $status = $this->attributes['status'] ?? null; // hindari undefined

        if ($status === 'Dibatalkan') {
            return 'Dibatalkan';
        }

        $now = now();

        if ($now < $this->start_time) {
            return 'Terjadwal';
        } elseif ($now >= $this->start_time && $now <= $this->end_time) {
            return 'Berlangsung';
        } else {
            return 'Selesai';
        }
    }

    /**
     * Scope untuk filter berdasarkan status computed.
     */
    public function scopeByStatus($query, $status)
    {
        $now = now();

        return match ($status) {
            'Dibatalkan' => $query->where('status', 'Dibatalkan'),
            'Terjadwal' => $query->where('status', '!=', 'Dibatalkan')
                ->where('start_time', '>', $now),
            'Berlangsung' => $query->where('status', '!=', 'Dibatalkan')
                ->where('start_time', '<=', $now)
                ->where('end_time', '>=', $now),
            'Selesai' => $query->where('status', '!=', 'Dibatalkan')
                ->where('end_time', '<', $now),
            default => $query,
        };
    }

    /**
     * Cek apakah event masih aktif untuk presensi (QR code).
     */
    public function isActiveForAttendance()
    {
        $now = now();
        return $now >= $this->start_time && $now <= $this->end_time;
    }

    /**
     * Check if event is synced to user's Google Calendar
     */
    public function isSyncedToUserCalendar(User $user)
    {
        return EventCalendarSync::where('event_id', $this->id)
            ->where('user_id', $user->id)
            ->whereNotNull('google_event_id')
            ->exists();
    }

    /**
     * Get sync status for user
     */
    public function getCalendarSyncStatus(User $user)
    {
        $sync = EventCalendarSync::where('event_id', $this->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$sync) {
            return 'not_synced';
        }

        if ($sync->google_event_id) {
            return 'synced';
        }

        return 'failed';
    }
}
