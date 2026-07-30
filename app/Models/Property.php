<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';

    use LocalizedModelTrait;

    protected $fillable = [
        'org_id',
        'type_id',
        'country_id',
        'city_id',
        'neighborhood_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'latitude',
        'longitude',
        'logo',
        'images',
        'rules_ar',
        'rules_en',
        'avg_rating',
        'rating_count',
        'is_featured',
        'status',
        'slug',
        'meta_title',
        'meta_description',
        'star_rating',
    ];

    protected $casts = [
        'images' => 'array',
        'avg_rating' => 'decimal:2',
        'rating_count' => 'integer',
        'is_featured' => 'boolean',
        'star_rating' => 'integer',
    ];

    public function getCancellationPolicyAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return $settings?->cancellation_policy ?? 'moderate';
    }

    public function getCheckInTimeAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return $settings?->check_in_time ?? '14:00:00';
    }

    public function getCheckOutTimeAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return $settings?->check_out_time ?? '11:00:00';
    }

    public function getMinAdvanceBookingDaysAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return (int) ($settings?->min_advance_booking_days ?? 1);
    }

    public function getMaxAdvanceBookingDaysAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return (int) ($settings?->max_advance_booking_days ?? 365);
    }

    public function getAllowInstantBookingAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return (bool) ($settings?->allow_instant_booking ?? true);
    }

    public function getRequiresIdVerificationAttribute()
    {
        $settings = $this->settings ?? $this->org?->settings;

        return (bool) ($settings?->requires_id_verification ?? false);
    }

    public function toArray()
    {
        $array = parent::toArray();

        // Resolve effective settings: property-specific override takes priority, org default is the fallback
        $effectiveSettings = null;
        if ($this->relationLoaded('settings')) {
            $effectiveSettings = $this->settings;
        }
        if (! $effectiveSettings && $this->relationLoaded('org') && $this->org && $this->org->relationLoaded('settings')) {
            $effectiveSettings = $this->org->settings;
        }

        // Put effective settings inside the org relation block to align with the customer app version
        if ($this->relationLoaded('org') && $this->org) {
            $array['org']['settings'] = $effectiveSettings ? $effectiveSettings->toArray() : [
                'org_id' => $this->org->id,
                'cancellation_policy' => 'moderate',
                'check_in_time' => '14:00:00',
                'check_out_time' => '12:00:00',
                'min_advance_booking_days' => 1,
                'max_advance_booking_days' => 365,
                'allow_instant_booking' => true,
                'requires_id_verification' => false,
            ];
        }

        return $this->localizeArray($array);
    }

    public function settings()
    {
        return $this->hasOne(PropertySetting::class, 'property_id');
    }

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class, 'property_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'property_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'property_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
