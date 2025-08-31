<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nip',
        'full_name',
        'position',
        'work_unit',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    /**
     * Mendapatkan semua event yang dibuat oleh user ini.
     * Relasi One-to-Many: Satu User bisa membuat banyak Event.
     */
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    /**
     * Mendapatkan semua event yang diikuti oleh user ini.
     * Relasi Many-to-Many: Satu User bisa mengikuti banyak Event,
     * dan satu Event bisa diikuti banyak User.
     */
    public function attendedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants', 'user_id', 'event_id');
    }

    /**
     * Mendapatkan semua catatan kehadiran (absensi) milik user ini.
     * Relasi One-to-Many: Satu User bisa memiliki banyak catatan kehadiran.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mendapatkan semua dokumen yang diunggah oleh user ini.
     * Relasi One-to-Many: Satu User bisa mengunggah banyak Dokumen.
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploader_id');
    }
}
