<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class PaymentWebhookController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    #[OA\Post(
        path: '/api/v1/webhooks/payment-complete',
        summary: 'Payment gateway completion webhook',
        description: 'Called by monolith payment system after payment succeeds or fails.',
        tags: ['Webhooks'],
        parameters: [
            new OA\Parameter(name: 'X-Webhook-Signature', in: 'header', required: false, schema: new OA\Schema(type: 'string'), description: 'HMAC-SHA256 of payload'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['hold_token', 'payment_reference', 'status'],
                properties: [
                    new OA\Property(property: 'hold_token', type: 'string', example: 'HOLD-ABCDE12345XY'),
                    new OA\Property(property: 'payment_reference', type: 'string', example: 'PAY-9988776655'),
                    new OA\Property(property: 'status', type: 'string', example: 'paid'),
                    new OA\Property(property: 'gateway', type: 'string', example: 'kuraimi'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Webhook processed — booking created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'booking_id', type: 'integer', example: 88),
                        new OA\Property(property: 'reference_no', type: 'string', example: 'BK-ABCD1234'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Hold not found or payment not paid'),
            new OA\Response(response: 401, description: 'Invalid HMAC signature'),
            new OA\Response(response: 500, description: 'Internal processing error'),
        ]
    )]
    public function handle(Request $request)
    {
        $secret = config('services.monolith.webhook_secret', env('WEBHOOK_SECRET', 'jac_rental_webhook_secret_2026'));
        $signature = $request->header('X-Webhook-Signature');

        if ($signature) {
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            if (! hash_equals($expected, $signature)) {
                return response()->json(['message' => 'Invalid signature'], 401);
            }
        }

        try {
            $result = $this->bookingService->handlePaymentWebhook($request->all());

            if (! $result['success']) {
                return response()->json(['message' => $result['message']], 400);
            }

            return response()->json($result, 200);
        } catch (\Exception $e) {
            Log::error('Payment webhook failed: '.$e->getMessage());

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }
}
