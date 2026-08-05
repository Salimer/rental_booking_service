<?php

namespace Tests\Feature\Api;

use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_active_properties()
    {
        $type = Type::create(['name_ar' => 'فيلا', 'name_en' => 'Villa']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);

        $p1 = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sunset Villa', 'title_en' => 'Sunset Villa', 'status' => 'active']);
        \App\Models\Unit::create(['property_id' => $p1->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1', 'status' => 'active', 'price' => 100]);

        Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Hidden Cottage', 'title_en' => 'Hidden Cottage', 'status' => 'inactive']);

        $response = $this->getJson('/api/v1/properties');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'properties');
    }

    public function test_public_can_view_single_property_details()
    {
        $type = Type::create(['name_ar' => 'فيلا', 'name_en' => 'Villa']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);

        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sunset Villa', 'title_en' => 'Sunset Villa', 'status' => 'active']);

        $response = $this->getJson("/api/v1/properties/{$property->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $property->id)
            ->assertJsonPath('title', 'Sunset Villa');
    }
}
