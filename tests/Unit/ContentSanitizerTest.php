<?php

namespace Tests\Unit;

use App\Services\Wp\ContentSanitizer;
use Tests\TestCase;

class ContentSanitizerTest extends TestCase
{
    protected ContentSanitizer $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sanitizer = new ContentSanitizer();
    }

    public function test_removes_script_tags_and_their_content(): void
    {
        $html = '<p>Hello</p><script>alert("xss")</script><p>World</p>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('script', $clean);
        $this->assertStringNotContainsString('alert', $clean);
        $this->assertStringContainsString('Hello', $clean);
        $this->assertStringContainsString('World', $clean);
    }

    public function test_removes_iframes_and_embedded_objects(): void
    {
        $html = '<p>Before</p><iframe src="https://evil.example/payload"></iframe><object data="x"></object><embed src="y"><p>After</p>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('iframe', $clean);
        $this->assertStringNotContainsString('evil.example', $clean);
        $this->assertStringNotContainsString('<object', $clean);
        $this->assertStringNotContainsString('<embed', $clean);
    }

    public function test_strips_inline_event_handlers(): void
    {
        $html = '<p onclick="steal()" onmouseover=\'x()\' onerror=alert(1)>Text</p>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onmouseover', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringContainsString('Text', $clean);
    }

    public function test_neutralizes_javascript_urls(): void
    {
        $html = '<a href="javascript:alert(1)">Link</a><img src="javascript:bad()">';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('javascript:', $clean);
    }

    public function test_removes_php_snippets(): void
    {
        $html = '<p>A</p><?php evil_code(); ?><p>B</p><?="short"?>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('evil_code', $clean);
        $this->assertStringNotContainsString('<?php', $clean);
        $this->assertStringNotContainsString('<?=', $clean);
    }

    public function test_removes_unresolvable_shortcodes(): void
    {
        $html = '<p>[contact-form-7 id="123"]Keep this[/contact-form-7] [gallery ids="1,2"]</p>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringNotContainsString('[contact-form-7', $clean);
        $this->assertStringNotContainsString('[gallery', $clean);
        $this->assertStringNotContainsString('[/contact-form-7]', $clean);
        $this->assertStringContainsString('Keep this', $clean);
    }

    public function test_keeps_safe_content_intact(): void
    {
        $html = '<h2>Heading</h2><p>Paragraph with <strong>bold</strong> and <a href="https://example.com">link</a>.</p><ul><li>Item</li></ul>';

        $clean = $this->sanitizer->clean($html);

        $this->assertStringContainsString('<h2>Heading</h2>', $clean);
        $this->assertStringContainsString('<strong>bold</strong>', $clean);
        $this->assertStringContainsString('href="https://example.com"', $clean);
        $this->assertStringContainsString('<li>Item</li>', $clean);
    }

    public function test_rewrites_mapped_image_urls(): void
    {
        $html = '<img src="https://old-wp.com/wp-content/uploads/2024/05/photo.jpg" alt="x">';
        $map = ['https://old-wp.com/wp-content/uploads/2024/05/photo.jpg' => 'https://new.local/posts/photo.jpg'];

        $rewritten = $this->sanitizer->rewriteImages($html, $map);

        $this->assertStringContainsString('https://new.local/posts/photo.jpg', $rewritten);
        $this->assertStringNotContainsString('old-wp.com', $rewritten);
    }

    public function test_rewrites_unmapped_uploads_by_basename(): void
    {
        $html = '<img src="https://cdn.old-wp.com/wp-content/uploads/2024/05/photo.jpg?resize=600">';
        $map = ['https://other.com/wp-content/uploads/2024/05/photo.jpg' => 'https://new.local/posts/photo.jpg'];

        $rewritten = $this->sanitizer->rewriteImages($html, $map);

        $this->assertStringContainsString('https://new.local/posts/photo.jpg', $rewritten);
    }

    public function test_plain_text_extraction(): void
    {
        $text = $this->sanitizer->toPlainText('<p>One</p><p>Two  spaces</p>');

        $this->assertSame('One Two spaces', $text);
    }
}
