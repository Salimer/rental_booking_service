<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\Country;
use App\Models\Neighborhood;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_fetch_countries_cities_and_neighborhoods()
    {
        $country = Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 1]);
        $city = City::create(['country_id' => $country->id, 'name_ar' => 'صنعاء', 'name_en' => 'Sanaa', 'status' => 1]);
        $neigh = Neighborhood::create(['city_id' => $city->id, 'name_ar' => 'حدة', 'name_en' => 'Haddah', 'status' => 1]);

        $type = \App\Models\Type::create(['name_ar' => 'Chalet', 'name_en' => 'Chalet']);
        $org = \App\Models\Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $p = \App\Models\Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'city_id' => $city->id, 'neighborhood_id' => $neigh->id, 'title_ar' => 'Prop 1', 'status' => 'active']);
        \App\Models\Unit::create(['property_id' => $p->id, 'name_ar' => 'Unit 1', 'name_en' => 'Unit 1', 'status' => 'active']);

        $this->getJson('/api/v1/countries')
            ->assertStatus(200)
            ->assertJsonCount(1);

        $this->getJson("/api/v1/cities?country_id={$country->id}")
            ->assertStatus(200)
            ->assertJsonCount(1);

        $this->getJson("/api/v1/neighborhoods?city_id={$city->id}")
            ->assertStatus(200)
            ->assertJsonCount(1);
    }
}
