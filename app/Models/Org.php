<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $table = 'orgs';

    use LocalizedModelTrait;

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'vendor_id',
        'dashboard_user_id',
        'module_id',
        'zone_id',
        'city',
        'address_ar',
        'address_en',
        'latitude',
        'longitude',
        'contact_phone',
        'contact_email',
        'logo',
        'cover_photo',
        'preferred_currency',
        'status',
        'commission',
        'notes',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
    ];

    public function dashboardUser()
    {
        return $this->belongsTo(DashboardUser::class, 'dashboard_user_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function settings()
    {
        return $this->hasOne(OrgSetting::class, 'org_id');
    }

    public function staff()
    {
        return $this->hasMany(OrgStaff::class, 'org_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'org_id');
    }

    public function units()
    {
        return $this->hasManyThrough(Unit::class, Property::class, 'org_id', 'property_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'org_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'org_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
