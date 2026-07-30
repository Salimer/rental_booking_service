<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PropertySummary',
    description: 'Compact property representation returned in list views',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Sea View Villa'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'city_id', type: 'integer', example: 3),
        new OA\Property(property: 'type_id', type: 'integer', example: 1),
        new OA\Property(property: 'avg_rating', type: 'number', format: 'float', example: 4.5),
        new OA\Property(property: 'rating_count', type: 'integer', example: 12),
        new OA\Property(property: 'is_featured', type: 'boolean', example: true),
        new OA\Property(property: 'star_rating', type: 'integer', example: 4),
        new OA\Property(property: 'status', type: 'string', example: 'active'),
        new OA\Property(property: 'logo', type: 'string', nullable: true, example: 'properties/villa1.jpg'),
        new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string')),
    ]
)]
class PropertySummarySchema
{
    public int $id;
}
