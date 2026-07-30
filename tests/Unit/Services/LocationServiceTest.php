<?php

namespace Tests\Unit\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\Neighborhood;
use App\Services\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LocationService $locationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->locationService = new LocationService;
    }

    public function test_get_countries_returns_only_active_countries()
    {
        Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 1]);
        Country::create(['name_ar' => 'غير نشط', 'name_en' => 'Inactive', 'status' => 0]);

        $countries = $this->locationService->getCountries();

        $this->assertCount(1, $countries);
        $this->assertEquals('Yemen', $countries[0]->name_en);
    }

    public function test_get_cities_filters_by_country_id()
    {
        $country1 = Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 1]);
        $country2 = Country::create(['name_ar' => 'السعودية', 'name_en' => 'KSA', 'status' => 1]);

        $city1 = City::create(['country_id' => $country1->id, 'name_ar' => 'صنعاء', 'name_en' => 'Sanaa', 'status' => 1]);
        $city2 = City::create(['country_id' => $country2->id, 'name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'status' => 1]);

        $yemenCities = $this->locationService->getCities($country1->id);

        $this->assertCount(1, $yemenCities);
        $this->assertEquals($city1->id, $yemenCities[0]->id);
    }

    public function test_get_neighborhoods_filters_by_city_id()
    {
        $country = Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 1]);
        $city = City::create(['country_id' => $country->id, 'name_ar' => 'صنعاء', 'name_en' => 'Sanaa', 'status' => 1]);

        $neigh1 = Neighborhood::create(['city_id' => $city->id, 'name_ar' => 'حدة', 'name_en' => 'Haddah', 'status' => 1]);

        $neighborhoods = $this->locationService->getNeighborhoods($city->id);

        $this->assertCount(1, $neighborhoods);
        $this->assertEquals($neigh1->id, $neighborhoods[0]->id);
    }
}
