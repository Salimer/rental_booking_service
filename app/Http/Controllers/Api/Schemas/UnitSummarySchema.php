<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UnitSummary',
    description: 'Compact unit representation in property listings',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 5),
        new OA\Property(property: 'name', type: 'string', example: 'Chalet 101'),
        new OA\Property(property: 'max_guests', type: 'integer', example: 6),
        new OA\Property(property: 'quantity', type: 'integer', example: 1),
        new OA\Property(property: 'status', type: 'string', example: 'active'),
        new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string')),
    ]
)]
class UnitSummarySchema
{
    public int $id;
}
