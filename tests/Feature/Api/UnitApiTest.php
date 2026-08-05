<?php

namespace Tests\Feature\Api;

use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UnitApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_single_unit_details()
    {
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org', 'preferred_currency' => 'SAR']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sea Tower', 'title_en' => 'Sea Tower', 'logo' => 'prop_logo.png']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Penthouse', 'name_en' => 'Penthouse', 'images' => ['unit_img1.png']]);

        $response = $this->getJson("/api/v1/units/{$unit->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $unit->id)
            ->assertJsonPath('name', 'Penthouse');

        $this->assertStringContainsString('storage/rental_unit/unit_img1.png', $response->json('images.0'));
        $this->assertStringContainsString('storage/rental_property/prop_logo.png', $response->json('property.logo'));
    }

    public function test_authenticated_vendor_can_get_and_set_unit_prices()
    {
        $token = 'test-vendor-token';

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 10,
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
            ], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org', 'preferred_currency' => 'SAR']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sea Tower', 'title_en' => 'Sea Tower']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Penthouse', 'name_en' => 'Penthouse']);

        // Set price
        $response = $this->withToken($token)->postJson("/api/v1/units/{$unit->id}/prices", [
            'date' => '2026-09-10',
            'price' => 500.00,
            'currency' => 'SAR',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Price updated successfully');

        // Get prices
        $getResponse = $this->withToken($token)->getJson("/api/v1/units/{$unit->id}/prices?start_date=2026-09-01&end_date=2026-09-30");

        $getResponse->assertStatus(200)
            ->assertJsonCount(1);
    }
}
