<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingStatusLog;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\DateHold;
use App\Models\GatewayDiscount;
use App\Models\ManualLock;
use App\Models\Org;
use App\Models\OrgSetting;
use App\Models\OrgStaff;
use App\Models\Price;
use App\Models\Property;
use App\Models\PropertySetting;
use App\Models\Unit;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestDashboardController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Dashboard Overview
     */
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'total_units' => Unit::count(),
            'total_bookings' => Booking::count(),
            'active_coupons' => Coupon::where('status', true)->count(),
            'total_orgs' => Org::count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])->selectRaw('SUM(unit_price * nights_count) as revenue')->value('revenue') ?? 0,
        ];

        $recentBookings = Booking::with(['property', 'unit'])->latest()->limit(5)->get();
        $recentLogs = BookingStatusLog::latest()->limit(5)->get();

        return view('test-dashboard.index', compact('stats', 'recentBookings', 'recentLogs'));
    }

    /**
     * Properties, Units, Prices & Availabilities
     */
    public function properties(Request $request)
    {
        $tab = $request->query('tab', 'properties');

        $properties = Property::with(['org', 'city', 'type'])->latest()->get();
        $units = Unit::with('property')->latest()->get();
        $prices = Price::with('priceable')->latest()->limit(50)->get();
        $availabilities = Availability::with('unit')->latest()->limit(50)->get();
        $manualLocks = ManualLock::with('unit')->latest()->get();
        $amenities = Amenity::latest()->get();

        return view('test-dashboard.properties', compact(
            'tab', 'properties', 'units', 'prices', 'availabilities', 'manualLocks', 'amenities'
        ));
    }

    /**
     * Bookings, Payments, Status Logs & Holds
     */
    public function bookings(Request $request)
    {
        $tab = $request->query('tab', 'bookings');

        $bookings = Booking::with(['property', 'unit', 'statusLogs', 'payments'])->latest()->get();
        $payments = BookingPayment::with('booking')->latest()->get();
        $statusLogs = BookingStatusLog::with('booking')->latest()->limit(50)->get();
        $dateHolds = DateHold::with('unit')->latest()->get();

        return view('test-dashboard.bookings', compact(
            'tab', 'bookings', 'payments', 'statusLogs', 'dateHolds'
        ));
    }

    /**
     * Coupons & Discounts
     */
    public function coupons(Request $request)
    {
        $tab = $request->query('tab', 'coupons');

        $coupons = Coupon::latest()->get();
        $usages = CouponUsage::with('coupon')->latest()->limit(50)->get();
        $gatewayDiscounts = GatewayDiscount::latest()->get();

        return view('test-dashboard.coupons', compact('tab', 'coupons', 'usages', 'gatewayDiscounts'));
    }

    /**
     * Organizations, Staff & Settings
     */
    public function orgs(Request $request)
    {
        $tab = $request->query('tab', 'orgs');

        $orgs = Org::with(['settings', 'staff'])->latest()->get();
        $orgStaff = OrgStaff::with('org')->latest()->get();
        $orgSettings = OrgSetting::with('org')->latest()->get();
        $propertySettings = PropertySetting::with('property')->latest()->get();

        return view('test-dashboard.orgs', compact('tab', 'orgs', 'orgStaff', 'orgSettings', 'propertySettings'));
    }

    /**
     * API & Price Calculation Test Tool
     */
    public function apiTester()
    {
        $units = Unit::with('property')->get();
        return view('test-dashboard.api-tester', compact('units'));
    }

    /**
     * Process Price Estimation Calculation Test
     */
    public function estimate(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|integer|exists:units,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'coupon_code' => 'nullable|string',
        ]);

        try {
            $estimate = $this->bookingService->estimatePrice($request->all());
            return response()->json([
                'success' => true,
                'data' => $estimate,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
