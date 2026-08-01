<?php

use App\Http\Controllers\Dashboard\DashboardAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DashboardSsoController;
use App\Http\Controllers\Web\TestDashboardController;
use App\Http\Middleware\VerifyDashboardSession;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/sso', [DashboardSsoController::class, 'handleSso'])->name('sso');
    Route::get('/login', [DashboardAuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [DashboardAuthController::class, 'login']);
    Route::post('/logout', [DashboardAuthController::class, 'logout'])->name('logout');

    Route::middleware([VerifyDashboardSession::class])->group(function () {
        Route::get('/home', [DashboardController::class, 'home'])->name('home');
        Route::get('/orgs', [DashboardController::class, 'orgsList'])->name('orgs.list');
        Route::get('/orgs/create', [DashboardController::class, 'orgCreate'])->name('orgs.create');
        Route::post('/orgs/store', [DashboardController::class, 'orgStore'])->name('orgs.store');
        Route::get('/orgs/{id}', [DashboardController::class, 'orgShow'])->name('orgs.show');
        Route::post('/orgs/{id}/update', [DashboardController::class, 'orgUpdate'])->name('orgs.update');

        Route::get('/orgs/{orgId}/staff/create', [DashboardController::class, 'staffCreate'])->name('staff.create');
        Route::post('/orgs/{orgId}/staff/store', [DashboardController::class, 'staffStore'])->name('staff.store');
        Route::post('/staff/{id}/toggle-status', [DashboardController::class, 'staffToggleStatus'])->name('staff.toggle-status');

        Route::post('/properties/store', [DashboardController::class, 'propertyStore'])->name('properties.store');
        Route::post('/units/store', [DashboardController::class, 'unitStore'])->name('units.store');
        Route::get('/units/{id}/calendar', [DashboardController::class, 'unitCalendar'])->name('units.calendar');
        Route::post('/units/{id}/calendar/lock', [DashboardController::class, 'unitLockDates'])->name('units.lock-dates');

        Route::get('/bookings', [DashboardController::class, 'bookingsList'])->name('bookings.list');
        Route::get('/bookings/{id}', [DashboardController::class, 'bookingShow'])->name('bookings.show');
        Route::post('/bookings/{id}/status', [DashboardController::class, 'bookingUpdateStatus'])->name('bookings.update-status');

        Route::get('/settings', [DashboardController::class, 'settingsIndex'])->name('settings.index');
        Route::post('/settings/types/store', [DashboardController::class, 'typeStore'])->name('settings.types.store');
        Route::post('/settings/amenities/store', [DashboardController::class, 'amenityStore'])->name('settings.amenities.store');
        Route::post('/settings/locations/country/store', [DashboardController::class, 'countryStore'])->name('settings.countries.store');
        Route::post('/settings/locations/city/store', [DashboardController::class, 'cityStore'])->name('settings.cities.store');
        Route::post('/settings/locations/neighborhood/store', [DashboardController::class, 'neighborhoodStore'])->name('settings.neighborhoods.store');

        Route::get('/finance', [DashboardController::class, 'financeOverview'])->name('finance.overview');
        Route::get('/activity-log', [DashboardController::class, 'activityLog'])->name('activity-log');
    });
});

// Standalone Open Rental Microservice Test Dashboard
Route::prefix('test-dashboard')->group(function () {
    Route::get('/', [TestDashboardController::class, 'index'])->name('test-dashboard.index');
    Route::get('/properties', [TestDashboardController::class, 'properties'])->name('test-dashboard.properties');
    Route::get('/bookings', [TestDashboardController::class, 'bookings'])->name('test-dashboard.bookings');
    Route::get('/coupons', [TestDashboardController::class, 'coupons'])->name('test-dashboard.coupons');
    Route::get('/orgs', [TestDashboardController::class, 'orgs'])->name('test-dashboard.orgs');
    Route::get('/api-tester', [TestDashboardController::class, 'apiTester'])->name('test-dashboard.api-tester');
    Route::post('/api-tester/estimate', [TestDashboardController::class, 'estimate'])->name('test-dashboard.estimate');
});
