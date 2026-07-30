<?php

namespace App\Models;

use App\Traits\LocalizedModelTrait;
use Illuminate\Database\Eloquent\Model;

class OrgSetting extends Model
{
    protected $table = 'org_settings';

    use LocalizedModelTrait;

    public const PAYOUT_DAILY = 'daily';

    public const PAYOUT_WEEKLY = 'weekly';

    public const PAYOUT_HALF_MONTHLY = 'half_monthly';

    public const PAYOUT_MONTHLY = 'monthly';

    public const PAYOUT_FREQUENCIES = [
        self::PAYOUT_DAILY,
        self::PAYOUT_WEEKLY,
        self::PAYOUT_HALF_MONTHLY,
        self::PAYOUT_MONTHLY,
    ];

    protected $fillable = [
        'org_id',
        'cancellation_policy',
        'cancellation_policy_ar',
        'cancellation_policy_en',
        'check_in_time',
        'check_out_time',
        'min_advance_booking_days',
        'max_advance_booking_days',
        'allow_instant_booking',
        'requires_id_verification',
        'free_night_enabled',
        'free_night_min_nights',
        'free_night_max_nights',
        'free_nights_count',
        'payout_frequency',
    ];

    protected $casts = [
        'min_advance_booking_days' => 'integer',
        'max_advance_booking_days' => 'integer',
        'allow_instant_booking' => 'boolean',
        'requires_id_verification' => 'boolean',
        'free_night_enabled' => 'boolean',
        'free_night_min_nights' => 'integer',
        'free_night_max_nights' => 'integer',
        'free_nights_count' => 'integer',
    ];

    public function org()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function getCancellationPolicyAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar') {
            return $this->cancellation_policy_ar ?? $this->cancellation_policy_en;
        }

        return $this->cancellation_policy_en ?? $this->cancellation_policy_ar;
    }

    public function setCancellationPolicyAttribute($value)
    {
        if ($value === 'flexible') {
            $this->attributes['cancellation_policy_en'] = 'Flexible (Free cancellation any time)';
            $this->attributes['cancellation_policy_ar'] = 'إلغاء مرن (إلغاء مجاني في أي وقت)';
        } elseif ($value === 'strict') {
            $this->attributes['cancellation_policy_en'] = 'Strict (No refunds)';
            $this->attributes['cancellation_policy_ar'] = 'إلغاء صارم (لا يوجد استرجاع)';
        } elseif ($value === 'non_refundable') {
            $this->attributes['cancellation_policy_en'] = 'Non-refundable (No refunds at all)';
            $this->attributes['cancellation_policy_ar'] = 'غير مسترد (لا يوجد استرجاع نهائياً)';
        } elseif ($value === 'moderate') {
            $this->attributes['cancellation_policy_en'] = 'Moderate (Cancel up to 24h prior)';
            $this->attributes['cancellation_policy_ar'] = 'إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)';
        } else {
            $this->attributes['cancellation_policy_en'] = $value;
            $this->attributes['cancellation_policy_ar'] = $value;
        }
    }
}
