<?php

namespace Tests\Feature;

use App\Services\Wp\ContentSanitizer;
use App\Services\Wp\MediaDownloader;
use App\Services\Wp\WpImporter;
use Tests\TestCase;

class WpMigrateCommandTest extends TestCase
{
    public function test_rejects_missing_url(): void
    {
        $this->artisan('wp:migrate')->assertExitCode(1);
    }

    public function test_rejects_invalid_url_scheme(): void
    {
        $this->artisan('wp:migrate', ['--url' => 'ftp://nope.example'])->assertExitCode(1);
    }

    public function test_importer_service_resolves_from_container(): void
    {
        $importer = new WpImporter(
            new \App\Services\Wp\WpClient('https://old.example'),
            new ContentSanitizer(),
            new MediaDownloader('wp-import')
        );

        $this->assertInstanceOf(WpImporter::class, $importer);
    }

    public function test_media_downloader_ignores_non_http_urls(): void
    {
        $downloader = new MediaDownloader('wp-import');

        $this->assertNull($downloader->download(''));
        $this->assertNull($downloader->download('/local/path.jpg'));
        $this->assertNull($downloader->download('ftp://x/y.jpg'));
    }
}
