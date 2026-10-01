<?php

namespace Tests\Unit;

use App\Services\Wp\WpDbClient;
use Tests\TestCase;

class WpDbClientTest extends TestCase
{
    public function test_build_post_payload_maps_core_fields(): void
    {
        $payload = WpDbClient::buildPostPayload([
            'ID' => '42',
            'post_name' => 'best-jobs-2026',
            'post_status' => 'publish',
            'post_date' => '2024-05-01 10:00:00',
            'post_date_gmt' => '2024-05-01 05:00:00',
            'post_title' => 'Best Jobs 2026',
            'post_content' => '<p>Body text</p>',
            'post_excerpt' => 'Short excerpt',
        ]);

        $this->assertSame(42, $payload['id']);
        $this->assertSame('best-jobs-2026', $payload['slug']);
        $this->assertSame('publish', $payload['status']);
        $this->assertSame('2024-05-01 05:00:00', $payload['date_gmt']);
        $this->assertSame('Best Jobs 2026', $payload['title']['rendered']);
        $this->assertSame('<p>Body text</p>', $payload['content']['rendered']);
        $this->assertSame('Short excerpt', $payload['excerpt']['rendered']);
        $this->assertFalse($payload['sticky']);
        $this->assertArrayNotHasKey('yoast_head_json', $payload);
        $this->assertArrayNotHasKey('_embedded', $payload);
    }

    public function test_build_post_payload_falls_back_to_local_date_when_gmt_empty(): void
    {
        $payload = WpDbClient::buildPostPayload([
            'ID' => 1,
            'post_name' => 'x',
            'post_date' => '2024-05-01 10:00:00',
            'post_date_gmt' => '0000-00-00 00:00:00',
            'post_title' => 'T',
            'post_content' => '',
            'post_excerpt' => '',
        ]);

        $this->assertSame('2024-05-01 10:00:00', $payload['date_gmt']);
    }

    public function test_build_post_payload_includes_terms_and_thumbnail(): void
    {
        $payload = WpDbClient::buildPostPayload(
            ['ID' => 7, 'post_name' => 's', 'post_title' => 'S', 'post_content' => '', 'post_excerpt' => ''],
            [],
            [[
                ['id' => 3, 'name' => 'Jobs', 'slug' => 'jobs', 'taxonomy' => 'category'],
                ['id' => 9, 'name' => 'Govt', 'slug' => 'govt', 'taxonomy' => 'post_tag'],
            ]],
            ['id' => 55, 'source_url' => 'https://old.example/wp-content/uploads/2024/05/pic.jpg', 'media_details' => ['sizes' => []]],
            true,
        );

        $this->assertCount(2, $payload['_embedded']['wp:term'][0]);
        $this->assertSame('category', $payload['_embedded']['wp:term'][0][0]['taxonomy']);
        $this->assertSame(55, $payload['_embedded']['wp:featuredmedia'][0]['id']);
        $this->assertSame('https://old.example/wp-content/uploads/2024/05/pic.jpg', $payload['_embedded']['wp:featuredmedia'][0]['source_url']);
        $this->assertTrue($payload['sticky']);
    }

    public function test_build_term_payload_maps_fields(): void
    {
        $term = WpDbClient::buildTermPayload([
            'term_id' => '3',
            'name' => 'Scholarships',
            'slug' => 'scholarships',
            'description' => '<p>Funding news</p>',
            'taxonomy' => 'category',
        ]);

        $this->assertSame(3, $term['id']);
        $this->assertSame('Scholarships', $term['name']);
        $this->assertSame('scholarships', $term['slug']);
        $this->assertSame('category', $term['taxonomy']);
    }

    public function test_build_media_payload_handles_null_attachment(): void
    {
        $this->assertNull(WpDbClient::buildMediaPayload(null));
        $this->assertNull(WpDbClient::buildMediaPayload([]));
    }

