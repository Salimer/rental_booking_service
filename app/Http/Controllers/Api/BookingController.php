<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    #[OA\Get(
        path: '/api/v1/bookings',
        summary: 'List user bookings',
        description: 'Returns all bookings belonging to the authenticated user, newest first.',
        security: [['bearerAuth' => []]],
        tags: ['Bookings'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of bookings',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/BookingSummary'))
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $bookings = Booking::with(['property', 'unit', 'transaction'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($bookings, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/v1/bookings/{id}',
        summary: 'Get booking details',
        description: 'Returns full details for a specific booking owned by the authenticated user.',
        security: [['bearerAuth' => []]],
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking details',
                content: new OA\JsonContent(ref: '#/components/schemas/BookingDetail')
            ),
            new OA\Response(response: 404, description: 'Booking not found'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function show($id, Request $request)
    {
        try {
            $user = $request->user();
            $booking = Booking::with(['property', 'unit', 'transaction', 'payments', 'statusLogs', 'confirmation', 'review'])
                ->where('user_id', $user->id)
                ->find($id);

            if (! $booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            return response()->json($booking, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/v1/bookings/estimate',
        summary: 'Estimate booking price',
        description: 'Calculates the total price for a unit over selected dates including per-night breakdown. Does NOT create a booking or hold. Use this when the user is browsing dates before committing.',
        security: [['bearerAuth' => []]],
        tags: ['Bookings'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['unit_id', 'check_in_date', 'check_out_date'],
                properties: [
                    new OA\Property(property: 'unit_id', type: 'integer', example: 5),
                    new OA\Property(property: 'check_in_date', type: 'string', format: 'date', example: '2026-09-01'),
                    new OA\Property(property: 'check_out_date', type: 'string', format: 'date', example: '2026-09-04'),
                    new OA\Property(property: 'guests_count', type: 'integer', example: 2),
                    new OA\Property(property: 'currency', type: 'string', example: 'SAR', description: 'SAR | USD | YER_S | YER_N'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Price estimate breakdown',
                content: new OA\JsonContent(ref: '#/components/schemas/PriceEstimate')
            ),
            new OA\Response(response: 400, description: 'Invalid date range'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function estimatePrice(Request $request)
    {
        $data = $request->all();
        if (empty($data['unit_id']) && !empty($data['rental_unit_id'])) {
            $data['unit_id'] = $data['rental_unit_id'];
        }
        if (!empty($data['check_in_date']) && strlen($data['check_in_date']) >= 10) {
            $data['check_in_date'] = substr($data['check_in_date'], 0, 10);
        }
        if (!empty($data['check_out_date']) && strlen($data['check_out_date']) >= 10) {
            $data['check_out_date'] = substr($data['check_out_date'], 0, 10);
        }

        $validator = Validator::make($data, [
            'unit_id' => 'required|integer|exists:units,id',
            'check_in_date' => 'required|date_format:Y-m-d',
            'check_out_date' => 'required|date_format:Y-m-d|after:check_in_date',
            'guests_count' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $estimate = $this->bookingService->estimatePrice($data);

            return response()->json($estimate, 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/v1/bookings/initiate-payment',
        summary: 'Initiate the payment flow',
        description: 'Creates a 15-minute date hold on the unit and returns a payment URL. The booking record is only created AFTER the payment gateway confirms payment via the webhook. Call this when the user taps "Pay Now".',
        security: [['bearerAuth' => []]],
        tags: ['Bookings'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['unit_id', 'check_in_date', 'check_out_date'],
                properties: [
                    new OA\Property(property: 'unit_id', type: 'integer', example: 5),
                    new OA\Property(property: 'check_in_date', type: 'string', format: 'date', example: '2026-09-01'),
                    new OA\Property(property: 'check_out_date', type: 'string', format: 'date', example: '2026-09-04'),
                    new OA\Property(property: 'guests_count', type: 'integer', example: 2),
                    new OA\Property(property: 'currency', type: 'string', example: 'SAR'),
                    new OA\Property(property: 'guest_name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'guest_phone', type: 'string', example: '+966501234567'),
                    new OA\Property(property: 'guest_email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'guest_note', type: 'string', example: 'Late check-in please'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment initiation details',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'hold_token', type: 'string', example: 'HOLD-ABCDE12345XY'),
                        new OA\Property(property: 'expires_at', type: 'string', format: 'date-time'),
                        new OA\Property(property: 'total_amount', type: 'number', example: 450.00),
                        new OA\Property(property: 'currency', type: 'string', example: 'SAR'),
                        new OA\Property(property: 'payment_url', type: 'string', example: 'https://api.example.com/api/v1/payments/initiate?hold_token=HOLD-ABC&amount=450'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function initiatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|integer|exists:units,id',
            'check_in_date' => 'required|date_format:Y-m-d',
            'check_out_date' => 'required|date_format:Y-m-d|after:check_in_date',
            'guests_count' => 'nullable|integer|min:1',
            'guest_name' => 'nullable|string',
            'guest_phone' => 'nullable|string',
            'guest_email' => 'nullable|email',
            'guest_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $response = $this->bookingService->initiatePayment($request->user(), $request->all());

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/v1/bookings/{id}/cancel',
        summary: 'Cancel a booking',
        description: 'Cancels a confirmed booking owned by the authenticated user. Push notification is sent to the user via the monolith bridge.',
        security: [['bearerAuth' => []]],
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'reason', type: 'string', example: 'Change of plans'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking cancelled',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Booking cancelled successfully'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Cannot cancel booking in current state'),
            new OA\Response(response: 404, description: 'Booking not found'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function cancel($id, Request $request)
    {
        try {
            $user = $request->user();
            $booking = Booking::where('user_id', $user->id)->find($id);

            if (! $booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            if (in_array($booking->status, ['completed', 'cancelled'])) {
                return response()->json(['message' => 'Booking cannot be cancelled in its current state'], 400);
            }

            $this->bookingService->cancelBooking($booking, $request->input('reason'));

            return response()->json(['message' => 'Booking cancelled successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'nullable|integer',
            'rental_unit_id' => 'nullable|integer',
            'check_in_date' => 'required|date_format:Y-m-d',
            'check_out_date' => 'required|date_format:Y-m-d|after:check_in_date',
            'guests_count' => 'nullable|integer|min:1',
            'guest_name' => 'nullable|string',
            'guest_phone' => 'nullable|string',
            'guest_email' => 'nullable|email',
            'guest_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            if (empty($data['unit_id']) && !empty($data['rental_unit_id'])) {
                $data['unit_id'] = $data['rental_unit_id'];
            }

            $res = $this->bookingService->createBooking($request->user(), $data);

            return response()->json($res, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function processPayment($id, Request $request)
    {
        try {
            $user = $request->user();
            $booking = Booking::where('user_id', $user->id)->find($id);

            if (! $booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            $res = $this->bookingService->processPayment($booking, $request->all());

            return response()->json($res, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
