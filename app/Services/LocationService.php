<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Collection;

class LocationService
{
    public function getCountries(): Collection
    {
        return Country::where('status', 1)->orderBy('name_ar', 'asc')->get();
    }

    public function getCities(?int $countryId = null): Collection
    {
        $query = City::where('status', 1)
            ->whereHas('properties', function ($q) {
                $q->where('status', 'active')->whereHas('units', function ($u) {
                    $u->where('status', 'active');
                });
            });

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        return $query->orderBy('name_ar', 'asc')->get();
    }

    public function getNeighborhoods(?int $cityId = null): Collection
    {
        $query = Neighborhood::where('status', 1)
            ->whereHas('properties', function ($q) {
                $q->where('status', 'active')->whereHas('units', function ($u) {
                    $u->where('status', 'active');
                });
            });

        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        return $query->orderBy('name_ar', 'asc')->get();
    }
}
