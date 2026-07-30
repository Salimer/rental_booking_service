<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingConfirmation extends Model
{
    protected $table = 'booking_confirmations';

    protected $fillable = [
        'booking_id',
        'confirmation_number',
        'qr_payload',
        'voucher_file_path',
        'status',
        'issued_at',
        'issued_by',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
