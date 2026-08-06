<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class CloudStorageService
{
    protected $disk = 's3';
    protected $folder = 'properties';

    public function __construct()
    {
        if (!config('filesystems.disks.s3.key') || !config('filesystems.disks.s3.bucket')) {
            Log::error('S3/R2 storage credentials not configured');
            throw new Exception('Cloud storage is not configured. Please add AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_BUCKET, AWS_ENDPOINT and AWS_URL to your .env file');
        }
    }

    /**
     * Upload une image vers le stockage cloud (Cloudflare R2 / S3-compatible)
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null URL publique de l'image ou null en cas d'erreur
     */
    public function upload($file)
    {
        try {
            if (!$file->isValid()) {
                Log::error('Invalid file uploaded', ['file' => $file->getClientOriginalName()]);
                return null;
            }

            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = $this->folder . '/' . Str::uuid() . '.' . $extension;

            Log::info('Uploading image to cloud storage', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);

            $stored = Storage::disk($this->disk)->putFileAs(
                $this->folder,
                $file,
                basename($filename),
                'public'
            );

            if (!$stored) {
                Log::error('Cloud storage upload failed', ['filename' => $filename]);
                return null;
            }

            $url = Storage::disk($this->disk)->url($filename);

            Log::info('Image uploaded successfully to cloud storage', ['url' => $url]);

            return $url;

        } catch (Exception $e) {
            Log::error('Cloud storage upload exception', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);
            return null;
        }
    }

    /**
     * Upload plusieurs images vers le stockage cloud
     *
     * @param array $files Array d'UploadedFile
     * @return array URLs des images uploadées avec succès
     */
    public function uploadMultiple(array $files)
    {
        $urls = [];
        $failedCount = 0;

        Log::info('Starting multiple image upload', ['count' => count($files)]);

        foreach ($files as $index => $file) {
            $url = $this->upload($file);

            if ($url) {
                $urls[] = $url;
            } else {
                $failedCount++;
                Log::warning('Failed to upload image', [
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                ]);
            }
        }

        Log::info('Multiple image upload completed', [
            'total' => count($files),
            'successful' => count($urls),
            'failed' => $failedCount,
        ]);

        return $urls;
    }

    /**
     * Vérifie si le stockage est configuré
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty(config('filesystems.disks.s3.key')) && !empty(config('filesystems.disks.s3.bucket'));
    }
}
