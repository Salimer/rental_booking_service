<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UnitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class UnitController extends Controller
{
    protected UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 10);
        $offset = (int) $request->query('offset', 0);

        $filters = [
            'rental_type_id' => $request->query('rental_type_id'),
            'max_guests' => $request->query('max_guests'),
            'city_id' => $request->query('rental_city_id') ?? $request->query('city_id'),
            'neighborhood_id' => $request->query('rental_neighborhood_id') ?? $request->query('neighborhood_id'),
            'country_id' => $request->query('rental_country_id') ?? $request->query('country_id'),
            'amenity_ids' => $request->query('amenity_ids'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'search' => $request->query('search'),
        ];

        $result = $this->unitService->getCustomerUnits($filters, $limit, $offset);

        return response()->json($result, 200);
    }

    #[OA\Get(
        path: '/api/v1/units/{id}',
        summary: 'Get unit details',
        description: 'Returns full details for a specific unit including amenities and price rules.',
        tags: ['Units'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Unit details',
                content: new OA\JsonContent(ref: '#/components/schemas/UnitDetail')
            ),
            new OA\Response(response: 404, description: 'Unit not found'),
        ]
    )]
    public function show($id)
    {
        try {
            $unit = $this->unitService->getUnitDetails((int) $id);

            return response()->json($unit, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unit not found'], 404);
        }
    }

    public function checkAvailability($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'mode' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            /** @var \App\Services\BookingService $bookingService */
            $bookingService = app(\App\Services\BookingService::class);
            $result = $bookingService->checkAvailability(
                (int) $id,
                $request->input('check_in_date'),
                $request->input('check_out_date'),
                $request->input('mode')
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/v1/units/{id}/prices',
        summary: 'Get custom price schedule for a unit',
        description: 'Returns override/seasonal price records for a given unit within a date range.',
        security: [['bearerAuth' => []]],
        tags: ['Units'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'start_date', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date', example: '2026-09-01')),
            new OA\Parameter(name: 'end_date', in: 'query', required: true, schema: new OA\Schema(type: 'string', format: 'date', example: '2026-09-30')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of price records in the requested period',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/PriceRecord'))
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function getPrices($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $prices = $this->unitService->getUnitPrices(
                (int) $id,
                $request->input('start_date'),
                $request->input('end_date')
            );

            return response()->json($prices, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/v1/units/{id}/prices',
        summary: 'Set a custom price for a unit on a specific date',
        description: 'Creates or updates an override price for a specific date. Used by org managers/staff.',
        security: [['bearerAuth' => []]],
        tags: ['Units'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['date', 'price'],
                properties: [
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-12-25'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 350.00),
                    new OA\Property(property: 'currency', type: 'string', example: 'SAR', description: 'SAR | USD | YER_S | YER_N'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Price set successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Price updated successfully'),
                        new OA\Property(property: 'price', ref: '#/components/schemas/PriceRecord'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function setPrice($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'price' => 'required|numeric|min:0',
            'currency' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $price = $this->unitService->setCustomPrice(
                (int) $id,
                $request->input('date'),
                (float) $request->input('price'),
                $request->input('currency', 'SAR')
            );

            return response()->json([
                'message' => 'Price updated successfully',
                'price' => $price,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
