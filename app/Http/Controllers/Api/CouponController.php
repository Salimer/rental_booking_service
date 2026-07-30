<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    #[OA\Post(
        path: '/api/v1/coupons/apply',
        summary: 'Validate and apply a coupon code',
        description: 'Validates the coupon code against the booking amount and returns the discount details.',
        security: [['bearerAuth' => []]],
        tags: ['Coupons'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code', 'amount'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SAVE20'),
                    new OA\Property(property: 'amount', type: 'number', format: 'float', example: 500.00),
                    new OA\Property(property: 'org_id', type: 'integer', example: 3),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Coupon applied successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'code', type: 'string', example: 'SAVE20'),
                        new OA\Property(property: 'discount_type', type: 'string', example: 'percent'),
                        new OA\Property(property: 'discount_value', type: 'number', example: 20),
                        new OA\Property(property: 'discount_amount', type: 'number', example: 100.00),
                        new OA\Property(property: 'original_amount', type: 'number', example: 500.00),
                        new OA\Property(property: 'final_amount', type: 'number', example: 400.00),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Invalid coupon'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'org_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->couponService->applyCoupon(
                $request->input('code'),
                (float) $request->input('amount'),
                $request->input('org_id')
            );

            return response()->json($result, 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
