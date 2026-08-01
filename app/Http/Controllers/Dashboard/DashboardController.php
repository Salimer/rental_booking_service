<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\DashboardActivityLog;
use App\Models\DashboardUser;
use App\Models\ManualLock;
use App\Models\Neighborhood;
use App\Models\Org;
use App\Models\OrgStaff;
use App\Models\Price;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    private function currentUser(): DashboardUser
    {
        return session('dashboard_user');
    }

    public function home()
    {
        $user = $this->currentUser();

        if ($user->isAdmin()) {
            $totalOrgs = Org::count();
            $totalProperties = Property::count();
            $totalUnits = Unit::count();
            $totalBookings = Booking::count();
            $recentBookings = Booking::with(['property', 'unit', 'user'])->latest()->take(6)->get();
            $recentOrgs = Org::withCount(['properties', 'units', 'bookings'])->latest()->take(5)->get();
            $revenueTotal = (float) Booking::whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
        } else {
            $orgId = $user->org_id;
            $totalOrgs = 1;
            $totalProperties = Property::where('org_id', $orgId)->count();
            $totalUnits = Unit::whereHas('property', fn($q) => $q->where('org_id', $orgId))->count();
            $totalBookings = Booking::where('org_id', $orgId)->count();
            $recentBookings = Booking::where('org_id', $orgId)->with(['property', 'unit', 'user'])->latest()->take(6)->get();
            $recentOrgs = Org::where('id', $orgId)->withCount(['properties', 'units', 'bookings'])->get();
            $revenueTotal = (float) Booking::where('org_id', $orgId)->whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
        }

        return view('dashboard.home', compact(
            'user',
            'totalOrgs',
            'totalProperties',
            'totalUnits',
            'totalBookings',
            'recentBookings',
            'recentOrgs',
            'revenueTotal'
        ));
    }

    public function orgsList(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            return redirect()->route('dashboard.orgs.show', $user->org_id);
        }

        $query = Org::withCount(['properties', 'units', 'bookings', 'staff'])->with('dashboardUser');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orgs = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.orgs.list', compact('orgs', 'user'));
    }

    public function orgShow($id)
    {
        $user = $this->currentUser();

        if (!$user->isAdmin() && (int)$user->org_id !== (int)$id) {
            abort(403, 'غير مصرح لك بالوصول لبيانات هذه المنظمة.');
        }

        $org = Org::with(['dashboardUser', 'settings', 'properties.units.prices', 'properties.type', 'properties.city'])->findOrFail($id);
        $staff = DashboardUser::where('org_id', $id)->latest()->get();
        $bookings = Booking::where('org_id', $id)->with(['property', 'unit', 'user'])->latest()->paginate(10);
        $allTypes = Type::active()->get();
        $allCities = City::all();
        $allAmenities = Amenity::all();

        return view('dashboard.orgs.show', compact('org', 'staff', 'bookings', 'user', 'allTypes', 'allCities', 'allAmenities'));
    }

    public function orgCreate()
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'فقط المسؤول يمكنه إضافة منظمات جديدة.');
        }

        $cities = City::all();
        return view('dashboard.orgs.create', compact('cities', 'user'));
    }

    public function orgStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'فقط المسؤول يمكنه إضافة منظمات جديدة.');
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'address_ar' => 'nullable|string',
            'commission' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,pending,suspended',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:dashboard_users,email',
            'owner_password' => 'required|string|min:6',
        ]);

        $org = Org::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'code' => 'ORG-' . strtoupper(uniqid()),
            'contact_phone' => $data['contact_phone'],
            'contact_email' => $data['contact_email'],
            'city' => $data['city'],
            'address_ar' => $data['address_ar'],
            'commission' => $data['commission'] ?? 10.00,
            'status' => $data['status'],
        ]);

        $owner = DashboardUser::create([
            'org_id' => $org->id,
            'name' => $data['owner_name'],
            'email' => strtolower(trim($data['owner_email'])),
            'phone' => $data['contact_phone'],
            'password' => Hash::make($data['owner_password']),
            'role' => 'owner',
            'permissions' => array_fill_keys(array_keys(DashboardUser::ALL_PERMISSIONS), true),
            'status' => true,
        ]);

        $org->update(['dashboard_user_id' => $owner->id]);

        DashboardActivityLog::log('org.created', $org, ['org_id' => $org->id, 'owner_id' => $owner->id]);

        return redirect()->route('dashboard.orgs.show', $org->id)
            ->with('success', 'تم إنشاء المنظمة وحساب مالك المنظمة بنجاح.');
    }

    public function orgUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin() && (int)$user->org_id !== (int)$id) {
            abort(403);
        }

        $org = Org::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'address_ar' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,pending,suspended',
        ]);

        if (!$user->isAdmin()) {
            unset($data['status']); // Only admin can change status
        }

        $org->update($data);
        DashboardActivityLog::log('org.updated', $org);

        return back()->with('success', 'تم تحديث بيانات المنظمة بنجاح.');
    }

    public function staffCreate($orgId)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin() && !$user->hasPermission('manage_staff')) {
            abort(403, 'غير مصرح لك بإضافة موظفين.');
        }

        $org = Org::findOrFail($orgId);
        $permissions = DashboardUser::ALL_PERMISSIONS;

        return view('dashboard.staff.create', compact('org', 'permissions', 'user'));
    }

    public function staffStore(Request $request, $orgId)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'إضافة الموظفين وتحديد صلاحياتهم تتم حصرياً عبر إدارة النظام.');
        }

        $org = Org::findOrFail($orgId);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dashboard_users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
            'role' => 'required|in:manager,receptionist',
            'permissions' => 'nullable|array',
        ]);

        $selectedPermissions = [];
        foreach (array_keys(DashboardUser::ALL_PERMISSIONS) as $permKey) {
            $selectedPermissions[$permKey] = !empty($data['permissions'][$permKey]);
        }

        $staffUser = DashboardUser::create([
            'org_id' => $org->id,
            'name' => $data['name'],
            'email' => strtolower(trim($data['email'])),
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'permissions' => $selectedPermissions,
            'status' => true,
        ]);

        // Mirror in org_staff for system consistency
        OrgStaff::create([
            'org_id' => $org->id,
            'dashboard_user_id' => $staffUser->id,
            'rental_role' => $data['role'],
            'permissions' => $selectedPermissions,
            'status' => true,
        ]);

        DashboardActivityLog::log('staff.created', $staffUser, ['org_id' => $org->id, 'role' => $data['role']]);

        return redirect()->route('dashboard.orgs.show', $org->id)
            ->with('success', 'تم إنشاء حساب الموظف وتعيين الصلاحيات بنجاح.');
    }

    public function staffToggleStatus($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $staffUser = DashboardUser::findOrFail($id);
        $staffUser->update(['status' => !$staffUser->status]);

        DashboardActivityLog::log('staff.status_toggled', $staffUser, ['new_status' => $staffUser->status]);

        return back()->with('success', 'تم تغيير حالة الحساب بنجاح.');
    }

    public function propertyStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('manage_properties')) {
            abort(403);
        }

        $data = $request->validate([
            'org_id' => 'required|exists:orgs,id',
            'rental_type_id' => 'required|exists:rental_types,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:rental_cities,id',
            'address_ar' => 'nullable|string',
            'status' => 'required|in:active,inactive,pending',
        ]);

        if (!$user->isAdmin() && (int)$data['org_id'] !== (int)$user->org_id) {
            abort(403);
        }

        $property = Property::create([
            'org_id' => $data['org_id'],
            'rental_type_id' => $data['rental_type_id'],
            'title_ar' => $data['name_ar'],
            'title_en' => $data['name_en'] ?? $data['name_ar'],
            'city_id' => $data['city_id'],
            'address_ar' => $data['address_ar'],
            'status' => $data['status'],
        ]);

        DashboardActivityLog::log('property.created', $property);

        return back()->with('success', 'تم إضافة العقار بنجاح.');
    }

    public function unitStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('manage_units')) {
            abort(403);
        }

        $data = $request->validate([
            'property_id' => 'required|exists:rental_properties,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'price_sar' => 'required|numeric|min:0',
            'price_yer_n' => 'nullable|numeric|min:0',
            'price_yer_s' => 'nullable|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,unavailable,maintenance',
            'amenity_ids' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $property = Property::findOrFail($data['property_id']);
        if (!$user->isAdmin() && (int)$property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = uniqid('unit_') . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('units', $filename, 'public');
                $uploadedImages[] = 'storage/' . $path;
            }
        }

        $unit = Unit::create([
            'property_id' => $property->id,
            'name' => $data['name_ar'],
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'bedrooms' => $data['bedrooms'],
            'bathrooms' => $data['bathrooms'],
            'max_guests' => $data['max_guests'],
            'images' => $uploadedImages,
            'status' => $data['status'],
        ]);

        Price::create([
            'priceable_id' => $unit->id,
            'priceable_type' => Unit::class,
            'price_type' => Price::TYPE_DEFAULT,
            'name' => 'السعر الأساسي',
            'price_sar' => $data['price_sar'],
            'price_yer_n' => $data['price_yer_n'] ?? 0.00,
            'price_yer_s' => $data['price_yer_s'] ?? 0.00,
            'price_usd' => $data['price_usd'] ?? 0.00,
        ]);

        if (!empty($data['amenity_ids'])) {
            $unit->amenities()->sync($data['amenity_ids']);
        }

        DashboardActivityLog::log('unit.created', $unit);

        return back()->with('success', 'تم إضافة وحدة الإيواء وتسجيل أسعار العملات الأربع والصور بنجاح.');
    }

    public function unitCalendar($id)
    {
        $user = $this->currentUser();
        $unit = Unit::with(['property.org', 'manualLocks'])->findOrFail($id);

        if (!$user->isAdmin() && (int)$unit->property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $locks = ManualLock::where('unit_id', $id)->latest()->get();
        $bookings = Booking::where('unit_id', $id)->whereIn('status', ['confirmed', 'paid', 'checked_in'])->get();

        return view('dashboard.units.calendar', compact('unit', 'locks', 'bookings', 'user'));
    }

    public function unitLockDates(Request $request, $id)
    {
        $user = $this->currentUser();
        $unit = Unit::with('property')->findOrFail($id);

        if (!$user->isAdmin() && (int)$unit->property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        ManualLock::create([
            'unit_id' => $unit->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? 'إغلاق يدوي من لوحة التحكم',
            'created_by_user_id' => $user->id,
        ]);

        DashboardActivityLog::log('unit.dates_locked', $unit, $data);

        return back()->with('success', 'تم حظر التواريخ المحددة بنجاح.');
    }

    public function bookingsList(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('view_bookings')) {
            abort(403);
        }

        $query = Booking::with(['property', 'unit', 'user', 'org']);

        if (!$user->isAdmin()) {
            $query->where('org_id', $user->org_id);
        } elseif ($request->filled('org_id')) {
            $query->where('org_id', $request->org_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        $orgs = $user->isAdmin() ? Org::all() : [];

        return view('dashboard.bookings.list', compact('bookings', 'orgs', 'user'));
    }

    public function bookingShow($id)
    {
        $user = $this->currentUser();
        $booking = Booking::with(['property.org', 'unit', 'user', 'payments', 'statusLogs'])->findOrFail($id);

        if (!$user->isAdmin() && (int)$booking->org_id !== (int)$user->org_id) {
            abort(403);
        }

        return view('dashboard.bookings.show', compact('booking', 'user'));
    }

    public function bookingUpdateStatus(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('manage_bookings')) {
            abort(403);
        }

        $booking = Booking::findOrFail($id);
        if (!$user->isAdmin() && (int)$booking->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $data['status']]);

        DashboardActivityLog::log('booking.status_changed', $booking, [
            'from' => $oldStatus,
            'to' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'تم تغيير حالة الحجز بنجاح.');
    }

    public function settingsIndex()
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'الإعدادات العامة متاحة فقط لإدارة النظام.');
        }

        $types = Type::all();
        $amenities = Amenity::all();
        $countries = Country::withCount('cities')->get();
        $cities = City::withCount('neighborhoods')->get();
        $neighborhoods = Neighborhood::with('city')->latest()->get();

        return view('dashboard.settings.index', compact('types', 'amenities', 'countries', 'cities', 'neighborhoods', 'user'));
    }

    public function countryStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $country = Country::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'status' => true,
        ]);

        DashboardActivityLog::log('country.created', $country);

        return back()->with('success', 'تم إضافة دولة جديدة بنجاح.');
    }

    public function typeStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $type = Type::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'icon' => $data['icon'] ?? 'ti-home',
            'status' => true,
        ]);

        DashboardActivityLog::log('type.created', $type);

        return back()->with('success', 'تم إضافة تصنيف إيواء جديد بنجاح.');
    }

    public function amenityStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $amenity = Amenity::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'icon' => $data['icon'] ?? 'ti-star',
        ]);

        DashboardActivityLog::log('amenity.created', $amenity);

        return back()->with('success', 'تم إضافة مرفق جديد بنجاح.');
    }

    public function cityStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $city = City::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'status' => true,
        ]);

        DashboardActivityLog::log('city.created', $city);

        return back()->with('success', 'تم إضافة مدينة جديدة بنجاح.');
    }

    public function neighborhoodStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'city_id' => 'required|exists:rental_cities,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $neighborhood = Neighborhood::create([
            'city_id' => $data['city_id'],
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
        ]);

        DashboardActivityLog::log('neighborhood.created', $neighborhood);

        return back()->with('success', 'تم إضافة حي جديد بنجاح.');
    }

    public function financeOverview()
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('view_financials')) {
            abort(403);
        }

        if ($user->isAdmin()) {
            $totalRevenue = (float) Booking::whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
            $orgsFinance = Org::withCount(['bookings'])->get()->map(function ($org) {
                $org->total_sales = (float) Booking::where('org_id', $org->id)->whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
                $org->app_commission = $org->total_sales * ($org->commission / 100);
                $org->vendor_payout = $org->total_sales - $org->app_commission;
                return $org;
            });
        } else {
            $totalRevenue = (float) Booking::where('org_id', $user->org_id)->whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
            $orgsFinance = Org::where('id', $user->org_id)->get()->map(function ($org) {
                $org->total_sales = (float) Booking::where('org_id', $org->id)->whereIn('status', ['confirmed', 'completed', 'paid'])->sum(DB::raw('unit_price * nights_count'));
                $org->app_commission = $org->total_sales * ($org->commission / 100);
                $org->vendor_payout = $org->total_sales - $org->app_commission;
                return $org;
            });
        }

        return view('dashboard.finance.overview', compact('totalRevenue', 'orgsFinance', 'user'));
    }

    public function activityLog()
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'سجل النشاط متاح فقط لإدارة النظام.');
        }

        $logs = DashboardActivityLog::with('user')->latest()->paginate(25);

        return view('dashboard.activity.log', compact('logs', 'user'));
    }
}
