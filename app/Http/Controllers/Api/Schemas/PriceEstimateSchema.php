<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PriceEstimate',
    description: 'Price breakdown and total for booking',
    properties: [
        new OA\Property(property: 'unit_id', type: 'integer', example: 5),
        new OA\Property(property: 'check_in_date', type: 'string', format: 'date', example: '2026-09-01'),
        new OA\Property(property: 'check_out_date', type: 'string', format: 'date', example: '2026-09-04'),
        new OA\Property(property: 'nights_count', type: 'integer', example: 3),
        new OA\Property(property: 'unit_price', type: 'number', format: 'float', example: 150.00),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float', example: 450.00),
        new OA\Property(property: 'currency', type: 'string', example: 'SAR'),
        new OA\Property(property: 'nightly_breakdown', type: 'array', items: new OA\Items(properties: [
            new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-09-01'),
            new OA\Property(property: 'price', type: 'number', format: 'float', example: 150.00),
        ], type: 'object')),
    ]
)]
class PriceEstimateSchema
{
    public int $unit_id;
}
