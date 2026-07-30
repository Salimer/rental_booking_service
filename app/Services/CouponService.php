<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    /**
     * Validate and calculate coupon discount amount for a booking.
     */
    public function applyCoupon(string $code, float $totalAmount, ?int $orgId = null): array
    {
        $coupon = Coupon::where('code', $code)
            ->where('status', 1)
            ->first();

        if (! $coupon) {
            throw new \InvalidArgumentException('الكوبون غير صالح أو غير موجود.');
        }

        $now = Carbon::now();
        if ($coupon->start_date && Carbon::parse($coupon->start_date)->gt($now)) {
            throw new \InvalidArgumentException('الكوبون غير فاعل بعد.');
        }

        if ($coupon->expire_date && Carbon::parse($coupon->expire_date)->lt($now)) {
            throw new \InvalidArgumentException('انتهت صلاحية الكوبون.');
        }

        if ($coupon->min_booking_amount && $totalAmount < $coupon->min_booking_amount) {
            throw new \InvalidArgumentException("الحد الأدنى للشراء لاستخدام الكوبون هو {$coupon->min_booking_amount}.");
        }

        if ($coupon->org_id && $orgId && $coupon->org_id != $orgId) {
            throw new \InvalidArgumentException('هذا الكوبون غير مخصص لهذه المؤسسة.');
        }

        $discount = 0;
        if ($coupon->discount_type === 'percent') {
            $discount = ($totalAmount * $coupon->discount) / 100;
            if ($coupon->max_discount_amount && $discount > $coupon->max_discount_amount) {
                $discount = $coupon->max_discount_amount;
            }
        } else {
            $discount = $coupon->discount;
        }

        $finalAmount = max(0, $totalAmount - $discount);

        return [
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount,
            'discount_amount' => round($discount, 2),
            'original_amount' => $totalAmount,
            'final_amount' => round($finalAmount, 2),
        ];
    }
}
