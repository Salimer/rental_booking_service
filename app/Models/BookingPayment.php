<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    protected $table = 'booking_payments';

    protected $fillable = [
        'booking_id',
        'reference_no',
        'business_payment_method_id',
        'payment_method_key',
        'gateway',
        'gateway_transaction_ref',
        'amount',
        'currency',
        'status',
        'payment_method',
        'sender_name',
        'transfer_proof_name',
        'transfer_proof_path',
        'response_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'response_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    protected $appends = ['status_label'];

    public function getStatusLabelAttribute(): string
    {
        return PaymentStatus::tryFrom($this->status ?? '')?->label() ?? ucfirst(str_replace('_', ' ', $this->status ?? ''));
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function businessPaymentMethod()
    {
        return $this->belongsTo(BusinessPaymentMethod::class, 'business_payment_method_id');
    }
}
