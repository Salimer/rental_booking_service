<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'City',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'country_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Sanaa'),
        new OA\Property(property: 'status', type: 'integer', example: 1),
    ]
)]
class CitySchema
{
    public int $id;
}
