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
        return Unit::with(['property.org.settings', 'property.type', 'property.city', 'property.neighborhood', 'property.settings', 'amenities', 'prices'])->findOrFail($id);
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
            ->where('status', 'active')
            ->whereHas('property', function ($q) {
                $q->where('status', 'active');
            });

        $typeId = $filters['type_id'] ?? $filters['rental_type_id'] ?? null;
        if (!empty($typeId)) {
            $query->whereHas('property', function ($q) use ($typeId) {
                $q->where('type_id', $typeId);
            });
        }

        if (!empty($filters['max_guests'])) {
            $query->where('max_guests', '>=', (int) $filters['max_guests']);
        }

        if (!empty($filters['city_id'])) {
            $cityVal = $filters['city_id'];
            if (is_numeric($cityVal)) {
                $query->whereHas('property', function ($q) use ($cityVal) {
                    $q->where('city_id', (int) $cityVal);
                });
            } else {
                $query->whereHas('property.city', function ($q) use ($cityVal) {
                    $q->where('name_en', 'like', "%{$cityVal}%")
                      ->orWhere('name_ar', 'like', "%{$cityVal}%");
                });
            }
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

        if (!empty($filters['min_rating'])) {
            $minRating = (float) $filters['min_rating'];
            $query->whereHas('property', function ($q) use ($minRating) {
                $q->where('avg_rating', '>=', $minRating);
            });
        }

        if (!empty($filters['has_discount'])) {
            $hasDiscount = (bool) $filters['has_discount'];
            if ($hasDiscount) {
                $today = now()->toDateString();
                $query->whereHas('property', function ($q) use ($today) {
                    $q->where(function ($sub) use ($today) {
                        $sub->whereHas('coupons', function ($cQ) use ($today) {
                            $cQ->where('status', 1)
                               ->where('start_date', '<=', $today)
                               ->where('expire_date', '>=', $today);
                        })->orWhereHas('org.settings', function ($sQ) {
                            $sQ->where('free_night_enabled', 1);
                        });
                    });
                });
            }
        }

        if (!empty($filters['min_price'])) {
            $minPrice = (float) $filters['min_price'];
            $query->whereHas('prices', function ($priceQ) use ($minPrice) {
                $priceQ->where('price_type', 'default')
                       ->where(function ($sub) use ($minPrice) {
                           $sub->where('price_sar', '>=', $minPrice)
                               ->orWhere('price_yer_s', '>=', $minPrice)
                               ->orWhere('price_yer_n', '>=', $minPrice)
                               ->orWhere('price_usd', '>=', $minPrice);
                       });
            });
        }

        if (!empty($filters['max_price'])) {
            $maxPrice = (float) $filters['max_price'];
            $query->whereHas('prices', function ($priceQ) use ($maxPrice) {
                $priceQ->where('price_type', 'default')
                       ->where(function ($sub) use ($maxPrice) {
                           $sub->where('price_sar', '<=', $maxPrice)
                               ->orWhere('price_yer_s', '<=', $maxPrice)
                               ->orWhere('price_yer_n', '<=', $maxPrice)
                               ->orWhere('price_usd', '<=', $maxPrice);
                       });
            });
        }

        if (!empty($filters['amenity_ids']) && is_array($filters['amenity_ids'])) {
            $amenityIds = $filters['amenity_ids'];
            $amenityQuantities = is_array($filters['amenity_quantities'] ?? null) ? $filters['amenity_quantities'] : [];
            $mode = $filters['amenity_filter_mode'] ?? 'any';

            if ($mode === 'all') {
                foreach ($amenityIds as $index => $amenityId) {
                    $minQty = isset($amenityQuantities[$index]) ? intval($amenityQuantities[$index]) : 1;
                    $query->whereHas('amenities', function ($q) use ($amenityId, $minQty) {
                        $q->where('amenity_id', $amenityId)
                          ->where('unit_amenity.quantity', '>=', $minQty);
                    });
                }
            } else {
                $query->whereHas('amenities', function ($q) use ($amenityIds, $amenityQuantities) {
                    $q->where(function ($q2) use ($amenityIds, $amenityQuantities) {
                        foreach ($amenityIds as $index => $amenityId) {
                            $minQty = isset($amenityQuantities[$index]) ? intval($amenityQuantities[$index]) : 1;
                            $q2->orWhere(function ($q3) use ($amenityId, $minQty) {
                                $q3->where('amenity_id', $amenityId)
                                   ->where('unit_amenity.quantity', '>=', $minQty);
                            });
                        }
                    });
                });
            }
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhereHas('property', function ($pq) use ($search) {
                      $pq->where('name_ar', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['check_in_date']) && !empty($filters['check_out_date'])) {
            $checkIn = $filters['check_in_date'];
            $checkOut = $filters['check_out_date'];
            $query->whereDoesntHave('bookings', function ($bQ) use ($checkIn, $checkOut) {
                $bQ->whereIn('status', ['confirmed', 'checked_in'])
                   ->where('check_in_date', '<', $checkOut)
                   ->where('check_out_date', '>', $checkIn);
            })->whereDoesntHave('availability', function ($aQ) use ($checkIn, $checkOut) {
                $aQ->where('is_blocked', true)
                   ->where('date', '>=', $checkIn)
                   ->where('date', '<', $checkOut);
            })->whereDoesntHave('manualLocks', function ($mL) use ($checkIn, $checkOut) {
                $mL->where('start_date', '<', $checkOut)
                   ->where('end_date', '>', $checkIn);
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
