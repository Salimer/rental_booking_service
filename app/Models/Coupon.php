<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'org_id',
        'property_id',
        'code',
        'title_ar',
        'title_en',
        'discount_type',
        'discount',
        'min_booking_amount',
        'max_discount_amount',
        'start_date',
        'expire_date',
        'max_uses',
        'limit_per_user',
        'used_count',
        'status',
        'created_by',
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'min_booking_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'start_date' => 'date',
        'expire_date' => 'date',
        'max_uses' => 'integer',
        'limit_per_user' => 'integer',
        'used_count' => 'integer',
        'status' => 'boolean',
    ];

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }
}
