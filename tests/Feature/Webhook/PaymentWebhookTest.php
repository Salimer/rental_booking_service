<?php

namespace Tests\Feature\Webhook;

use App\Models\DateHold;
use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_webhook_creates_booking_when_payment_is_paid()
    {
        Http::fake([
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $user = User::create(['id' => 7, 'name' => 'Sara', 'email' => 'sara@example.com', 'phone' => '+966522222222']);
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Flat']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Flat Resort', 'title_en' => 'Flat Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 1', 'name_en' => 'Flat 1']);

        $hold = DateHold::create([
            'unit_id' => $unit->id,
            'user_id' => $user->id,
            'hold_token' => 'HOLD-WEBHOOK-99',
            'check_in_date' => '2026-12-01',
            'check_out_date' => '2026-12-05',
            'nights_count' => 4,
            'guests_count' => 2,
            'guest_name' => 'Sara',
            'guest_phone' => '+966522222222',
            'guest_email' => 'sara@example.com',
            'total_amount' => 800,
            'currency' => 'SAR',
            'status' => 'active',
            'expires_at' => now()->addMinutes(15),
        ]);

        $payload = [
            'hold_token' => 'HOLD-WEBHOOK-99',
            'payment_reference' => 'PAY-99999',
            'status' => 'paid',
            'gateway' => 'kuraimi',
        ];

        $secret = config('services.monolith.webhook_secret', env('WEBHOOK_SECRET', 'jac_rental_webhook_secret_2026'));
        $signature = hash_hmac('sha256', json_encode($payload), $secret);

        $response = $this->withHeader('X-Webhook-Signature', $signature)
            ->postJson('/api/v1/webhooks/payment-complete', $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);
    }

    public function test_payment_webhook_creates_pending_booking_when_payment_is_pending()
    {
        Http::fake([
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $user = User::create(['id' => 8, 'name' => 'Ali', 'email' => 'ali@example.com', 'phone' => '+966533333333']);
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Flat']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Flat Resort', 'title_en' => 'Flat Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 2', 'name_en' => 'Flat 2']);

        $hold = DateHold::create([
            'unit_id' => $unit->id,
            'user_id' => $user->id,
            'hold_token' => 'HOLD-WEBHOOK-PENDING',
            'check_in_date' => '2026-12-10',
            'check_out_date' => '2026-12-12',
            'nights_count' => 2,
            'guests_count' => 1,
            'guest_name' => 'Ali',
            'guest_phone' => '+966533333333',
            'guest_email' => 'ali@example.com',
            'total_amount' => 400,
            'currency' => 'SAR',
            'status' => 'active',
            'expires_at' => now()->addMinutes(15),
        ]);

        $payload = [
            'hold_token' => 'HOLD-WEBHOOK-PENDING',
            'payment_reference' => 'PAY-TRANSFER-100',
            'status' => 'pending',
            'gateway' => 'manual_transfer',
        ];

        $secret = config('services.monolith.webhook_secret', env('WEBHOOK_SECRET', 'jac_rental_webhook_secret_2026'));
        $signature = hash_hmac('sha256', json_encode($payload), $secret);

        $response = $this->withHeader('X-Webhook-Signature', $signature)
            ->postJson('/api/v1/webhooks/payment-complete', $payload);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);
    }
}
