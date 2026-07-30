<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case SUBMITTED = 'submitted';
    case PROCESSING = 'processing';
    case AWAITING_PAYMENT = 'awaiting_payment';
    case PAYMENT_UNDER_REVIEW = 'payment_under_review';
    case CONFIRMED = 'confirmed';
    case TICKET_ISSUED = 'ticket_issued';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function isActive(): bool
    {
        return match ($this) {
            self::PENDING,
            self::SUBMITTED,
            self::PROCESSING,
            self::AWAITING_PAYMENT,
            self::PAYMENT_UNDER_REVIEW,
            self::CONFIRMED,
            self::TICKET_ISSUED => true,
            default => false,
        };
    }

    public function isPast(): bool
    {
        return match ($this) {
            self::COMPLETED,
            self::CANCELLED,
            self::REJECTED,
            self::EXPIRED => true,
            default => false,
        };
    }

    public function label(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();
        $isAr = str_starts_with((string) $lang, 'ar');

        return match ($this) {
            self::PENDING => $isAr ? 'قيد الانتظار' : 'Pending',
            self::SUBMITTED => $isAr ? 'تم التقديم' : 'Submitted',
            self::PROCESSING => $isAr ? 'قيد المعالجة' : 'Processing',
            self::AWAITING_PAYMENT => $isAr ? 'بانتظار الدفع' : 'Awaiting Payment',
            self::PAYMENT_UNDER_REVIEW => $isAr ? 'الدفع قيد المراجعة' : 'Payment Under Review',
            self::CONFIRMED => $isAr ? 'مؤكد' : 'Confirmed',
            self::TICKET_ISSUED => $isAr ? 'تم إصدار التذكرة' : 'Ticket Issued',
            self::COMPLETED => $isAr ? 'مكتمل' : 'Completed',
            self::CANCELLED => $isAr ? 'ملغى' : 'Cancelled',
            self::REJECTED => $isAr ? 'مرفوض' : 'Rejected',
            self::EXPIRED => $isAr ? 'منتهي الصلاحية' : 'Expired',
        };
    }
}
