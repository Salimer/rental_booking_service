<?php

namespace App\Services;

use App\Models\Price;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class UnitService
{
    /**
     * Get details for a specific unit.
     */
    public function getUnitDetails(int $id): Unit
    {
        return Unit::with(['property.org', 'amenities', 'prices'])->findOrFail($id);
    }

    /**
     * Set or update custom price for a unit on specific dates.
     */
    public function setCustomPrice(int $unitId, string $date, float $price, ?string $currency = 'SAR'): Price
    {
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        $currencyField = match (strtoupper($currency ?? 'SAR')) {
            'USD' => 'price_usd',
            'YER_S' => 'price_yer_s',
            'YER_N' => 'price_yer_n',
            default => 'price_sar',
        };

        return Price::updateOrCreate(
            [
                'priceable_id' => $unitId,
                'priceable_type' => Unit::class,
                'price_type' => Price::TYPE_OVERRIDE,
                'start_date' => $formattedDate,
                'end_date' => $formattedDate,
            ],
            [
                'priceable_id' => $unitId,
                'priceable_type' => Unit::class,
                'price_type' => Price::TYPE_OVERRIDE,
                'start_date' => $formattedDate,
                'end_date' => $formattedDate,
                $currencyField => $price,
                'price_sar' => $price,
                'price_usd' => $price,
                'price_yer_s' => $price,
                'price_yer_n' => $price,
            ]
        );
    }

    /**
     * Get paginated customer units filtered by query parameters.
     */
    public function getCustomerUnits(array $filters, int $limit = 10, int $offset = 0): array
    {
        $query = Unit::with(['prices', 'amenities', 'property.org', 'property.type', 'property.city', 'property.neighborhood'])
            ->where('status', 'active');

        if (!empty($filters['rental_type_id'])) {
            $query->whereHas('property', function ($q) use ($filters) {
                $q->where('type_id', $filters['rental_type_id']);
            });
        }

        if (!empty($filters['max_guests'])) {
            $query->where('max_guests', '>=', (int) $filters['max_guests']);
        }

        if (!empty($filters['city_id'])) {
            $query->whereHas('property', function ($q) use ($filters) {
                $q->where('city_id', $filters['city_id']);
            });
        }

        if (!empty($filters['neighborhood_id'])) {
            $query->whereHas('property', function ($q) use ($filters) {
                $q->where('neighborhood_id', $filters['neighborhood_id']);
            });
        }

        if (!empty($filters['country_id'])) {
            $query->whereHas('property.city', function ($q) use ($filters) {
                $q->where('country_id', $filters['country_id']);
            });
        }

        if (!empty($filters['amenity_ids']) && is_array($filters['amenity_ids'])) {
            $query->whereHas('amenities', function ($q) use ($filters) {
                $q->whereIn('amenity_id', $filters['amenity_ids']);
            });
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('property', function ($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $totalSize = $query->count();
        $units = $query->skip($offset)->take($limit)->get();

        return [
            'total_size' => $totalSize,
            'limit' => $limit,
            'offset' => $offset,
            'units' => $units,
        ];
    }

    /**
     * Get price range schedule for a unit within date boundaries.
     */
    public function getUnitPrices(int $unitId, string $startDate, string $endDate): Collection
    {
        return Price::where('priceable_id', $unitId)
            ->where('priceable_type', Unit::class)
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->orderBy('start_date', 'asc')
            ->get();
    }
}
