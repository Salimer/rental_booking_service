<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PropertyService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PropertyController extends Controller
{
    protected PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    #[OA\Get(
        path: '/api/v1/properties',
        summary: 'Browse active rental properties',
        description: 'Returns a paginated list of active properties. Supports filtering by city, neighborhood, type, and star rating. No authentication required.',
        tags: ['Properties'],
        parameters: [
            new OA\Parameter(name: 'city_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Filter by city'),
            new OA\Parameter(name: 'neighborhood_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Filter by neighborhood'),
            new OA\Parameter(name: 'type_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: 'Filter by property type'),
            new OA\Parameter(name: 'star_rating', in: 'query', required: false, schema: new OA\Schema(type: 'integer', maximum: 5, minimum: 1), description: 'Minimum star rating'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of properties',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'total_size', type: 'integer', example: 42),
                        new OA\Property(property: 'limit', type: 'integer', example: 15),
                        new OA\Property(property: 'offset', type: 'integer', example: 1),
                        new OA\Property(property: 'properties', type: 'array', items: new OA\Items(ref: '#/components/schemas/PropertySummary')),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request)
    {
        try {
            $paginator = $this->propertyService->getCustomerProperties($request->all());

            return response()->json([
                'total_size' => $paginator->total(),
                'limit' => $paginator->perPage(),
                'offset' => $paginator->currentPage(),
                'properties' => $paginator->items(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/v1/properties/{id}',
        summary: 'Get property details',
        description: 'Returns full details for a single property including units, reviews, amenities, and settings.',
        tags: ['Properties'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Property details',
                content: new OA\JsonContent(ref: '#/components/schemas/PropertyDetail')
            ),
            new OA\Response(response: 404, description: 'Property not found'),
        ]
    )]
    public function show($id)
    {
        try {
            $property = $this->propertyService->getPropertyDetails((int) $id);

            return response()->json($property, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Property not found'], 404);
        }
    }
}
