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

    public function getPriceAttribute($value = null)
    {
        if (array_key_exists('price', $this->attributes)) {
            return $this->attributes['price'];
        }
        $defaultPrice = $this->prices->firstWhere('price_type', 'default');

        return $defaultPrice ? $defaultPrice->price_sar : 0.00;
    }

    public function getWeekendPriceAttribute($value = null)
    {
        if (array_key_exists('weekend_price', $this->attributes)) {
            return $this->attributes['weekend_price'];
        }
        $weekendPrice = $this->prices->firstWhere('price_type', 'weekend');

        return $weekendPrice ? $weekendPrice->price_sar : null;
    }

    public function getActivePriceForDate($date, $currency)
    {
        $prices = $this->prices;
        $activePriceRecord = Price::resolveActivePrice($prices, Carbon::parse($date));

        return $activePriceRecord ? $activePriceRecord->getValueForCurrency($currency) : 0.00;
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
