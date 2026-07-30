<?php

namespace App\Http\Controllers\Api\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookingDetail',
    description: 'Full booking details',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 88),
        new OA\Property(property: 'reference_no', type: 'string', example: 'BK-ABCD1234'),
        new OA\Property(property: 'status', type: 'string', example: 'confirmed'),
        new OA\Property(property: 'payment_status', type: 'string', example: 'paid'),
        new OA\Property(property: 'check_in_date', type: 'string', format: 'date', example: '2026-09-01'),
        new OA\Property(property: 'check_out_date', type: 'string', format: 'date', example: '2026-09-04'),
        new OA\Property(property: 'nights_count', type: 'integer', example: 3),
        new OA\Property(property: 'guests_count', type: 'integer', example: 2),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float', example: 450.00),
        new OA\Property(property: 'currency', type: 'string', example: 'SAR'),
        new OA\Property(property: 'guest_name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'guest_phone', type: 'string', example: '+966501234567'),
        new OA\Property(property: 'guest_email', type: 'string', example: 'john@example.com'),
        new OA\Property(property: 'guest_note', type: 'string', nullable: true),
        new OA\Property(property: 'property', ref: '#/components/schemas/PropertySummary'),
        new OA\Property(property: 'unit', ref: '#/components/schemas/UnitSummary'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class BookingDetailSchema
{
    public int $id;
}
