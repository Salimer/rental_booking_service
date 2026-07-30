<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class FavouriteController extends Controller
{
    #[OA\Get(
        path: '/api/v1/favourites',
        summary: 'List user favourites',
        description: 'Returns all properties saved to the authenticated user favourites list.',
        security: [['bearerAuth' => []]],
        tags: ['Favourites'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of favourited properties',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/FavouriteItem')),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();
        $favourites = Favourite::with(['property', 'unit'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json(['data' => $favourites], 200);
    }

    #[OA\Post(
        path: '/api/v1/favourites',
        summary: 'Save a property to favourites',
        description: 'Adds a property to the user favourites. Idempotent.',
        security: [['bearerAuth' => []]],
        tags: ['Favourites'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['property_id'],
                properties: [
                    new OA\Property(property: 'property_id', type: 'integer', example: 12),
                    new OA\Property(property: 'unit_id', type: 'integer', example: 34),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Added to favourites'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|integer|exists:properties,id',
            'unit_id' => 'nullable|integer|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $favourite = Favourite::firstOrCreate([
            'user_id' => $user->id,
            'property_id' => $request->property_id,
            'unit_id' => $request->unit_id,
        ]);

        return response()->json([
            'message' => 'Added to favourites',
            'favourite' => $favourite,
        ], 200);
    }

    #[OA\Post(
        path: '/api/v1/favourites/toggle',
        summary: 'Toggle favourite status',
        description: 'Adds if not saved; removes if saved.',
        security: [['bearerAuth' => []]],
        tags: ['Favourites'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['property_id'],
                properties: [
                    new OA\Property(property: 'property_id', type: 'integer', example: 12),
                    new OA\Property(property: 'unit_id', type: 'integer', example: 34),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Toggle result',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Added to favourites'),
                        new OA\Property(property: 'is_favourite', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function toggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|integer|exists:properties,id',
            'unit_id' => 'nullable|integer|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        $existing = Favourite::where('user_id', $user->id)
            ->where('property_id', $request->property_id)
            ->when($request->unit_id, fn ($q) => $q->where('unit_id', $request->unit_id))
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['message' => 'Removed from favourites', 'is_favourite' => false], 200);
        }

        $favourite = Favourite::create([
            'user_id' => $user->id,
            'property_id' => $request->property_id,
            'unit_id' => $request->unit_id,
        ]);

        return response()->json(['message' => 'Added to favourites', 'is_favourite' => true, 'favourite' => $favourite], 200);
    }

    #[OA\Delete(
        path: '/api/v1/favourites/{id}',
        summary: 'Remove a favourite by ID',
        description: 'Deletes a specific favourite record.',
        security: [['bearerAuth' => []]],
        tags: ['Favourites'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Removed from favourites'),
            new OA\Response(response: 404, description: 'Favourite not found'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        $favourite = Favourite::where('user_id', $user->id)->find($id);

        if (! $favourite) {
            return response()->json(['message' => 'Favourite not found'], 404);
        }

        $favourite->delete();

        return response()->json(['message' => 'Removed from favourites'], 200);
    }
}
