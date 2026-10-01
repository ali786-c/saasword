<?php

namespace Tests\Unit;

use App\Services\Wp\ElementorConverter;
use Tests\TestCase;

class ElementorConverterTest extends TestCase
{
    protected ElementorConverter $converter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->converter = new ElementorConverter();
    }

    public function test_is_elementor_post_detects_valid_and_invalid_meta(): void
    {
        $this->assertTrue($this->converter->isElementorPost([
            '_elementor_data' => '[{"elType":"section"}]',
        ]));

        $this->assertFalse($this->converter->isElementorPost([]));
        $this->assertFalse($this->converter->isElementorPost(['_elementor_data' => '']));
        $this->assertFalse($this->converter->isElementorPost(['_elementor_data' => '[]']));
        $this->assertFalse($this->converter->isElementorPost(['_elementor_data' => 'not json']));
    }

    public function test_returns_null_when_meta_absent(): void
    {
        $this->assertNull($this->converter->convert([]));
        $this->assertNull($this->converter->convert(['_elementor_data' => '']));
    }

    public function test_returns_null_on_unparsable_json(): void
    {
        $this->assertNull($this->converter->convert(['_elementor_data' => '[broken-json']));
    }

    public function test_renders_heading_with_correct_level(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'section',
                    'elements' => [
                        [
                            'elType' => 'column',
                            'elements' => [
                                [
                                    'elType' => 'widget',
                                    'widgetType' => 'heading',
                                    'settings' => ['title' => 'Jobs in Pakistan', 'header_size' => 'h2'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->assertStringContainsString('<h2>Jobs in Pakistan</h2>', $html);
        $this->assertStringNotContainsString('section', $html);
    }

    public function test_renders_heading_link_and_rejects_invalid_size(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => 'Apply Now',
                        'header_size' => 'h9', // invalid -> falls back to h2
                        'link' => ['url' => 'https://example.com/apply'],
                    ],
                ],
            ]),
        ]);

        $this->assertStringContainsString('<h2><a href="https://example.com/apply">Apply Now</a></h2>', $html);
    }

    public function test_renders_text_editor_image_and_button(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => '<p>Scholarship details here</p>'],
                ],
                [
                    'elType' => 'widget',
                    'widgetType' => 'image',
                    'settings' => ['image' => ['url' => 'https://old.example/wp-content/uploads/2024/05/pic.jpg'], 'caption' => 'Campus'],
                ],
                [
                    'elType' => 'widget',
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => 'Read more',
                        'link' => ['url' => 'https://example.com/post', 'is_external' => 'yes', 'nofollow' => 'yes'],
                    ],
                ],
            ]),
        ]);

        $this->assertStringContainsString('<p>Scholarship details here</p>', $html);
        $this->assertStringContainsString('<img src="https://old.example/wp-content/uploads/2024/05/pic.jpg" alt="Campus">', $html);
        $this->assertStringContainsString('<a href="https://example.com/post" target="_blank" rel="noopener" rel="nofollow">Read more</a>', $html);
    }

    public function test_renders_icon_list_as_ul(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'widget',
                    'widgetType' => 'icon-list',
                    'settings' => [
                        'icon_list' => [
                            ['text' => 'Deadline: 30 Nov'],
                            ['text' => ''],
                            ['text' => 'Fully funded'],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->assertStringContainsString('<ul>', $html);
        $this->assertStringContainsString('<li>Deadline: 30 Nov</li>', $html);
        $this->assertStringContainsString('<li>Fully funded</li>', $html);
        $this->assertSame(2, substr_count($html, '<li>')); // empty item skipped
    }

    public function test_video_becomes_plain_link_not_iframe(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'widget',
                    'widgetType' => 'video',
                    'settings' => ['video_type' => 'youtube', 'youtube' => 'https://youtu.be/abc123'],
                ],
            ]),
        ]);

        $this->assertStringContainsString('href="https://youtu.be/abc123"', $html);
        $this->assertStringNotContainsString('<iframe', $html);
    }

    public function test_unknown_widgets_and_empty_widgets_produce_nothing(): void
    {
        // All-empty render returns null (NOT '') so the importer falls back
        // to the raw post_content snapshot instead of wiping the content.
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                ['elType' => 'widget', 'widgetType' => 'google_maps', 'settings' => ['address' => 'x']],
                ['elType' => 'widget', 'widgetType' => 'heading', 'settings' => ['title' => '']],
                ['elType' => 'section', 'elements' => []],
            ]),
        ]);

        $this->assertNull($html);
    }

    public function test_elements_render_in_document_order(): void
    {
        $html = $this->converter->convert([
            '_elementor_data' => json_encode([
                [
                    'elType' => 'section',
                    'elements' => [
                        [
                            'elType' => 'column',
                            'elements' => [
                                ['elType' => 'widget', 'widgetType' => 'heading', 'settings' => ['title' => 'First', 'header_size' => 'h1']],
                            ],
                        ],
                    ],
                ],
                [
                    'elType' => 'section',
                    'elements' => [
                        [
                            'elType' => 'column',
                            'elements' => [
                                ['elType' => 'widget', 'widgetType' => 'heading', 'settings' => ['title' => 'Second', 'header_size' => 'h1']],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->assertMatchesRegularExpression('/First.*Second/s', $html);
    }

    public function test_convert_or_fallback_uses_elementor_when_present(): void
    {
        $html = $this->converter->convertOrFallback(
            ['_elementor_data' => json_encode([
                ['elType' => 'widget', 'widgetType' => 'heading', 'settings' => ['title' => 'From Elementor', 'header_size' => 'h2']],
            ])],
            '<p>degraded snapshot</p>',
        );

        $this->assertStringContainsString('From Elementor', $html);
        $this->assertStringNotContainsString('degraded snapshot', $html);
    }

    public function test_convert_or_fallback_uses_post_content_without_elementor(): void
    {
        $html = $this->converter->convertOrFallback([], '<p>plain post</p>');

        $this->assertSame('<p>plain post</p>', $html);
    }
}
