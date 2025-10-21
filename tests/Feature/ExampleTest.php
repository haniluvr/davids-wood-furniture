<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Test a simple route that doesn't require database
        $response = $this->get('/test-route');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok',
            'message' => 'Laravel route is working'
        ]);
    }

    /**
     * Test health endpoint
     */
    public function test_health_endpoint_works(): void
    {
        $response = $this->get('/health');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok'
        ]);
    }
}
