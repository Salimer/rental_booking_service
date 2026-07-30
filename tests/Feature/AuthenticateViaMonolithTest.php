<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthenticateViaMonolithTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_unauthenticated_request_without_bearer_token()
    {
        $response = $this->getJson('/api/v1/bookings');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated. Bearer token required.']);
    }

    public function test_rejects_invalid_token_when_monolith_returns_error()
    {
        Http::fake([
            '*/api/v1/auth/me' => Http::response(['message' => 'Unauthorized'], 401),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->getJson('/api/v1/bookings');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid or expired token.']);
    }

    public function test_authenticates_valid_token_and_lazy_provisions_user()
    {
        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 99,
                'f_name' => 'Salim',
                'l_name' => 'User',
                'name' => 'Salim User',
                'phone' => '+966500000000',
                'email' => 'salim@example.com',
                'current_language_key' => 'ar',
                'token_expires_at' => now()->addHour()->toIso8601String(),
            ], 200),
        ]);

        $this->assertDatabaseMissing('users', ['id' => 99]);

        $response = $this->withHeader('Authorization', 'Bearer valid-token-123')
            ->getJson('/api/v1/bookings');

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => 99,
            'name' => 'Salim User',
            'email' => 'salim@example.com',
        ]);
    }

    public function test_uses_cached_token_and_avoids_repeated_monolith_http_calls()
    {
        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 100,
                'name' => 'Cached User',
                'email' => 'cached@example.com',
                'token_expires_at' => now()->addDays(1)->toIso8601String(),
            ], 200),
        ]);

        // First request - populates cache
        $this->withHeader('Authorization', 'Bearer token-cache-test')
            ->getJson('/api/v1/bookings')
            ->assertStatus(200);

        Http::assertSentCount(1);

        // Second request - hits cache
        $this->withHeader('Authorization', 'Bearer token-cache-test')
            ->getJson('/api/v1/bookings')
            ->assertStatus(200);

        // Still only 1 sent HTTP call
        Http::assertSentCount(1);
    }
}
