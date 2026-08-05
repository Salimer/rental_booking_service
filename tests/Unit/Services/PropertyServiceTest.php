<?php

namespace Tests\Unit\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Services\PropertyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PropertyService $propertyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->propertyService = new PropertyService;
    }

    public function test_get_customer_properties_filters_by_city_and_status()
    {
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $type = Type::create(['name_ar' => 'Chalet', 'name_en' => 'Chalet']);
        $country = Country::create(['name_ar' => 'Yemen', 'name_en' => 'Yemen', 'code' => 'YE']);
        $city1 = City::create(['country_id' => $country->id, 'name_ar' => 'Sanaa', 'name_en' => 'Sanaa']);
        $city2 = City::create(['country_id' => $country->id, 'name_ar' => 'Aden', 'name_en' => 'Aden']);

        $prop1 = Property::create([
            'org_id' => $org->id,
            'type_id' => $type->id,
            'city_id' => $city1->id,
            'title_ar' => 'Sanaa Villa',
            'title_en' => 'Sanaa Villa',
            'status' => 'active',
        ]);
        Unit::create(['property_id' => $prop1->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1', 'status' => 'active', 'price' => 100]);

        $prop2 = Property::create([
            'org_id' => $org->id,
            'type_id' => $type->id,
            'city_id' => $city2->id,
            'title_ar' => 'Aden House',
            'title_en' => 'Aden House',
            'status' => 'active',
        ]);

        $result = $this->propertyService->getCustomerProperties(['city_id' => $city1->id]);

        $this->assertEquals(1, $result->total());
        $this->assertEquals($prop1->id, $result->items()[0]->id);
    }

    public function test_get_property_details_returns_property_with_relationships()
    {
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $type = Type::create(['name_ar' => 'Apartment', 'name_en' => 'Apartment']);
        $property = Property::create([
            'org_id' => $org->id,
            'type_id' => $type->id,
            'title_ar' => 'Luxury Flat',
            'title_en' => 'Luxury Flat',
            'status' => 'active',
        ]);

        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Flat 1', 'name_en' => 'Flat 1']);

        $fetched = $this->propertyService->getPropertyDetails($property->id);

        $this->assertEquals($property->id, $fetched->id);
        $this->assertCount(1, $fetched->units);
    }

    public function test_update_property_settings_upserts_settings()
    {
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $type = Type::create(['name_ar' => 'Apartment', 'name_en' => 'Apartment']);
        $property = Property::create([
            'org_id' => $org->id,
            'type_id' => $type->id,
            'title_ar' => 'Luxury Flat',
            'title_en' => 'Luxury Flat',
            'status' => 'active',
        ]);

        $setting = $this->propertyService->updatePropertySettings($property->id, [
            'auto_approve_bookings' => true,
            'cancellation_policy_en' => 'Flexible cancellation policy',
        ]);

        $this->assertDatabaseHas('property_settings', [
            'property_id' => $property->id,
            'auto_approve_bookings' => 1,
        ]);
    }
}
