<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'Standalone microservice handling all rental property booking operations for the JAC platform. Authentication uses Bearer tokens issued by the JAC monolith (Laravel Passport).',
    title: 'JAC Rental Booking Service API',
    contact: new OA\Contact(email: 'dev@jac.com')
)]
#[OA\Server(
    url: 'L5_SWAGGER_CONST_HOST',
    description: 'Current Environment'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    description: 'Passport Bearer token issued by the JAC monolith. Obtain via the monolith /api/v1/auth/login endpoint.',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
#[OA\Tag(name: 'Properties', description: 'Browse and search rental properties')]
#[OA\Tag(name: 'Units', description: 'Individual bookable units within a property')]
#[OA\Tag(name: 'Bookings', description: 'Create and manage reservations')]
#[OA\Tag(name: 'Coupons', description: 'Apply discount coupons to bookings')]
#[OA\Tag(name: 'Favourites', description: 'User wishlist / saved properties')]
#[OA\Tag(name: 'Locations', description: 'Countries, cities and neighborhoods')]
#[OA\Tag(name: 'Webhooks', description: 'Internal callbacks — e.g. payment gateway notifications')]
abstract class Controller {}
