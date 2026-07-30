<?php

namespace Tests\Feature\Api;

use App\Models\Booking;
use App\Models\Org;
use App\Models\Price;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_estimate_booking_price()
    {
        $token = 'test-token';

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 1,
                'name' => 'User One',
                'email' => 'one@example.com',
            ], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Beach Flat', 'title_en' => 'Beach Flat']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 10', 'name_en' => 'Unit 10']);

        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'default',
            'price_sar' => 100,
            'price_usd' => 30,
            'price_yer_s' => 150,
            'price_yer_n' => 160,
        ]);

        $response = $this->withToken($token)->postJson('/api/v1/bookings/estimate', [
            'unit_id' => $unit->id,
            'check_in_date' => '2026-08-01',
            'check_out_date' => '2026-08-03',
            'currency' => 'SAR',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('nights_count', 2)
            ->assertJsonPath('total_amount', 200);
    }

    public function test_initiate_payment_for_booking()
    {
        $token = 'test-token-initiate';

        User::create(['id' => 2, 'name' => 'User Two', 'email' => 'two@example.com']);

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 2,
                'name' => 'User Two',
                'email' => 'two@example.com',
            ], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Beach Flat', 'title_en' => 'Beach Flat']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 10', 'name_en' => 'Unit 10']);

        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => 'default',
            'price_sar' => 150,
            'price_usd' => 40,
            'price_yer_s' => 200,
            'price_yer_n' => 210,
        ]);

        $response = $this->withToken($token)->postJson('/api/v1/bookings/initiate-payment', [
            'unit_id' => $unit->id,
            'check_in_date' => '2026-09-01',
            'check_out_date' => '2026-09-04',
            'guests_count' => 2,
            'currency' => 'SAR',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('total_amount', 450);
    }

    public function test_cancel_booking()
    {
        $token = 'test-token-cancel';

        $user = User::create(['id' => 3, 'name' => 'User Three', 'email' => 'three@example.com']);

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 3,
                'name' => 'User Three',
                'email' => 'three@example.com',
            ], 200),
            '*/api/v1/internal/notify' => Http::response(['success' => true], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Beach Flat', 'title_en' => 'Beach Flat']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Unit 10', 'name_en' => 'Unit 10']);

        $booking = Booking::create([
            'reference_no' => 'BK-CANCEL-1',
            'user_id' => $user->id,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'org_id' => $org->id,
            'guest_name' => 'User Three',
            'guest_phone' => '+966533333333',
            'unit_price' => 150,
            'currency' => 'SAR',
            'nights_count' => 2,
            'submitted_at' => now(),
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'check_in_date' => '2026-10-01',
            'check_out_date' => '2026-10-03',
        ]);

        $response = $this->withToken($token)->postJson("/api/v1/bookings/{$booking->id}/cancel", [
            'reason' => 'Emergency',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Booking cancelled successfully');
    }
}
