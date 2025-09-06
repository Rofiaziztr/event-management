<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id',
        'uploader_id',
        'title',
        'type',
        'file_path',
        'content',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        'updated_at' => 'datetime',];
    }

    /**
     * Mendapatkan event tempat dokumen ini diunggah.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Mendapatkan user yang mengunggah dokumen ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
