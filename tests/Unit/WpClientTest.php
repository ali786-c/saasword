<?php

namespace Tests\Unit;

use App\Services\Wp\WpClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WpClientTest extends TestCase
{
    public function test_fetch_all_paginates_using_x_wp_total_pages(): void
    {
        Http::fake([
            'https://old.example/wp-json/wp/v2/posts?_embed=1&per_page=50&page=1' => Http::response(
                [['id' => 1], ['id' => 2]],
                200,
                ['X-WP-TotalPages' => '2']
            ),
            'https://old.example/wp-json/wp/v2/posts?_embed=1&per_page=50&page=2' => Http::response(
                [['id' => 3]],
                200,
                ['X-WP-TotalPages' => '2']
            ),
        ]);

        $client = new WpClient('https://old.example');

        $items = $client->posts();

        $this->assertCount(3, $items);
        $this->assertSame([1, 2, 3], $items->pluck('id')->all());
    }

    public function test_fetch_all_stops_when_page_param_exceeds_available(): void
    {
        Http::fake([
            'https://old.example/wp-json/wp/v2/tags*' => Http::sequence()
                ->push([['id' => 1], ['id' => 2]], 200, ['X-WP-TotalPages' => ''])
                ->push([], 200), // empty final page
        ]);

        $client = new WpClient('https://old.example');

        $this->assertCount(2, $client->tags());
    }

    public function test_treats_rest_invalid_page_400_as_end_of_collection(): void
    {
        Http::fake([
            'https://old.example/wp-json/wp/v2/media/99' => Http::response(
                ['code' => 'rest_post_invalid_page_number'],
                400
            ),
        ]);

        $client = new WpClient('https://old.example');

        $this->assertNull($client->media(99));
    }

    public function test_connection_failure_returns_null_instead_of_throwing(): void
    {
        Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException('timeout');
        });

        $client = new WpClient('https://down.example');

        $this->assertNull($client->media(1));
        $this->assertTrue($client->posts()->isEmpty());
    }

    public function test_sends_request_to_correct_base_url(): void
    {
        Http::fake([
            'https://old.example/*' => Http::response([['id' => 7]], 200, ['X-WP-TotalPages' => '1']),
        ]);

        (new WpClient('https://old.example'))->posts();

        Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://old.example/wp-json/wp/v2/posts'));
    }
}
