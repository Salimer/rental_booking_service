<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertySetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyService
{
    /**
     * Search and list properties for customer browsing.
     */
    public function getCustomerProperties(array $filters): LengthAwarePaginator
    {
        $query = Property::with(['units.prices', 'type', 'city', 'country', 'neighborhood', 'org'])
            ->where('status', 'active')
            ->whereHas('units', function (Builder $q) {
                $q->where('status', 'active');
            });

        $cityId = $filters['city_id'] ?? $filters['rental_city_id'] ?? $filters['city'] ?? null;
        if (! empty($cityId)) {
            if (is_numeric($cityId)) {
                $query->where('city_id', (int) $cityId);
            } else {
                $query->whereHas('city', function (Builder $q) use ($cityId) {
                    $q->where('name_en', 'like', "%{$cityId}%")
                      ->orWhere('name_ar', 'like', "%{$cityId}%");
                });
            }
        }

        $countryId = $filters['country_id'] ?? $filters['rental_country_id'] ?? null;
        if (! empty($countryId)) {
            $query->where('country_id', $countryId);
        }

        $neighborhoodId = $filters['neighborhood_id'] ?? $filters['rental_neighborhood_id'] ?? null;
        if (! empty($neighborhoodId)) {
            $query->where('neighborhood_id', $neighborhoodId);
        }

        $typeId = $filters['type_id'] ?? $filters['rental_type_id'] ?? null;
        if (! empty($typeId)) {
            $query->where('type_id', $typeId);
        }

        if (! empty($filters['min_rating'])) {
            $query->where('avg_rating', '>=', (float) $filters['min_rating']);
        }

        if (! empty($filters['has_discount'])) {
            $hasDiscount = (bool) $filters['has_discount'];
            if ($hasDiscount) {
                $today = now()->toDateString();
                $query->where(function (Builder $q) use ($today) {
                    $q->whereHas('coupons', function (Builder $cQ) use ($today) {
                        $cQ->where('status', 1)
                           ->where('start_date', '<=', $today)
                           ->where('expire_date', '>=', $today);
                    })->orWhereHas('org.settings', function (Builder $sQ) {
                        $sQ->where('free_night_enabled', 1);
                    });
                });
            }
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('address_ar', 'like', "%{$search}%")
                    ->orWhere('address_en', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['min_price'])) {
            $minPrice = (float) $filters['min_price'];
            $query->whereHas('units.prices', function (Builder $q) use ($minPrice) {
                $q->where('price_type', 'default')
                  ->where(function (Builder $sub) use ($minPrice) {
                      $sub->where('price_sar', '>=', $minPrice)
                          ->orWhere('price_yer_s', '>=', $minPrice)
                          ->orWhere('price_yer_n', '>=', $minPrice)
                          ->orWhere('price_usd', '>=', $minPrice);
                  });
            });
        }

        if (! empty($filters['max_price'])) {
            $maxPrice = (float) $filters['max_price'];
            $query->whereHas('units.prices', function (Builder $q) use ($maxPrice) {
                $q->where('price_type', 'default')
                  ->where(function (Builder $sub) use ($maxPrice) {
                      $sub->where('price_sar', '<=', $maxPrice)
                          ->orWhere('price_yer_s', '<=', $maxPrice)
                          ->orWhere('price_yer_n', '<=', $maxPrice)
                          ->orWhere('price_usd', '<=', $maxPrice);
                  });
            });
        }

        $sortBy = $filters['sort_by'] ?? 'latest';
        match ($sortBy) {
            'rating' => $query->orderBy('avg_rating', 'desc'),
            'price_low' => $query->orderBy('min_unit_price', 'asc'),
            'price_high' => $query->orderBy('min_unit_price', 'desc'),
            default => $query->latest(),
        };

        $limit = $filters['limit'] ?? 15;
        $offset = $filters['offset'] ?? 1;

        return $query->paginate($limit, ['*'], 'page', $offset);
    }

    /**
     * Get detailed property record with relationships.
     */
    public function getPropertyDetails(int $id): Property
    {
        return Property::with([
            'units' => function ($q) {
                $q->where('status', 'active')->with(['amenities', 'prices']);
            },
            'type',
            'city',
            'country',
            'neighborhood',
            'org',
            'settings',
            'reviews.user',
        ])->where('status', 'active')->findOrFail($id);
    }

    /**
     * Create or update property setting overrides.
     */
    public function updatePropertySettings(int $propertyId, array $settingsData): PropertySetting
    {
        return PropertySetting::updateOrCreate(
            ['property_id' => $propertyId],
            $settingsData
        );
    }
}
