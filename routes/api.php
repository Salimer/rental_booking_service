<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Webhook\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rental Microservice API Routes (Prefix: /api/v1)
|--------------------------------------------------------------------------
*/

// Public Webhooks
Route::post('webhooks/payment-complete', [PaymentWebhookController::class, 'handle']);

// Public Browsing Routes
Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/{id}', [PropertyController::class, 'show']);
Route::get('units/{id}', [UnitController::class, 'show']);
Route::get('countries', [LocationController::class, 'countries']);
Route::get('cities', [LocationController::class, 'cities']);
Route::get('neighborhoods', [LocationController::class, 'neighborhoods']);

// Customer Authenticated Routes (via auth.monolith middleware)
Route::middleware('auth.monolith')->group(function () {
    // Bookings
    Route::post('bookings/estimate', [BookingController::class, 'estimatePrice']);
    Route::post('bookings/initiate-payment', [BookingController::class, 'initiatePayment']);
    Route::get('bookings', [BookingController::class, 'index']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Coupons
    Route::post('coupons/apply', [CouponController::class, 'apply']);

    // Favourites
    Route::get('favourites', [FavouriteController::class, 'index']);
    Route::post('favourites', [FavouriteController::class, 'store']);
    Route::post('favourites/toggle', [FavouriteController::class, 'toggle']);
    Route::delete('favourites/{id}', [FavouriteController::class, 'destroy']);

    // Unit Pricing Management
    Route::get('units/{id}/prices', [UnitController::class, 'getPrices']);
    Route::post('units/{id}/prices', [UnitController::class, 'setPrice']);
});
