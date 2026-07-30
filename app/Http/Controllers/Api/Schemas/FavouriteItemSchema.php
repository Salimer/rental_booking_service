<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FavouriteItem',
    description: 'A saved favourite entry',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 10),
        new OA\Property(property: 'property_id', type: 'integer', example: 1),
        new OA\Property(property: 'unit_id', type: 'integer', nullable: true, example: 5),
        new OA\Property(property: 'property', ref: '#/components/schemas/PropertySummary'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class FavouriteItemSchema
{
    public int $id;
}
