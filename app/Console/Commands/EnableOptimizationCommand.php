<?php

namespace App\Console\Commands;

use Botble\Setting\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EnableOptimizationCommand extends Command
{
    protected $signature = 'cms:enable-litespeed-cache';

    protected $description = 'Enable LiteSpeed Cache, PageSpeed HTML minification, JS deferral, and browser caching rules';

    public function handle(): int
    {
        $this->info('Enabling LiteSpeed Cache & PageSpeed Optimizations...');

        // 1. Enable Botble Optimize Package & WebP Conversion Settings
        $settings = [
            'optimize_page_speed_enable'    => '1',
            'optimize_collapse_white_space' => '1',
            'optimize_defer_javascript'     => '1',
            'optimize_insert_dns_prefetch'  => '1',
            'optimize_remove_comments'      => '1',
            'optimize_remove_quotes'        => '1',
            'media_convert_image_to_webp'   => '1',
        ];

        foreach ($settings as $key => $val) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $val]);
        }

        $this->info('✔ Botble PageSpeed & Minification settings enabled.');

        // 2. Enhance public/.htaccess with LiteSpeed LSCache & Gzip Rules
        $htaccessPath = public_path('.htaccess');
        if (File::exists($htaccessPath)) {
            $htaccessContent = File::get($htaccessPath);

            $litespeedRules = <<<HTACCESS

# --- BEGIN LiteSpeed Cache & Browser Optimization ---
<IfModule LiteSpeed>
    CacheEngine on
    CacheLookup on
    CacheKeyObject select_distance
    CacheGlobal on
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/x-javascript "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/x-javascript application/json image/svg+xml
</IfModule>
# --- END LiteSpeed Cache & Browser Optimization ---

HTACCESS;

            if (! str_contains($htaccessContent, 'BEGIN LiteSpeed Cache')) {
                File::append($htaccessPath, $litespeedRules);
                $this->info('✔ Added LiteSpeed Cache & Gzip/Brotli rules to public/.htaccess');
            }
        }

        $this->callSilent('optimize:clear');
        $this->info('✔ Cache cleared & Optimization Engine fully active!');

        return self::SUCCESS;
    }
}
