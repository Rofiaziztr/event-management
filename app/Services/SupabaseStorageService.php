<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        $this->url = config('supabase.url');
        $this->key = config('supabase.key');
        $this->bucket = config('supabase.storage.bucket');
    }

    /**
     * Upload a file to Supabase Storage.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @param  string  $folder  Optional subfolder within the bucket
     * @return string|null  The file path within the bucket, or null on failure
     */
    public function upload(UploadedFile $file, string $folder = ''): ?string
    {
        try {
            $fileName = $this->generateFileName($file);
            $filePath = $folder ? "{$folder}/{$fileName}" : $fileName;

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
                'Content-Type' => $file->getMimeType(),
            ])->withBody(
                file_get_contents($file->getRealPath()),
                $file->getMimeType()
            )->post("{$this->url}/storage/v1/object/{$this->bucket}/{$filePath}");

            if ($response->successful()) {
                Log::info('File uploaded to Supabase Storage', [
                    'path' => $filePath,
                    'bucket' => $this->bucket,
                ]);
                return $filePath;
            }

            Log::error('Failed to upload file to Supabase Storage', [
                'status' => $response->status(),
                'body' => $response->body(),
                'path' => $filePath,
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase Storage upload error', [
                'message' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);
            return null;
        }
    }

    /**
     * Delete a file from Supabase Storage.
     *
     * @param  string  $filePath  The file path within the bucket
     * @return bool  Whether the deletion was successful
     */
    public function delete(string $filePath): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
            ])->delete("{$this->url}/storage/v1/object/{$this->bucket}/{$filePath}");

            if ($response->successful()) {
                Log::info('File deleted from Supabase Storage', [
                    'path' => $filePath,
                    'bucket' => $this->bucket,
                ]);
                return true;
            }

            Log::warning('Failed to delete file from Supabase Storage', [
                'status' => $response->status(),
                'body' => $response->body(),
                'path' => $filePath,
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Supabase Storage delete error', [
                'message' => $e->getMessage(),
                'path' => $filePath,
            ]);
            return false;
        }
    }

    /**
     * Get the public URL for a file in Supabase Storage.
     *
     * @param  string  $filePath  The file path within the bucket
     * @return string  The public URL
     */
    public function getPublicUrl(string $filePath): string
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$filePath}";
    }

    /**
     * Check if Supabase Storage is configured.
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->url) && !empty($this->key);
    }

    /**
     * Generate a unique file name preserving the original extension.
     *
     * @param  UploadedFile  $file
     * @return string
     */
    protected function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His');
        $random = Str::random(8);

        return "{$timestamp}_{$random}.{$extension}";
    }
}
