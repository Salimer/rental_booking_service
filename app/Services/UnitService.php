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
