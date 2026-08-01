<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TypeService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TypeController extends Controller
{
    protected TypeService $typeService;

    public function __construct(TypeService $typeService)
    {
        $this->typeService = $typeService;
    }

    #[OA\Get(
        path: '/api/v1/types',
        summary: 'List property types',
        description: 'Returns all active rental property types.',
        tags: ['Types'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of property types',
            ),
        ]
    )]
    public function index()
    {
        return response()->json($this->typeService->getActiveTypes());
    }
}
