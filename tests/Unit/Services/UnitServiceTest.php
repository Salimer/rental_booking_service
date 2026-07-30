<?php

namespace Tests\Unit\Services;

use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use App\Services\UnitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UnitService $unitService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->unitService = new UnitService;
    }

    public function test_get_unit_details_returns_unit_with_relations()
    {
        $type = Type::create(['name_ar' => 'Chalet', 'name_en' => 'Chalet']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sea Resort', 'title_en' => 'Sea Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Chalet 101', 'name_en' => 'Chalet 101']);

        $fetched = $this->unitService->getUnitDetails($unit->id);

        $this->assertEquals($unit->id, $fetched->id);
        $this->assertEquals('Sea Resort', $fetched->property->title_en);
    }

    public function test_set_custom_price_upserts_price_record()
    {
        $type = Type::create(['name_ar' => 'Chalet', 'name_en' => 'Chalet']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Sea Resort', 'title_en' => 'Sea Resort']);
        $unit = Unit::create(['property_id' => $property->id, 'name_ar' => 'Chalet 101', 'name_en' => 'Chalet 101']);

        $price = $this->unitService->setCustomPrice($unit->id, '2026-09-01', 350.00, 'SAR');

        $this->assertDatabaseHas('prices', [
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_sar' => 350.00,
        ]);
    }
}
