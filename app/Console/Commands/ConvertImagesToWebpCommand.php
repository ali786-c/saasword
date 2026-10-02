<?php

namespace App\Console\Commands;

use Botble\Media\Models\MediaFile;
use Botble\Setting\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class ConvertImagesToWebpCommand extends Command
{
    protected $signature = 'cms:convert-images-to-webp';

    protected $description = 'Convert existing PNG, JPG, and JPEG images in Media Library to WebP format';

    public function handle(): int
    {
        $this->info('Enabling Automatic WebP Conversion Settings...');

        // 1. Enable WebP Conversion Setting
        Setting::query()->updateOrCreate(['key' => 'media_convert_image_to_webp'], ['value' => '1']);

        $this->info('✔ Automatic WebP Conversion Setting Enabled for All Future Uploads!');
        $this->info('Scanning Media Library for existing JPG / PNG images to convert...');

        $files = MediaFile::query()
            ->whereIn('mime_type', ['image/jpeg', 'image/jpg', 'image/png'])
            ->get();

        if ($files->isEmpty()) {
            $this->info('No existing JPG or PNG images found that require conversion.');
            return self::SUCCESS;
        }

        $this->info("Found {$files->count()} image files to convert.");

        $manager = new ImageManager(new GdDriver());
        $convertedCount = 0;

        foreach ($files as $file) {
            try {
                $relativeUrl = $file->url;
                $extension = strtolower(pathinfo($relativeUrl, PATHINFO_EXTENSION));

                if (! in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    continue;
                }

                $realPath = Storage::path($relativeUrl);
                if (! File::exists($realPath)) {
                    $realPath = public_path('storage/' . $relativeUrl);
                }

                if (! File::exists($realPath)) {
                    continue;
                }

                // New WebP Path
                $dirName = pathinfo($relativeUrl, PATHINFO_DIRNAME);
                $fileNameWithoutExt = pathinfo($relativeUrl, PATHINFO_FILENAME);
                $newRelativeUrl = ($dirName === '.' ? '' : $dirName . '/') . $fileNameWithoutExt . '.webp';
                $newRealPath = pathinfo($realPath, PATHINFO_DIRNAME) . '/' . $fileNameWithoutExt . '.webp';

                // Read image and encode to WebP
                $img = $manager->read($realPath);
                $encodedWebp = (string) $img->encode(new WebpEncoder(quality: 85));

                File::put($newRealPath, $encodedWebp);
                if ($newRealPath !== $realPath && File::exists($newRealPath)) {
                    @unlink($realPath);
                }

                // Update Database Record
                $file->url = $newRelativeUrl;
                $file->mime_type = 'image/webp';
                $file->save();

                $convertedCount++;
                $this->line("  ✔ Converted: {$relativeUrl} -> {$newRelativeUrl}");
            } catch (\Throwable $e) {
                $this->error("  ✖ Error converting {$file->url}: " . $e->getMessage());
            }
        }

        $this->callSilent('optimize:clear');
        $this->info("✔ Conversion Complete! Total {$convertedCount} images converted to WebP.");

        return self::SUCCESS;
    }
}
