<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Neighborhood',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'city_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Haddah'),
        new OA\Property(property: 'status', type: 'integer', example: 1),
    ]
)]
class NeighborhoodSchema
{
    public int $id;
}
