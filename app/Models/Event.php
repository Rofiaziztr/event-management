<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

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
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Mendapatkan semua user (peserta) yang mengikuti event ini.
     * Relasi Many-to-Many: Satu Event bisa memiliki banyak User (peserta).
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants', 'event_id', 'user_id');
    }

    /**
     * Mendapatkan semua catatan kehadiran untuk event ini.
     * Relasi One-to-Many: Satu Event bisa memiliki banyak catatan kehadiran.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mendapatkan semua dokumen yang terkait dengan event ini.
     * Relasi One-to-Many: Satu Event bisa memiliki banyak Dokumen.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getCountdownStatusAttribute(): string
    {
        $now = now();
        $startTime = $this->start_time;

        if ($this->status == 'Selesai' || $this->status == 'Dibatalkan') {
            return $this->status;
        }

        if ($now->isAfter($startTime) && $now->isBefore($this->end_time)) {
            return 'Berlangsung';
        }

        if ($now->isAfter($this->end_time)) {
            return 'Selesai';
        }

        $daysUntil = $now->diffInDays($startTime, false);

        if ($daysUntil < 0) {
            return 'Selesai';
        }

        if ($daysUntil == 0) {
            return 'Hari Ini';
        }

        if ($daysUntil == 1) {
            return 'Besok';
        }

        $daysUntil = ceil($now->diffInHours($startTime) / 24);

        // Format jadi H-n tanpa koma
        return 'H-' . $daysUntil;
    }
}
