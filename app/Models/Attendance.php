<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
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
        'check_in_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in_time' => 'timestamp',
        ];
    }

    /**
     * Mendapatkan event terkait absensi ini.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Mendapatkan user (peserta) yang melakukan absensi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
