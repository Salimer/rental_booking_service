<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTransaction extends Model
{
    protected $table = 'booking_transactions';

    protected $fillable = [
        'booking_id',
        'subtotal',
        'discount_amount',
        'free_night_discount_amount',
        'gateway_discount_amount',
        'discount_breakdown',
        'coupon_code',
        'tax_amount',
        'total_amount',
        'admin_commission',
        'org_amount',
        'currency',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'free_night_discount_amount' => 'decimal:2',
        'gateway_discount_amount' => 'decimal:2',
        'discount_breakdown' => 'array',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'admin_commission' => 'decimal:2',
        'org_amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
