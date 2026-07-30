<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use function in_array;
use function is_array;

class Price extends Model
{
    protected $table = 'prices';

    // Price types constants
    public const TYPE_DEFAULT = 'default';

    public const TYPE_WEEKEND = 'weekend';

    public const TYPE_SEASONAL = 'seasonal';

    public const TYPE_OVERRIDE = 'override';

    protected $fillable = [
        'priceable_id',
        'priceable_type',
        'price_type',
        'name',
        'start_date',
        'end_date',
        'days_of_week',
        'price_yer_n',
        'price_yer_s',
        'price_sar',
        'price_usd',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'price_yer_n' => 'decimal:2',
        'price_yer_s' => 'decimal:2',
        'price_sar' => 'decimal:2',
        'price_usd' => 'decimal:2',
    ];

    public function priceable()
    {
        return $this->morphTo();
    }

    /**
     * Scope: default prices
     */
    public function scopeDefault($query)
    {
        return $query->where('price_type', self::TYPE_DEFAULT);
    }

    /**
     * Scope: weekend prices
     */
    public function scopeWeekend($query)
    {
        return $query->where('price_type', self::TYPE_WEEKEND);
    }

    /**
     * Scope: seasonal prices
     */
    public function scopeSeasonal($query)
    {
        return $query->where('price_type', self::TYPE_SEASONAL);
    }

    /**
     * Scope: override prices
     */
    public function scopeOverride($query)
    {
        return $query->where('price_type', self::TYPE_OVERRIDE);
    }

    /**
     * Scope: filter by specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('price_type', $type);
    }

    /**
     * Get all supported price types.
     */
    public static function getPriceTypes()
    {
        return [
            self::TYPE_DEFAULT,
            self::TYPE_WEEKEND,
            self::TYPE_SEASONAL,
            self::TYPE_OVERRIDE,
        ];
    }

    /**
     * Resolves the active price record for a specific date out of a collection of prices.
     */
    public static function resolveActivePrice($prices, Carbon $date)
    {
        $dateStr = $date->format('Y-m-d');
        // Carbon dayOfWeek returns 0 (Sunday) to 6 (Saturday)
        $dayOfWeek = $date->dayOfWeek;

        // 1. Check for OVERRIDE type matching the specific date
        $overridePrice = $prices->first(function ($price) use ($dateStr) {
            return $price->price_type === self::TYPE_OVERRIDE
                && $price->start_date && $price->end_date
                && $dateStr >= $price->start_date->format('Y-m-d')
                && $dateStr <= $price->end_date->format('Y-m-d');
        });
        if ($overridePrice) {
            return $overridePrice;
        }

        // 2. Check for SEASONAL type matching the date range
        $seasonalPrice = $prices->first(function ($price) use ($dateStr) {
            return $price->price_type === self::TYPE_SEASONAL
                && $price->start_date && $price->end_date
                && $dateStr >= $price->start_date->format('Y-m-d')
                && $dateStr <= $price->end_date->format('Y-m-d');
        });
        if ($seasonalPrice) {
            return $seasonalPrice;
        }

        // 3. Check for WEEKEND type matching the day of the week
        $weekendPrice = $prices->first(function ($price) use ($dayOfWeek) {
            return $price->price_type === self::TYPE_WEEKEND
                && is_array($price->days_of_week)
                && in_array($dayOfWeek, $price->days_of_week);
        });
        if ($weekendPrice) {
            return $weekendPrice;
        }

        // 4. Fallback to DEFAULT price
        return $prices->firstWhere('price_type', self::TYPE_DEFAULT);
    }

    /**
     * Helper to return the price value for a specific resolved price record and currency.
     */
    public function getValueForCurrency($currency)
    {
        switch (strtoupper($currency)) {
            case 'YER_N':
                return $this->price_yer_n;
            case 'YER_S':
                return $this->price_yer_s;
            case 'SAR':
                return $this->price_sar;
            case 'USD':
                return $this->price_usd;
            default:
                return $this->price_sar;
        }
    }
}
