<?php

namespace Tests\Feature\Api;

use App\Models\Booking;
use App\Models\DateHold;
use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_availability_returns_true_when_no_overlapping_bookings()
    {
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Flat']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Flat Resort', 'title_en' => 'Flat Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 1', 'name_en' => 'Flat 1']);

        $response = $this->getJson("/api/v1/units/{$unit->id}/availability?check_in_date=2026-10-01&check_out_date=2026-10-05");

        $response->assertStatus(200)
            ->assertJson([
                'unit_id' => $unit->id,
                'is_available' => true,
            ]);
    }

    public function test_check_availability_returns_false_when_overlapping_booking_exists()
    {
        $user = User::create(['id' => 10, 'name' => 'John', 'email' => 'john@example.com', 'phone' => '+966511111111']);
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Flat']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Flat Resort', 'title_en' => 'Flat Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 1', 'name_en' => 'Flat 1']);

        Booking::create([
            'reference_no' => 'BK-TEST-1',
            'user_id' => $user->id,
            'org_id' => $org->id,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'check_in_date' => '2026-10-02',
            'check_out_date' => '2026-10-06',
            'nights_count' => 4,
            'guests_count' => 1,
            'guest_name' => 'John',
            'guest_phone' => '+966511111111',
            'unit_price' => 200,
            'currency' => 'SAR',
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'submitted_at' => now(),
        ]);

        $response = $this->getJson("/api/v1/units/{$unit->id}/availability?check_in_date=2026-10-01&check_out_date=2026-10-05");

        $response->assertStatus(200)
            ->assertJson([
                'unit_id' => $unit->id,
                'is_available' => false,
            ]);
    }

    public function test_calendar_mode_returns_unavailable_dates()
    {
        $user = User::create(['id' => 11, 'name' => 'Mark', 'email' => 'mark@example.com', 'phone' => '+966522222222']);
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Flat']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Flat Resort', 'title_en' => 'Flat Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 1', 'name_en' => 'Flat 1']);

        Booking::create([
            'reference_no' => 'BK-TEST-2',
            'user_id' => $user->id,
            'org_id' => $org->id,
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'check_in_date' => '2026-10-10',
            'check_out_date' => '2026-10-12',
            'nights_count' => 2,
            'guests_count' => 1,
            'guest_name' => 'Mark',
            'guest_phone' => '+966522222222',
            'unit_price' => 200,
            'currency' => 'SAR',
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'submitted_at' => now(),
        ]);

        $response = $this->getJson("/api/v1/units/{$unit->id}/availability?check_in_date=2026-10-01&check_out_date=2026-10-31&mode=calendar");

        $response->assertStatus(200)
            ->assertJsonFragment(['2026-10-10'])
            ->assertJsonFragment(['2026-10-11']);
    }
}
