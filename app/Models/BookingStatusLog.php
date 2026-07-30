<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatusLog extends Model
{
    protected $table = 'booking_status_logs';

    protected $fillable = [
        'booking_id',
        'old_status',
        'new_status',
        'changed_by_id',
        'changed_by_type',
        'changed_by_role',
        'comment',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
