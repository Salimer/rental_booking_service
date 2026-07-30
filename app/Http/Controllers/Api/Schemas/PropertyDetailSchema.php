<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PropertyDetail',
    description: 'Full property details including units, org, and settings',
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
        new OA\Property(property: 'org', properties: [
            new OA\Property(property: 'id', type: 'integer', example: 1),
            new OA\Property(property: 'name', type: 'string', example: 'Royal Stays'),
            new OA\Property(property: 'settings', properties: [
                new OA\Property(property: 'cancellation_policy', type: 'string', example: 'moderate'),
                new OA\Property(property: 'check_in_time', type: 'string', example: '14:00:00'),
                new OA\Property(property: 'check_out_time', type: 'string', example: '11:00:00'),
                new OA\Property(property: 'allow_instant_booking', type: 'boolean', example: true),
            ], type: 'object'),
        ], type: 'object'),
        new OA\Property(property: 'units', type: 'array', items: new OA\Items(ref: '#/components/schemas/UnitSummary')),
        new OA\Property(property: 'rules', type: 'string', nullable: true),
    ]
)]
class PropertyDetailSchema
{
    public int $id;
}
