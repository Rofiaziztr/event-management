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

    public function getIsActiveAttribute(): bool
    {
        return $this->participatedEvents()->exists() || $this->attendances()->exists();
    }
}
