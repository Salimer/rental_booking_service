<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_NO_SHOW = 'no_show';

    public const PAYMENT_STATUS_UNPAID = 'unpaid';

    public const PAYMENT_STATUS_PAID = 'paid';

    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const PAYMENT_STATUS_FAILED = 'failed';

    protected $fillable = [
        'reference_no',
        'user_id',
        'property_id',
        'unit_id',
        'org_id',
        'check_in_date',
        'check_out_date',
        'nights_count',
        'guests_count',
        'guest_name',
        'guest_phone',
        'guest_email',
        'unit_price',
        'currency',
        'payment_status',
        'status',
        'cancellation_reason',
        'cancelled_by',
        'cancelled_by_user_id',
        'guest_note',
        'internal_note',
        'submitted_at',
        'confirmed_at',
        'cancelled_at',
        'expires_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'nights_count' => 'integer',
        'guests_count' => 'integer',
        'unit_price' => 'decimal:2',
        'submitted_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $appends = ['status_label', 'payment_status_label', 'total_price', 'booking_code', 'total_nights'];

    public function getTotalPriceAttribute(): float
    {
        return (float) (($this->unit_price ?? 0) * ($this->nights_count ?? 1));
    }

    public function getBookingCodeAttribute(): string
    {
        return $this->reference_no ?? (string) $this->id;
    }

    public function getTotalNightsAttribute(): int
    {
        return (int) ($this->nights_count ?? 1);
    }

    public function getStatusLabelAttribute(): string
    {
        return BookingStatus::tryFrom($this->status ?? '')?->label() ?? ucfirst(str_replace('_', ' ', $this->status ?? ''));
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return PaymentStatus::tryFrom($this->payment_status ?? '')?->label() ?? ucfirst(str_replace('_', ' ', $this->payment_status ?? ''));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function transaction()
    {
        return $this->hasOne(BookingTransaction::class, 'booking_id');
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class, 'booking_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(BookingStatusLog::class, 'booking_id');
    }

    public function hold()
    {
        return $this->hasOne(DateHold::class, 'booking_id');
    }

    public function confirmation()
    {
        return $this->hasOne(BookingConfirmation::class, 'booking_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }
}
