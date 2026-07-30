<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case PENDING_REVIEW = 'pending_review';
    case UNDER_REVIEW = 'under_review';
    case PAID = 'paid';
    case REJECTED = 'rejected';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case REFUND_PENDING = 'refund_pending';
    case PARTIALLY_PAID = 'partially_paid';

    public function isVerified(): bool
    {
        return $this === self::PAID;
    }

    public function isPendingValidation(): bool
    {
        return match ($this) {
            self::PENDING,
            self::PENDING_REVIEW,
            self::UNDER_REVIEW => true,
            default => false,
        };
    }

    public function label(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();
        $isAr = str_starts_with((string) $lang, 'ar');

        return match ($this) {
            self::UNPAID => $isAr ? 'غير مدفوع' : 'Unpaid',
            self::PENDING,
            self::PENDING_REVIEW,
            self::UNDER_REVIEW => $isAr ? 'قيد المراجعة' : 'Under Review',
            self::PAID => $isAr ? 'تم السداد' : 'Paid',
            self::REJECTED => $isAr ? 'مرفوض' : 'Rejected',
            self::FAILED => $isAr ? 'فشل الدفع' : 'Failed',
            self::REFUNDED => $isAr ? 'مسترجع' : 'Refunded',
            self::REFUND_PENDING => $isAr ? 'طلب استرجاع' : 'Refund Pending',
            self::PARTIALLY_PAID => $isAr ? 'مدفوع جزئياً' : 'Partially Paid',
        };
    }
}
