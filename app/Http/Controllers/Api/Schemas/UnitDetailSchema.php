<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UnitDetail',
    description: 'Full unit details with amenities and price rules',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 5),
        new OA\Property(property: 'property_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Chalet 101'),
        new OA\Property(property: 'pricing_mode', type: 'string', example: 'nightly'),
        new OA\Property(property: 'max_guests', type: 'integer', example: 6),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'amenities', type: 'array', items: new OA\Items(properties: [
            new OA\Property(property: 'id', type: 'integer', example: 2),
            new OA\Property(property: 'name', type: 'string', example: 'WiFi'),
        ], type: 'object')),
        new OA\Property(property: 'property', ref: '#/components/schemas/PropertySummary'),
    ]
)]
class UnitDetailSchema
{
    public int $id;
}
