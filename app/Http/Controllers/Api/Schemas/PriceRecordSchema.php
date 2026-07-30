<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PriceRecord',
    description: 'A price rule for a unit',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 10),
        new OA\Property(property: 'priceable_id', type: 'integer', example: 5),
        new OA\Property(property: 'priceable_type', type: 'string', example: 'App\\Models\\Unit'),
        new OA\Property(property: 'price_type', type: 'string', example: 'default'),
        new OA\Property(property: 'start_date', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true),
        new OA\Property(property: 'price_sar', type: 'number', format: 'float', example: 350.00),
        new OA\Property(property: 'price_usd', type: 'number', format: 'float', example: 93.00),
        new OA\Property(property: 'price_yer_s', type: 'number', format: 'float', example: 87500.00),
        new OA\Property(property: 'price_yer_n', type: 'number', format: 'float', example: 90000.00),
    ]
)]
class PriceRecordSchema
{
    public int $id;
}
