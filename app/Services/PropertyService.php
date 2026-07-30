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
        $query = Property::with(['units', 'type', 'city', 'country', 'neighborhood', 'org'])
            ->where('status', 'active');

        if (! empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        if (! empty($filters['country_id'])) {
            $query->where('country_id', $filters['country_id']);
        }

        if (! empty($filters['neighborhood_id'])) {
            $query->where('neighborhood_id', $filters['neighborhood_id']);
        }

        if (! empty($filters['type_id'])) {
            $query->where('type_id', $filters['type_id']);
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

        if (! empty($filters['min_price']) || ! empty($filters['max_price'])) {
            $query->whereHas('units', function (Builder $q) use ($filters) {
                if (! empty($filters['min_price'])) {
                    $q->where('base_price', '>=', $filters['min_price']);
                }
                if (! empty($filters['max_price'])) {
                    $q->where('base_price', '<=', $filters['max_price']);
                }
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
            'units.amenities',
            'units.prices',
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
