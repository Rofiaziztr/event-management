<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\Storage;

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
            'updated_at' => 'datetime',
        ];
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

    /**
     * Get the public URL for the file.
     * Supports both Supabase Storage and local storage.
     *
     * @return string|null
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        $supabase = app(SupabaseStorageService::class);

        if ($supabase->isConfigured() && $this->isSupabaseFile()) {
            return $supabase->getPublicUrl($this->file_path);
        }

        // Fallback to local storage
        return Storage::url($this->file_path);
    }

    /**
     * Check if the file is stored in Supabase Storage.
     * Files uploaded to Supabase use paths like "events/{id}/filename"
     * while local files use "documents/filename".
     *
     * @return bool
     */
    public function isSupabaseFile(): bool
    {
        return str_starts_with($this->file_path, 'events/');
    }
}