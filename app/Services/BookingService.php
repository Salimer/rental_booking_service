<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingStatusLog;
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
        $unit = Unit::with(['property', 'prices'])->findOrFail($data['unit_id']);
        $checkIn = Carbon::parse($data['check_in_date'])->startOfDay();
        $checkOut = Carbon::parse($data['check_out_date'])->startOfDay();

        if ($checkIn->gte($checkOut)) {
            throw new \InvalidArgumentException('Check-out date must be after check-in date.');
        }

        $currency = strtoupper($data['currency'] ?? 'SAR');
        $nights = $checkIn->diffInDays($checkOut);
        $totalBasePrice = 0;
        $nightlyBreakdown = [];

        $period = CarbonPeriod::create($checkIn, $checkOut->copy()->subDay());

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $nightPrice = (float) $unit->getActivePriceForDate($date, $currency);

            $totalBasePrice += $nightPrice;
            $nightlyBreakdown[] = [
                'date' => $formattedDate,
                'price' => $nightPrice,
            ];
        }

        return [
            'unit_id' => $unit->id,
            'property_name' => $unit->property->title ?? '',
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'nights_count' => $nights,
            'unit_price' => (float) $unit->price,
            'total_base_price' => $totalBasePrice,
            'total_amount' => $totalBasePrice,
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

        if ($status !== 'paid') {
            $hold->update(['status' => 'failed']);

            return ['success' => false, 'message' => 'Payment status is not paid.'];
        }

        return DB::transaction(function () use ($hold, $paymentRef, $payload) {
            $unit = Unit::with('property')->find($hold->unit_id);
            $referenceNo = 'BK-'.strtoupper(Str::random(8));

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
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'submitted_at' => now(),
                'confirmed_at' => now(),
            ]);

            BookingPayment::create([
                'booking_id' => $booking->id,
                'reference_no' => $paymentRef,
                'payment_reference' => $paymentRef,
                'gateway' => $payload['gateway'] ?? 'online',
                'amount' => $hold->total_amount,
                'currency' => $hold->currency,
                'payment_status' => 'paid',
                'payment_method' => $payload['gateway'] ?? 'online',
                'paid_at' => now(),
            ]);

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'new_status' => 'confirmed',
                'comment' => 'Booking created and confirmed via payment webhook.',
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
