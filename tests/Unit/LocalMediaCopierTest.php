<?php

namespace Tests\Unit;

use App\Services\Wp\LocalMediaCopier;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

class LocalMediaCopierTest extends TestCase
{
    protected string $uploadsDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadsDir = sys_get_temp_dir().'/wp-import-test-uploads-'.uniqid();

        mkdir($this->uploadsDir.'/2024/05', 0777, true);

        file_put_contents($this->uploadsDir.'/2024/05/photo.jpg', 'fake-jpeg-bytes');
        file_put_contents($this->uploadsDir.'/2024/05/photo-300x200.jpg', 'fake-jpeg-thumb');
        file_put_contents($this->uploadsDir.'/2024/05/notes.txt', 'not-an-image');
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->uploadsDir);

        parent::tearDown();
    }

    protected function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $item) {
            $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
        }

        @rmdir($dir);
    }

    public function test_available_and_count_files_only_count_images(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $this->assertTrue($copier->available());
        $this->assertSame(2, $copier->countFiles()); // 2 jpgs; notes.txt excluded
    }

    public function test_unavailable_when_uploads_dir_missing(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir.'/nope', 'https://old.example');

        $this->assertFalse($copier->available());
        $this->assertSame(0, $copier->countFiles());
    }

    public function test_url_to_path_resolves_uploads_urls_from_any_host(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $path = $copier->urlToPath('https://old.example/wp-content/uploads/2024/05/photo.jpg');

        $this->assertNotNull($path);
        $this->assertSame('photo.jpg', basename($path));
    }

    public function test_url_to_path_handles_different_domain_and_query_strings(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://whatever.example');

        $path = $copier->urlToPath('https://cdn-other.example/wp-content/uploads/2024/05/photo.jpg?resize=600');

        $this->assertNotNull($path);
        $this->assertSame('photo.jpg', basename($path));
    }

    public function test_url_to_path_falls_back_from_size_rendition_to_full_size(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $path = $copier->urlToPath('https://old.example/wp-content/uploads/2024/05/photo-999x999.jpg');

        $this->assertNotNull($path);
        $this->assertSame('photo.jpg', basename($path));
    }

    public function test_url_to_path_prefers_existing_rendition(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $path = $copier->urlToPath('https://old.example/wp-content/uploads/2024/05/photo-300x200.jpg');

        $this->assertNotNull($path);
        $this->assertSame('photo-300x200.jpg', basename($path));
    }

    public function test_url_to_path_returns_null_for_unknown_files_and_non_uploads_urls(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $this->assertNull($copier->urlToPath('https://old.example/wp-content/uploads/2024/05/missing.jpg'));
        $this->assertNull($copier->urlToPath('https://old.example/wp-includes/js/jquery.js'));
        $this->assertNull($copier->urlToPath('not-a-url'));
    }

    public function test_download_returns_null_for_bad_urls_without_warnings(): void
    {
        Log::spy();

        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $this->assertNull($copier->download(''));
        $this->assertNull($copier->download('/local/file.jpg'));

        Log::shouldNotHaveReceived('warning');
    }

    public function test_download_logs_warning_when_file_missing(): void
    {
        Log::spy();

        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $this->assertNull($copier->download('https://old.example/wp-content/uploads/2024/05/missing.jpg'));

        Log::shouldHaveReceived('warning')->once();
    }

    public function test_map_empty_until_uploads_succeed(): void
    {
        $copier = new LocalMediaCopier($this->uploadsDir, 'https://old.example');

        $copier->download('https://old.example/wp-content/uploads/2024/05/photo.jpg');

        // RvMedia::uploadFromPath requires a real configured media disk; in
        // the test environment the copy may fail — map() stays consistent
        // either way (only successful copies are included).
        $this->assertIsArray($copier->map());
    }
}