    public function test_build_media_payload_uses_guid_as_source_url(): void
    {
        $media = WpDbClient::buildMediaPayload([
            'ID' => 55,
            'guid' => 'https://old.example/wp-content/uploads/2024/05/pic.jpg',
        ]);

        $this->assertSame(55, $media['id']);
        $this->assertSame('https://old.example/wp-content/uploads/2024/05/pic.jpg', $media['source_url']);
        $this->assertSame([], $media['media_details']['sizes']);
    }

    public function test_assemble_seo_returns_null_without_plugin_meta(): void
    {
        $this->assertNull(WpDbClient::assembleSeo([], 'Title'));
        $this->assertNull(WpDbClient::assembleSeo(['_edit_lock' => '1'], 'Title'));
    }

    public function test_assemble_seo_from_yoast_meta(): void
    {
        $seo = WpDbClient::assembleSeo([
            '_yoast_wpseo_title' => '%%title%% %%sep%% MySite',
            '_yoast_wpseo_metadesc' => 'Best jobs roundup',
            '_yoast_wpseo_meta-robots-noindex' => '1',
        ], 'Fallback Title');

        $this->assertSame('Fallback Title - MySite', $seo['title']);
        $this->assertSame('Best jobs roundup', $seo['meta_description']);
        $this->assertSame('noindex', $seo['robots']['index']);
    }

    public function test_assemble_seo_from_rank_math_meta(): void
    {
        $seo = WpDbClient::assembleSeo([
            'rank_math_title' => '%title% | Site',
            'rank_math_description' => 'Desc here',
            'rank_math_robots' => ['index'],
        ], 'Fallback Title');

        $this->assertSame('Fallback Title | Site', $seo['title']);
        $this->assertSame('Desc here', $seo['meta_description']);
        $this->assertSame('index', $seo['robots']['index']);
        $this->assertSame([], $seo['og_image']);
    }

    public function test_assemble_seo_prefers_yoast_when_both_present(): void
    {
        $seo = WpDbClient::assembleSeo([
            '_yoast_wpseo_title' => 'Yoast title',
            '_yoast_wpseo_metadesc' => 'Yoast desc',
            'rank_math_title' => 'RankMath title',
            'rank_math_description' => 'RankMath desc',
        ], 'Fallback');

        $this->assertSame('Yoast title', $seo['title']);
        $this->assertSame('Yoast desc', $seo['meta_description']);
    }

    public function test_expand_vars_strips_unknown_template_vars(): void
    {
        $this->assertSame('Hello World', WpDbClient::expandVars('%%title%% %%unknown_var%% World', 'Hello'));
        $this->assertSame('T -', WpDbClient::expandVars('%title% %sep% %sitename%', 'T'));
    }

    public function test_maybe_unserialize_passes_through_plain_values(): void
    {
        $this->assertSame('hello', WpDbClient::maybeUnserialize('hello'));
        $this->assertSame('', WpDbClient::maybeUnserialize(''));
        $this->assertSame('123', WpDbClient::maybeUnserialize('123'));
    }

    public function test_maybe_unserialize_unserializes_arrays(): void
    {
        $serialized = serialize(['index', 'noindex']);

        $this->assertSame(['index', 'noindex'], WpDbClient::maybeUnserialize($serialized));
        $this->assertFalse(WpDbClient::maybeUnserialize('b:0;'));
        $this->assertTrue(WpDbClient::maybeUnserialize('b:1;'));
    }

    public function test_maybe_unserialize_never_instantiates_objects(): void
    {
        // Serialized object payloads are returned as raw strings.
        $payload = 'O:8:"stdClass":0:{}';

        $this->assertSame($payload, WpDbClient::maybeUnserialize($payload));
    }

    public function test_maybe_unserialize_returns_raw_on_corrupt_data(): void
    {
        $corrupt = 'a:5:{not-valid';

        $this->assertSame($corrupt, WpDbClient::maybeUnserialize($corrupt));
    }
}
