<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic smoke test.
     *
     * Before installation the CMS redirects / to the web installer (302);
     * once installed it renders the homepage (200).
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $this->assertContains($response->status(), [200, 302]);
    }
}
