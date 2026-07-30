<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocationService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class LocationController extends Controller
{
    protected LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    #[OA\Get(
        path: '/api/v1/countries',
        summary: 'List active countries',
        description: 'Returns all countries with active status.',
        tags: ['Locations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of countries',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Country'))
            ),
        ]
    )]
    public function countries()
    {
        return response()->json($this->locationService->getCountries());
    }

    #[OA\Get(
        path: '/api/v1/cities',
        summary: 'List cities',
        description: 'Returns active cities. Filter by country_id.',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(name: 'country_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of cities',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/City'))
            ),
        ]
    )]
    public function cities(Request $request)
    {
        return response()->json($this->locationService->getCities($request->input('country_id')));
    }

    #[OA\Get(
        path: '/api/v1/neighborhoods',
        summary: 'List neighborhoods',
        description: 'Returns active neighborhoods. Filter by city_id.',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(name: 'city_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of neighborhoods',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Neighborhood'))
            ),
        ]
    )]
    public function neighborhoods(Request $request)
    {
        return response()->json($this->locationService->getNeighborhoods($request->input('city_id')));
    }
}
