<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AmenityService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AmenityController extends Controller
{
    protected AmenityService $amenityService;

    public function __construct(AmenityService $amenityService)
    {
        $this->amenityService = $amenityService;
    }

    #[OA\Get(
        path: '/api/v1/amenities',
        summary: 'List rental amenities',
        description: 'Returns all active amenities. Filter by type_id.',
        tags: ['Amenities'],
        parameters: [
            new OA\Parameter(name: 'type_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of amenities',
            ),
        ]
    )]
    public function index(Request $request)
    {
        $typeId = $request->input('type_id') ?? $request->input('rental_type_id');
        return response()->json($this->amenityService->getAmenities($typeId ? (int)$typeId : null));
    }
}
