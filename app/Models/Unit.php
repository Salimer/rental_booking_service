<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    protected $table = 'units';

    use LocalizedModelTrait, SoftDeletes;

    protected $fillable = [
        'property_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'pricing_mode',
        'max_guests',
        'images',
        'quantity',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'max_guests' => 'integer',
        'quantity' => 'integer',
    ];

    protected $appends = [
        'price',
        'weekend_price',
        'currency',
        'discount_tag_ar',
        'discount_tag_en',
        'has_discount',
    ];

    public function getDiscountTagArAttribute()
    {
        if ($this->relationLoaded('property') && $this->property) {
            return $this->property->discount_tag_ar;
        }
        return Property::where('id', $this->property_id)->first()?->discount_tag_ar;
    }

    public function getDiscountTagEnAttribute()
    {
        if ($this->relationLoaded('property') && $this->property) {
            return $this->property->discount_tag_en;
        }
        return Property::where('id', $this->property_id)->first()?->discount_tag_en;
    }

    public function getHasDiscountAttribute()
    {
        return !empty($this->discount_tag_ar) || !empty($this->discount_tag_en);
    }

    public function toArray()
    {
        $array = parent::toArray();

        if (isset($array['images']) && is_array($array['images'])) {
            $array['images'] = $this->image_urls;
        }

        return $this->localizeArray($array);
    }

    public function getImageUrlsAttribute(): array
    {
        $imgs = $this->images;
        if (!is_array($imgs)) {
            return [];
        }
        return array_map(function ($img) {
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }
            if (str_starts_with($img, 'storage/')) {
                return asset($img);
            }
            return asset('storage/rental_unit/' . $img);
        }, $imgs);
    }

    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'unit_amenity')->withPivot('quantity');
    }

    public function getOrgPreferredCurrency(): ?string
    {
        if ($this->relationLoaded('property') && $this->property) {
            if ($this->property->relationLoaded('org') && $this->property->org) {
                return $this->property->org->preferred_currency;
            }
            return Org::where('id', $this->property->org_id)->value('preferred_currency');
        }

        return Property::join('orgs', 'properties.org_id', '=', 'orgs.id')
            ->where('properties.id', $this->property_id)
            ->value('orgs.preferred_currency');
    }

    public function getPriceAttribute($value = null)
    {
        $orgCurrency = $this->getOrgPreferredCurrency();
        if (!$orgCurrency) {
            throw new \LogicException("Unit #{$this->id} cannot resolve price: Organization preferred currency is not defined.");
        }

        $defaultPrice = $this->prices->firstWhere('price_type', 'default');
        if (!$defaultPrice) {
            return 0.00;
        }

        return (float) $defaultPrice->getValueForCurrency($orgCurrency);
    }

    public function getWeekendPriceAttribute($value = null)
    {
        $orgCurrency = $this->getOrgPreferredCurrency();
        if (!$orgCurrency) {
            return null;
        }

        $weekendPrice = $this->prices->firstWhere('price_type', 'weekend');
        if (!$weekendPrice) {
            return null;
        }

        return (float) $weekendPrice->getValueForCurrency($orgCurrency);
    }

    public function getCurrencyAttribute($value = null)
    {
        return $this->getOrgPreferredCurrency();
    }

    public function getActivePriceForDate($date, $currency = null)
    {
        $effectiveCurrency = $currency ?: $this->getOrgPreferredCurrency();
        if (!$effectiveCurrency) {
            throw new \LogicException("Unit #{$this->id} cannot resolve active price: Currency is not specified and Organization preferred currency is missing.");
        }

        $prices = $this->prices;
        $activePriceRecord = Price::resolveActivePrice($prices, Carbon::parse($date));

        if (!$activePriceRecord) {
            return 0.00;
        }

        return (float) $activePriceRecord->getValueForCurrency($effectiveCurrency);
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function availability()
    {
        return $this->hasMany(Availability::class, 'unit_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'unit_id');
    }

    public function holds()
    {
        return $this->hasMany(DateHold::class, 'unit_id');
    }

    public function manualLocks()
    {
        return $this->hasMany(ManualLock::class, 'unit_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
