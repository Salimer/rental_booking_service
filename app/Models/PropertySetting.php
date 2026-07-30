<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class PropertySetting extends Model
{
    use LocalizedModelTrait;

    protected $table = 'property_settings';

    protected $fillable = [
        'property_id',
        'cancellation_policy',
        'cancellation_policy_ar',
        'cancellation_policy_en',
        'check_in_time',
        'check_out_time',
        'min_advance_booking_days',
        'max_advance_booking_days',
        'allow_instant_booking',
        'auto_approve_bookings',
        'requires_id_verification',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
