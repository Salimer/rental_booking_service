<?php

namespace Tests\Unit\Services;

use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CouponService $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = new CouponService;
    }

    public function test_apply_coupon_calculates_percentage_discount()
    {
        Coupon::create([
            'title_ar' => 'خصم الصيف',
            'title_en' => 'Summer Discount',
            'code' => 'SUMMER20',
            'discount_type' => 'percent',
            'discount' => 20,
            'min_booking_amount' => 100,
            'max_discount_amount' => 50,
            'status' => 1,
        ]);

        $result = $this->couponService->applyCoupon('SUMMER20', 200.00);

        $this->assertEquals('SUMMER20', $result['code']);
        $this->assertEquals(40.00, $result['discount_amount']); // 20% of 200 = 40
        $this->assertEquals(160.00, $result['final_amount']);
    }

    public function test_apply_coupon_respects_max_discount_cap()
    {
        Coupon::create([
            'title_ar' => 'عرض خيالي',
            'title_en' => 'Mega Deal',
            'code' => 'MEGA50',
            'discount_type' => 'percent',
            'discount' => 50,
            'min_booking_amount' => 100,
            'max_discount_amount' => 30, // Cap is 30
            'status' => 1,
        ]);

        $result = $this->couponService->applyCoupon('MEGA50', 500.00);

        $this->assertEquals(30.00, $result['discount_amount']); // capped at 30
        $this->assertEquals(470.00, $result['final_amount']);
    }

    public function test_apply_coupon_fails_if_min_purchase_not_met()
    {
        Coupon::create([
            'title_ar' => 'خصم مميز',
            'title_en' => 'VIP',
            'code' => 'VIP100',
            'discount_type' => 'fixed',
            'discount' => 50,
            'min_booking_amount' => 500,
            'status' => 1,
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->couponService->applyCoupon('VIP100', 200.00);
    }
}
