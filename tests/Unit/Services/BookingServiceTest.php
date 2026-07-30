<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\DateHold;
use App\Models\Org;
use App\Models\Price;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use App\Services\BookingService;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookingService $bookingService;

    protected function setUp(): void
    {
        parent::setUp();

        $notificationService = new NotificationService;
        $this->bookingService = new BookingService($notificationService);
    }

    protected function createTestType(): Type
    {
        return Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
    }

    public function test_estimate_price_calculates_correct_nightly_breakdown_and_custom_prices()
    {
        $type = $this->createTestType();
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Villa', 'title_en' => 'Villa']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1']);

        // Default price
        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'default',
            'price_sar' => 100,
            'price_usd' => 30,
            'price_yer_s' => 150,
            'price_yer_n' => 160,
        ]);

        // Custom override price on day 2
        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'override',
            'start_date' => '2026-08-02',
            'end_date' => '2026-08-02',
            'price_sar' => 250,
            'price_usd' => 70,
            'price_yer_s' => 350,
            'price_yer_n' => 360,
        ]);

        $estimate = $this->bookingService->estimatePrice([
            'unit_id' => $unit->id,
            'check_in_date' => '2026-08-01',
            'check_out_date' => '2026-08-03',
            'currency' => 'SAR',
        ]);

        $this->assertEquals(2, $estimate['nights_count']);
        $this->assertEquals(350, $estimate['total_amount']); // 100 + 250
        $this->assertCount(2, $estimate['nightly_breakdown']);
    }

    public function test_estimate_price_throws_exception_if_checkin_is_after_checkout()
    {
        $type = $this->createTestType();
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Villa', 'title_en' => 'Villa']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1']);

        $this->expectException(\InvalidArgumentException::class);

        $this->bookingService->estimatePrice([
            'unit_id' => $unit->id,
            'check_in_date' => '2026-08-05',
            'check_out_date' => '2026-08-02',
        ]);
    }

    public function test_initiate_payment_creates_date_hold_and_returns_payment_url()
    {
        $user = User::create(['id' => 1, 'name' => 'John', 'email' => 'john@example.com']);
        $type = $this->createTestType();
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Villa', 'title_en' => 'Villa']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1']);

        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'default',
            'price_sar' => 150,
            'price_usd' => 40,
            'price_yer_s' => 200,
            'price_yer_n' => 210,
        ]);

        $result = $this->bookingService->initiatePayment($user, [
            'unit_id' => $unit->id,
            'check_in_date' => '2026-09-01',
            'check_out_date' => '2026-09-03',
            'guests_count' => 2,
            'currency' => 'SAR',
        ]);

        $this->assertArrayHasKey('hold_token', $result);
        $this->assertArrayHasKey('payment_url', $result);
        $this->assertEquals(300, $result['total_amount']);

        $this->assertDatabaseHas('date_holds', [
            'hold_token' => $result['hold_token'],
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'status' => 'active',
        ]);
    }

    public function test_handle_payment_webhook_creates_booking_and_marks_hold_consumed()
    {
        Http::fake([
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $user = User::create(['id' => 5, 'name' => 'Alice', 'email' => 'alice@example.com', 'phone' => '+966500000000']);
        $type = $this->createTestType();
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Resort', 'title_en' => 'Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Chalet 1', 'name_en' => 'Chalet 1']);

        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'default',
            'price_sar' => 200,
            'price_usd' => 50,
            'price_yer_s' => 300,
            'price_yer_n' => 310,
        ]);

        $hold = DateHold::create([
            'unit_id' => $unit->id,
            'user_id' => $user->id,
            'hold_token' => 'HOLD-TEST-123',
            'check_in_date' => '2026-10-01',
            'check_out_date' => '2026-10-04',
            'nights_count' => 3,
            'guests_count' => 2,
            'guest_name' => 'Alice',
            'guest_phone' => '+966500000000',
            'guest_email' => 'alice@example.com',
            'total_amount' => 600,
            'currency' => 'SAR',
            'status' => 'active',
            'expires_at' => now()->addMinutes(15),
        ]);

        $webhookResult = $this->bookingService->handlePaymentWebhook([
            'hold_token' => 'HOLD-TEST-123',
            'payment_reference' => 'PAY-REF-999',
            'status' => 'paid',
            'gateway' => 'kuraimi',
        ]);

        $this->assertTrue($webhookResult['success']);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('date_holds', [
            'id' => $hold->id,
            'status' => 'consumed',
        ]);
    }

    public function test_cancel_booking_updates_status_and_creates_log()
    {
        Http::fake([
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $user = User::create(['id' => 10, 'name' => 'Bob', 'phone' => '+966511111111']);
        $type = $this->createTestType();
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Villa', 'title_en' => 'Villa']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit', 'name_en' => 'Unit']);

        $booking = Booking::create([
            'reference_no' => 'BK-REF-1',
            'user_id' => $user->id,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'org_id' => $org->id,
            'guest_name' => 'Bob',
            'guest_phone' => '+966511111111',
            'unit_price' => 100,
            'currency' => 'SAR',
            'nights_count' => 2,
            'submitted_at' => now(),
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'check_in_date' => '2026-11-01',
            'check_out_date' => '2026-11-03',
        ]);

        $success = $this->bookingService->cancelBooking($booking, 'Plans changed');

        $this->assertTrue($success);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
            'cancellation_reason' => 'Plans changed',
        ]);

        $this->assertDatabaseHas('booking_status_logs', [
            'booking_id' => $booking->id,
            'new_status' => 'cancelled',
        ]);
    }
}
