<?php

namespace App\Console\Commands;

use Botble\Media\Facades\RvMedia;
use Botble\Setting\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GenerateTextWatermarkCommand extends Command
{
    protected $signature = 'cms:generate-watermark {--text=careerinpak.com}';

    protected $description = 'Auto-generate a clean text watermark PNG and enable Botble watermark settings';

    public function handle(): int
    {
        $text = $this->option('text') ?? 'careerinpak.com';
        $this->info("Generating text watermark PNG for '{$text}'...");

        // Image dimensions
        $width = 600;
        $height = 120;

        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Transparent background
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefilledrectangle($image, 0, 0, $width, $height, $transparent);

        imagealphablending($image, true);

        // Colors
        $white = imagecolorallocatealpha($image, 255, 255, 255, 10); // Crisp White with 90% opacity
        $shadow = imagecolorallocatealpha($image, 0, 0, 0, 50);      // Dark Shadow

        // Try using system font or standard font
        $fontFile = public_path('vendor/core/core/base/fonts/Roboto-Bold.ttf');
        if (! File::exists($fontFile)) {
            // Fallback font search
            $fontFile = resource_path('fonts/Roboto-Bold.ttf');
        }

        if (File::exists($fontFile)) {
            $fontSize = 32;
            // Draw Shadow
            imagettftext($image, $fontSize, 0, 22, 72, $shadow, $fontFile, $text);
            // Draw Main Text
            imagettftext($image, $fontSize, 0, 20, 70, $white, $fontFile, $text);
        } else {
            // Built-in GD font fallback
            $font = 5;
            $x = 20;
            $y = 45;
            imagestring($image, $font, $x + 2, $y + 2, $text, $shadow);
            imagestring($image, $font, $x, $y, $text, $white);
        }

        // Save image to storage disk and public storage
        $watermarkFileName = 'watermark.png';
        $storagePath = storage_path('app/public/' . $watermarkFileName);
        $publicPath = public_path('storage/' . $watermarkFileName);

        File::ensureDirectoryExists(dirname($storagePath));
        File::ensureDirectoryExists(dirname($publicPath));

        imagepng($image, $storagePath);
        imagepng($image, $publicPath);
        imagedestroy($image);

        $this->info("✔ Watermark PNG saved to: {$publicPath}");

        // Update Botble Settings
        Setting::query()->updateOrCreate(['key' => 'media_watermark_enabled'], ['value' => '1']);
        Setting::query()->updateOrCreate(['key' => 'media_watermark_source'], ['value' => $watermarkFileName]);
        Setting::query()->updateOrCreate(['key' => 'media_watermark_position'], ['value' => 'bottom-right']);
        Setting::query()->updateOrCreate(['key' => 'media_watermark_size'], ['value' => '25']);
        Setting::query()->updateOrCreate(['key' => 'media_watermark_opacity'], ['value' => '80']);

        $this->callSilent('optimize:clear');

        $this->info("✔ Botble Media Watermark Settings Updated & Enabled!");
        $this->line("  - Enabled: Yes");
        $this->line("  - Text: {$text}");
        $this->line("  - Source: {$watermarkFileName}");
        $this->line("  - Position: bottom-right");
        $this->line("  - Scale Size: 25%");
        $this->line("  - Opacity: 80%");

        return self::SUCCESS;
    }
}
