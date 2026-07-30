<?php

namespace Tests\Feature\Api;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CouponApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_apply_coupon()
    {
        $token = 'test-token';

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 15,
                'name' => 'Test User',
                'email' => 'user@example.com',
            ], 200),
        ]);

        Coupon::create([
            'title_ar' => 'خصم 10%',
            'title_en' => '10% Off',
            'code' => 'SAVE10',
            'discount_type' => 'percent',
            'discount' => 10,
            'status' => 1,
        ]);

        $response = $this->withToken($token)->postJson('/api/v1/coupons/apply', [
            'code' => 'SAVE10',
            'amount' => 500,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('discount_amount', 50)
            ->assertJsonPath('final_amount', 450);
    }
}
