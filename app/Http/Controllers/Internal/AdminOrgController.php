<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Org;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class AdminOrgController extends Controller
{
    public function indexOrgs(Request $request)
    {
        $orgs = Org::withCount(['properties', 'bookings'])
            ->latest()
            ->paginate($request->query('per_page', 25));

        return response()->json($orgs);
    }

    public function showOrg($id)
    {
        $org = Org::with(['properties', 'settings'])->findOrFail($id);
        return response()->json($org);
    }

    public function storeOrg(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|string|max:100',
            'preferred_currency' => 'nullable|string|in:YER_N,YER_S,SAR,USD',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $org = Org::create($data);
        return response()->json($org, 201);
    }

    public function updateOrg(Request $request, $id)
    {
        $org = Org::findOrFail($id);
        $data = $request->validate([
            'name' => 'nullable|string|max:150',
            'code' => 'nullable|string|max:50',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|string|max:100',
            'preferred_currency' => 'nullable|string|in:YER_N,YER_S,SAR,USD',
            'status' => 'nullable|string|in:active,inactive',
        ]);
        $org->update($data);
        return response()->json($org);
    }

    public function indexProperties(Request $request)
    {
        $query = Property::with(['org', 'type', 'city', 'units']);

        if ($request->filled('org_id')) {
            $query->where('org_id', $request->org_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate($request->query('per_page', 25));
        return response()->json($properties);
    }

    public function indexUnits(Request $request)
    {
        $query = Unit::with(['property.org', 'amenities', 'prices']);

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $units = $query->latest()->paginate($request->query('per_page', 25));
        return response()->json($units);
    }

    public function indexBookings(Request $request)
    {
        $query = Booking::with(['property', 'unit', 'user']);

        if ($request->filled('org_id')) {
            $query->where('org_id', $request->org_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate($request->query('per_page', 25));
        return response()->json($bookings);
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => $request->input('status', $booking->status),
            'payment_status' => $request->input('payment_status', $booking->payment_status),
        ]);
        return response()->json($booking);
    }
}
