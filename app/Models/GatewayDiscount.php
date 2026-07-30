<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayDiscount extends Model
{
    protected $table = 'gateway_discounts';

    protected $fillable = [
        'gateway',
        'enabled',
        'discount_type',
        'discount_value',
        'label_ar',
        'label_en',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Get or create a discount config for a specific gateway.
     *
     * @return self
     */
    public static function getForGateway(string $gateway)
    {
        return self::firstOrCreate(
            ['gateway' => $gateway],
            [
                'enabled' => false,
                'discount_type' => 'percent',
                'discount_value' => 0.00,
                'label_ar' => null,
                'label_en' => null,
            ]
        );
    }
}
