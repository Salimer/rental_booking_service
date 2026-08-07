<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingStatusLog;
use App\Models\BookingTransaction;
use App\Models\DateHold;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Calculate price estimate for a unit without creating holds or bookings.
     */
    public function estimatePrice(array $data): array
    {
        $unitId = $data['unit_id'] ?? $data['rental_unit_id'] ?? null;
        if (! $unitId) {
            throw new \InvalidArgumentException('Unit ID is required.');
        }

        $unit = Unit::with(['property.org.settings', 'prices'])->findOrFail($unitId);
        $checkIn = Carbon::parse(substr($data['check_in_date'], 0, 10))->startOfDay();
        $checkOut = Carbon::parse(substr($data['check_out_date'], 0, 10))->startOfDay();

        if ($checkIn->gte($checkOut)) {
            throw new \InvalidArgumentException('Check-out date must be after check-in date.');
        }

        $currency = strtoupper($data['currency'] ?? $unit->currency ?? 'SAR');
        $nights = $checkIn->diffInDays($checkOut);
        $totalBasePrice = 0;
        $nightlyBreakdown = [];
        $nightlyPrices = [];

        $period = CarbonPeriod::create($checkIn, $checkOut->copy()->subDay());

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $nightPrice = (float) $unit->getActivePriceForDate($date, $currency);

            $totalBasePrice += $nightPrice;
            $nightlyPrices[] = $nightPrice;
            $nightlyBreakdown[] = [
                'date' => $formattedDate,
                'price' => $nightPrice,
            ];
        }

        // 1. Free Night Promotion (e.g. Book 3 Pay 2)
        $freeNightDiscount = 0.0;
        $org = $unit->property->org ?? null;
        $orgSettings = $org ? $org->settings : null;

        if ($orgSettings && $orgSettings->free_night_enabled && count($nightlyPrices) >= $orgSettings->free_night_min_nights) {
            if ($orgSettings->free_night_max_nights === null || count($nightlyPrices) <= $orgSettings->free_night_max_nights) {
                $freeNightsCountApplied = min($orgSettings->free_nights_count, max(0, count($nightlyPrices) - 1));
                if ($freeNightsCountApplied > 0) {
                    $lastN = array_slice($nightlyPrices, -$freeNightsCountApplied);
                    $freeNightDiscount = (float) array_sum($lastN);
                }
            }
        }

        // 2. Coupon Discount
        $couponDiscount = 0.0;
        $couponCode = $data['coupon_code'] ?? null;
        if (!empty($couponCode)) {
            try {
                $couponService = app(CouponService::class);
                $amountForCoupon = max(0, $totalBasePrice - $freeNightDiscount);
                $couponRes = $couponService->applyCoupon($couponCode, $amountForCoupon, $org ? $org->id : null);
                $couponDiscount = (float) ($couponRes['discount_amount'] ?? 0.0);
            } catch (\Exception $e) {
                // If coupon invalid, discount remains 0
            }
        }

        $totalAmount = max(0, $totalBasePrice - $freeNightDiscount - $couponDiscount);

        return [
            'unit_id' => $unit->id,
            'property_name' => $unit->property->title ?? '',
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'nights_count' => $nights,
            'unit_price' => (float) $unit->price,
            'subtotal' => $totalBasePrice,
            'total_base_price' => $totalBasePrice,
            'free_night_discount' => $freeNightDiscount,
            'coupon_discount' => $couponDiscount,
            'total_amount' => $totalAmount,
            'currency' => $currency,
            'nightly_breakdown' => $nightlyBreakdown,
        ];
    }

    /**
     * Initiate payment flow: create short-lived DateHold and return payment details.
     */
    public function initiatePayment(User $user, array $data): array
    {
        $estimate = $this->estimatePrice($data);
        $unit = Unit::findOrFail($data['unit_id']);

        $holdToken = 'HOLD-'.strtoupper(Str::random(12));
        $expiresAt = now()->addMinutes(15);

        $hold = DateHold::create([
            'unit_id' => $unit->id,
            'user_id' => $user->id,
            'hold_token' => $holdToken,
            'check_in_date' => $estimate['check_in_date'],
            'check_out_date' => $estimate['check_out_date'],
            'nights_count' => $estimate['nights_count'],
            'guests_count' => $data['guests_count'] ?? 1,
            'guest_name' => $data['guest_name'] ?? $user->name,
            'guest_phone' => $data['guest_phone'] ?? $user->phone,
            'guest_email' => $data['guest_email'] ?? $user->email,
            'guest_note' => $data['guest_note'] ?? null,
            'total_amount' => $estimate['total_amount'],
            'currency' => $estimate['currency'],
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        $monolithUrl = rtrim(config('services.monolith.url', env('MONOLITH_URL', 'http://localhost:8000')), '/');
        $paymentUrl = "{$monolithUrl}/api/v1/payments/initiate?hold_token={$holdToken}&amount={$estimate['total_amount']}";

        return [
            'hold_token' => $holdToken,
            'expires_at' => $expiresAt->toIso8601String(),
            'total_amount' => $estimate['total_amount'],
            'currency' => $estimate['currency'],
            'payment_url' => $paymentUrl,
        ];
    }

    /**
     * Create pending booking directly from customer app.
     */
    public function createBooking(User $user, array $data): array
    {
        $unitId = $data['unit_id'] ?? $data['rental_unit_id'] ?? null;
        if (! $unitId) {
            throw new \InvalidArgumentException('Unit ID is required.');
        }

        $data['unit_id'] = $unitId;
        $estimate = $this->estimatePrice($data);
        $unit = Unit::with('property')->findOrFail($unitId);

        $referenceNo = 'RBK-'.date('Ymd').'-'.strtoupper(Str::random(6));

        $booking = Booking::create([
            'reference_no' => $referenceNo,
            'user_id' => $user->id,
            'property_id' => $unit->property_id,
            'unit_id' => $unit->id,
            'org_id' => $unit->property->org_id ?? null,
            'check_in_date' => $estimate['check_in_date'],
            'check_out_date' => $estimate['check_out_date'],
            'nights_count' => $estimate['nights_count'],
            'guests_count' => $data['guests_count'] ?? 1,
            'guest_name' => $data['guest_name'] ?? $user->name,
            'guest_phone' => $data['guest_phone'] ?? $user->phone,
            'guest_email' => $data['guest_email'] ?? $user->email,
            'guest_note' => $data['guest_note'] ?? null,
            'unit_price' => $unit->price,
            'currency' => $estimate['currency'],
            'payment_status' => 'pending',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        BookingTransaction::create([
            'booking_id' => $booking->id,
            'subtotal' => $estimate['subtotal'],
            'discount_amount' => ($estimate['free_night_discount'] ?? 0.0) + ($estimate['coupon_discount'] ?? 0.0),
            'tax_amount' => 0.0,
            'total_amount' => $estimate['total_amount'],
            'currency' => $estimate['currency'],
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'new_status' => 'pending',
            'comment' => 'Booking requested by customer.',
        ]);

        return [
            'message' => 'Booking requested successfully',
            'booking' => $booking->load(['property', 'unit', 'transaction']),
        ];
    }

    /**
     * Process direct customer payment update.
     */
    public function processPayment(Booking $booking, array $data): array
    {
        $gateway = $data['gateway'] ?? $data['payment_method'] ?? 'online';
        $refNo = $data['reference_no'] ?? ('P-'.strtoupper(Str::random(10)));
        $amount = isset($data['amount']) ? (float)$data['amount'] : (float)($booking->transaction->total_amount ?? $booking->unit_price);
        $isSubmitted = !empty($data['payment_submitted']);

        $booking->update([
            'status' => $isSubmitted ? 'paid' : 'pending',
            'payment_status' => $isSubmitted ? 'paid' : 'pending',
        ]);

        BookingPayment::create([
            'booking_id' => $booking->id,
            'reference_no' => $refNo,
            'payment_reference' => $refNo,
            'gateway' => $gateway,
            'amount' => $amount,
            'currency' => $booking->currency ?? 'YER_S',
            'payment_status' => $isSubmitted ? 'paid' : 'pending',
            'payment_method' => $gateway,
            'response_payload' => $data,
            'paid_at' => $isSubmitted ? now() : null,
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'new_status' => $booking->status,
            'comment' => "Payment processed via {$gateway}. Ref: {$refNo}",
        ]);

        return [
            'message' => 'Payment processed successfully',
            'booking' => $booking->fresh(['property', 'unit', 'transaction', 'payments']),
        ];
    }

    /**
     * Check unit availability or get list of unavailable dates for a calendar window.
     */
    public function checkAvailability(int $unitId, string $checkInDate, string $checkOutDate, ?string $mode = null): array
    {
        $checkIn = Carbon::parse($checkInDate)->startOfDay();
        $checkOut = Carbon::parse($checkOutDate)->startOfDay();

        $bookings = Booking::where('unit_id', $unitId)
            ->whereIn('status', ['confirmed', 'submitted', 'pending'])
            ->where('check_in_date', '<', $checkOut->format('Y-m-d'))
            ->where('check_out_date', '>', $checkIn->format('Y-m-d'))
            ->get();

        $holds = DateHold::where('unit_id', $unitId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->where('check_in_date', '<', $checkOut->format('Y-m-d'))
            ->where('check_out_date', '>', $checkIn->format('Y-m-d'))
            ->get();

        if ($mode === 'calendar') {
            $unavailableSet = [];

            foreach ($bookings as $b) {
                $period = CarbonPeriod::create($b->check_in_date, Carbon::parse($b->check_out_date)->subDay());
                foreach ($period as $d) {
                    $unavailableSet[$d->format('Y-m-d')] = true;
                }
            }

            foreach ($holds as $h) {
                $period = CarbonPeriod::create($h->check_in_date, Carbon::parse($h->check_out_date)->subDay());
                foreach ($period as $d) {
                    $unavailableSet[$d->format('Y-m-d')] = true;
                }
            }

            return [
                'unit_id' => $unitId,
                'unavailable_dates' => array_values(array_keys($unavailableSet)),
            ];
        }

        $isAvailable = $bookings->isEmpty() && $holds->isEmpty();

        return [
            'unit_id' => $unitId,
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'is_available' => $isAvailable,
            'available' => $isAvailable,
        ];
    }

    /**
     * Process payment webhook from monolith to finalize booking.
     */
    public function handlePaymentWebhook(array $payload): array
    {
        $holdToken = $payload['hold_token'] ?? null;
        $paymentRef = $payload['payment_reference'] ?? null;
        $status = $payload['status'] ?? 'failed';

        $hold = DateHold::where('hold_token', $holdToken)
            ->where('status', 'active')
            ->first();

        if (! $hold) {
            return ['success' => false, 'message' => 'Active hold token not found or expired.'];
        }

        if ($status !== 'paid' && $status !== 'pending') {
            $hold->update(['status' => 'failed']);

            return ['success' => false, 'message' => 'Payment status is not paid or pending.'];
        }

        $isPending = ($status === 'pending');
        $chargedAmount = isset($payload['charged_amount']) && $payload['charged_amount'] !== null
            ? (float) $payload['charged_amount']
            : (float) $hold->total_amount;

        $gatewayDiscount = isset($payload['gateway_discount_amount']) ? (float) $payload['gateway_discount_amount'] : 0.0;
        $gatewayReason = $payload['gateway_discount_reason'] ?? null;

        return DB::transaction(function () use ($hold, $paymentRef, $payload, $isPending, $chargedAmount, $gatewayDiscount, $gatewayReason) {
            $unit = Unit::with('property')->find($hold->unit_id);
            $referenceNo = 'BK-'.strtoupper(Str::random(8));

            $bookingStatus = $isPending ? 'pending' : 'confirmed';
            $paymentStatus = $isPending ? 'pending' : 'paid';

            $booking = Booking::create([
                'reference_no' => $referenceNo,
                'user_id' => $hold->user_id,
                'property_id' => $unit->property_id,
                'unit_id' => $unit->id,
                'org_id' => $unit->property->org_id ?? null,
                'check_in_date' => $hold->check_in_date,
                'check_out_date' => $hold->check_out_date,
                'nights_count' => $hold->nights_count,
                'guests_count' => $hold->guests_count,
                'guest_name' => $hold->guest_name,
                'guest_phone' => $hold->guest_phone,
                'guest_email' => $hold->guest_email,
                'guest_note' => $hold->guest_note,
                'unit_price' => $unit->price,
                'currency' => $hold->currency,
                'payment_status' => $paymentStatus,
                'status' => $bookingStatus,
                'submitted_at' => now(),
                'confirmed_at' => $isPending ? null : now(),
            ]);

            BookingPayment::create([
                'booking_id' => $booking->id,
                'reference_no' => $paymentRef,
                'payment_reference' => $paymentRef,
                'gateway' => $payload['gateway'] ?? 'online',
                'amount' => $chargedAmount,
                'currency' => $hold->currency,
                'payment_status' => $paymentStatus,
                'payment_method' => $payload['gateway'] ?? 'online',
                'response_payload' => [
                    'charged_amount' => $chargedAmount,
                    'gateway_discount_amount' => $gatewayDiscount,
                    'gateway_discount_reason' => $gatewayReason,
                    'raw_payload' => $payload,
                ],
                'paid_at' => $isPending ? null : now(),
            ]);

            $logComment = $isPending 
                ? 'Booking created in pending state via payment webhook (awaiting verification).'
                : 'Booking created and confirmed via payment webhook.';
            if ($gatewayDiscount > 0) {
                $logComment .= " Applied gateway discount: {$gatewayDiscount} ({$gatewayReason}). Net charged: {$chargedAmount}.";
            }

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'new_status' => $bookingStatus,
                'comment' => $logComment,
            ]);

            $hold->update(['status' => 'consumed']);

            // Trigger customer notification via monolith bridge using user_id
            $this->notificationService->sendPush(
                null,
                'حجز مؤكد',
                "تم تأكيد حجزك رقم {$booking->reference_no} بنجاح!",
                [
                    'type' => 'booking_status',
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->reference_no,
                    'status' => 'confirmed',
                ],
                $booking->user_id
            );

            return [
                'success' => true,
                'message' => 'Booking successfully created.',
                'booking_id' => $booking->id,
                'reference_no' => $booking->reference_no,
            ];
        });
    }

    /**
     * Cancel an existing booking.
     */
    public function cancelBooking(Booking $booking, ?string $reason = null): bool
    {
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
            'comment' => $reason ?? 'Cancelled by user',
        ]);

        $this->notificationService->sendPush(
            null,
            'إلغاء حجز',
            "تم إلغاء حجزك رقم {$booking->reference_no}.",
            [
                'type' => 'booking_status',
                'booking_id' => $booking->id,
                'booking_reference' => $booking->reference_no,
                'status' => 'cancelled',
            ],
            $booking->user_id
        );

        return true;
    }
}
