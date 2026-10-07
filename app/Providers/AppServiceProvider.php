<?php

namespace App\Providers;

use Botble\Blog\Models\Post;
use Botble\Media\Events\MediaFileUploaded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists(\Botble\JobAlertsPopup\Providers\JobAlertsPopupServiceProvider::class)) {
            $this->app->register(\Botble\JobAlertsPopup\Providers\JobAlertsPopupServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (class_exists(Post::class)) {
            Post::saved(function () {
                \App\Services\LlmsTxtService::generate();
            });

            Post::deleted(function () {
                \App\Services\LlmsTxtService::generate();
            });
        }

        // Automatic Backend WebP Image Conversion on Upload
        if (class_exists(MediaFileUploaded::class)) {
            Event::listen(MediaFileUploaded::class, function (MediaFileUploaded $event): void {
                $file = $event->file;

                if (! $file || ! str_starts_with($file->mime_type, 'image/')) {
                    return;
                }

                if (in_array($file->mime_type, ['image/webp', 'image/svg+xml', 'image/gif'])) {
                    return;
                }

                try {
                    $storageDisk = Storage::disk('public');
                    $relativeFilePath = ltrim($file->url, '/');
                    $fullPath = $storageDisk->path($relativeFilePath);

                    if (! file_exists($fullPath)) {
                        $fullPath = public_path('storage/' . $relativeFilePath);
                    }

                    if (! file_exists($fullPath)) {
                        return;
                    }

                    $imageInfo = @getimagesize($fullPath);
                    if (! $imageInfo) {
                        return;
                    }

                    $mime = $imageInfo['mime'];
                    $image = match ($mime) {
                        'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
                        'image/png' => @imagecreatefrompng($fullPath),
                        'image/bmp' => @imagecreatefrombmp($fullPath),
                        default => null,
                    };

                    if (! $image) {
                        return;
                    }

                    if ($mime === 'image/png') {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, true);
                        imagesavealpha($image, true);
                    }

                    $pathInfo = pathinfo($fullPath);
                    $newWebpFileName = $pathInfo['filename'] . '.webp';
                    $newWebpFullPath = $pathInfo['dirname'] . '/' . $newWebpFileName;

                    if (imagewebp($image, $newWebpFullPath, 80)) {
                        imagedestroy($image);

                        if (file_exists($fullPath) && $fullPath !== $newWebpFullPath) {
                            @unlink($fullPath);
                        }

                        $dirname = pathinfo($file->url, PATHINFO_DIRNAME);
                        $newUrl = ($dirname && $dirname !== '.') ? $dirname . '/' . $newWebpFileName : $newWebpFileName;
                        
                        $file->url = ltrim($newUrl, './');
                        $file->mime_type = 'image/webp';
                        $file->size = @filesize($newWebpFullPath) ?: $file->size;
                        $file->save();
                    }
                } catch (Throwable $e) {
                    Log::error('Auto WebP Conversion Error: ' . $e->getMessage());
                }
            });
        }
    }
}
