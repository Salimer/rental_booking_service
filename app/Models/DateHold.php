<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DateHold extends Model
{
    protected $table = 'date_holds';

    protected $fillable = [
        'unit_id',
        'user_id',
        'booking_id',
        'hold_token',
        'check_in_date',
        'check_out_date',
        'nights_count',
        'guests_count',
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_note',
        'total_amount',
        'currency',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'expires_at' => 'datetime',
        'nights_count' => 'integer',
        'guests_count' => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
