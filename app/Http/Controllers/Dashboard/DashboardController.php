<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\City;
use App\Models\Country;
use App\Models\DashboardActivityLog;
use App\Models\DashboardUser;
use App\Models\ManualLock;
use App\Models\Neighborhood;
use App\Models\Org;
use App\Models\OrgSetting;
use App\Models\OrgStaff;
use App\Models\Price;
use App\Models\Property;
use App\Models\PropertySetting;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private function currentUser(): DashboardUser
    {
        return Auth::guard('dashboard')->user() ?? session('dashboard_user');
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('orgs/logos', 'public');
            $logoPath = 'storage/' . $path;
        }

        $coverPath = null;
        if ($request->hasFile('cover_photo')) {
            $path = $request->file('cover_photo')->store('orgs/covers', 'public');
            $coverPath = 'storage/' . $path;
        }

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
            'logo' => $logoPath,
            'cover_photo' => $coverPath,
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
            'commission' => 'nullable|numeric|min:0|max:100',
            'status' => 'nullable|in:active,inactive,pending,suspended',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if (!$user->isAdmin()) {
            unset($data['status']);
            unset($data['commission']);
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('orgs/logos', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        if ($request->hasFile('cover_photo')) {
            $path = $request->file('cover_photo')->store('orgs/covers', 'public');
            $data['cover_photo'] = 'storage/' . $path;
        }

        $org->update($data);
        DashboardActivityLog::log('org.updated', $org);

        return back()->with('success', 'تم تحديث بيانات المنظمة بنجاح.');
    }

    public function orgSoftDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'حذف المنظمة يتطلب صلاحيات مدير النظام.');
        }

        $org = Org::findOrFail($id);
        $org->delete();

        DashboardActivityLog::log('org.soft_deleted', $org);

        return redirect()->route('dashboard.orgs.list')
            ->with('success', 'تم نقل المنظمة إلى سلة المحذوفات بنجاح.');
    }

    public function orgHardDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'الحذف النهائي يتطلب صلاحيات مدير النظام.');
        }

        $org = Org::withTrashed()->findOrFail($id);

        foreach ($org->properties as $prop) {
            $prop->units()->forceDelete();
            $prop->forceDelete();
        }

        DashboardUser::where('org_id', $org->id)->delete();
        $org->forceDelete();

        DashboardActivityLog::log('org.hard_deleted', null, ['org_id' => $id]);

        return redirect()->route('dashboard.orgs.list')
            ->with('success', 'تم حذف المنظمة وجميع العقارات والوحدات التابعة لها بشكل نهائي.');
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

    public function staffImpersonate($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'التصفح كـ موظف متاح حصرياً لمدير النظام.');
        }

        $targetUser = DashboardUser::findOrFail($id);

        if ($targetUser->id === $user->id) {
            return back()->with('error', 'أنت بالفعل مسجل الدخول بهذا الحساب.');
        }

        session([
            'impersonator_id' => $user->id,
            'dashboard_user_id' => $targetUser->id,
            'dashboard_user' => $targetUser,
        ]);
        Auth::guard('dashboard')->login($targetUser);

        DashboardActivityLog::log('staff.impersonated', $targetUser, [
            'impersonated_by' => $user->id,
            'target_name' => $targetUser->name,
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'أنت الآن تتصفح النظام بحساب: ' . $targetUser->name);
    }

    public function impersonateStop()
    {
        $impersonatorId = session('impersonator_id');
        if (!$impersonatorId) {
            return redirect()->route('dashboard.home');
        }

        $adminUser = DashboardUser::findOrFail($impersonatorId);

        session()->forget('impersonator_id');
        session([
            'dashboard_user_id' => $adminUser->id,
            'dashboard_user' => $adminUser,
        ]);
        Auth::guard('dashboard')->login($adminUser);

        DashboardActivityLog::log('staff.impersonate_stopped', $adminUser, [
            'restored_admin_id' => $adminUser->id,
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'تم إلغاء التصفح المؤقت والعودة بحساب مدير النظام.');
    }

    public function staffResetPassword(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403, 'إعادة تعيين كلمة المرور متاح حصرياً لمدير النظام.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $targetUser = DashboardUser::findOrFail($id);
        $targetUser->update([
            'password' => Hash::make($request->password),
        ]);

        DashboardActivityLog::log('staff.password_reset_by_admin', $targetUser, [
            'reset_by' => $user->id,
        ]);

        return back()->with('success', 'تم إعادة تعيين كلمة المرور بنجاح للمستخدم: ' . $targetUser->name);
    }

    public function propertyStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('manage_properties')) {
            abort(403);
        }

        $data = $request->validate([
            'org_id' => 'required|exists:orgs,id',
            'type_id' => 'nullable|exists:types,id',
            'rental_type_id' => 'nullable|exists:types,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:cities,id',
            'address_ar' => 'nullable|string',
            'status' => 'required|in:active,inactive,draft,pending',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $typeId = $data['type_id'] ?? $data['rental_type_id'] ?? null;
        if (!$typeId) {
            return back()->withErrors(['type_id' => 'يرجى تحديد نوع العقار.']);
        }

        if (!$user->isAdmin() && (int)$data['org_id'] !== (int)$user->org_id) {
            abort(403);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('properties/logos', 'public');
            $logoPath = 'storage/' . $path;
        }

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('properties/gallery', 'public');
                $uploadedImages[] = 'storage/' . $path;
            }
        }

        $property = Property::create([
            'org_id' => $data['org_id'],
            'type_id' => $typeId,
            'title_ar' => $data['name_ar'],
            'title_en' => $data['name_en'] ?? $data['name_ar'],
            'city_id' => $data['city_id'],
            'address_ar' => $data['address_ar'],
            'status' => $data['status'],
            'logo' => $logoPath,
            'images' => $uploadedImages,
        ]);

        DashboardActivityLog::log('property.created', $property);

        return back()->with('success', 'تم إضافة العقار بنجاح.');
    }

    public function propertyEdit($id)
    {
        $user = $this->currentUser();
        $property = Property::with(['org', 'type', 'city', 'neighborhood', 'settings'])->findOrFail($id);

        if (!$user->isAdmin() && (int)$property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $orgs = $user->isAdmin() ? Org::all() : Org::where('id', $user->org_id)->get();
        $types = Type::active()->get();
        $cities = City::all();
        $neighborhoods = $property->city_id ? Neighborhood::where('city_id', $property->city_id)->get() : collect();

        return view('dashboard.properties.edit', compact('property', 'orgs', 'types', 'cities', 'neighborhoods', 'user'));
    }

    public function propertyUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        $property = Property::findOrFail($id);

        if (!$user->isAdmin() && (int)$property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $data = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type_id' => 'required|exists:types,id',
            'city_id' => 'nullable|exists:cities,id',
            'neighborhood_id' => 'nullable|exists:neighborhoods,id',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',
            'rules_ar' => 'nullable|string',
            'rules_en' => 'nullable|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:active,inactive,draft,pending',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'existing_images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'has_custom_settings' => 'nullable|boolean',
            'check_in_time' => 'nullable|string',
            'check_out_time' => 'nullable|string',
            'cancellation_policy_ar' => 'nullable|string',
            'cancellation_policy_en' => 'nullable|string',
            'min_advance_booking_days' => 'nullable|integer|min:0',
            'max_advance_booking_days' => 'nullable|integer|min:1',
            'allow_instant_booking' => 'nullable|boolean',
            'requires_id_verification' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('properties/logos', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        $retainedImages = $request->input('existing_images', []);
        if (!is_array($retainedImages)) {
            $retainedImages = [];
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('properties/gallery', 'public');
                $retainedImages[] = 'storage/' . $path;
            }
        }
        $data['images'] = array_values($retainedImages);

        $property->update([
            'title_ar' => $data['title_ar'],
            'title_en' => $data['title_en'] ?? $data['title_ar'],
            'description_ar' => $data['description_ar'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'type_id' => $data['type_id'],
            'city_id' => $data['city_id'] ?? null,
            'neighborhood_id' => $data['neighborhood_id'] ?? null,
            'address_ar' => $data['address_ar'] ?? null,
            'address_en' => $data['address_en'] ?? null,
            'rules_ar' => $data['rules_ar'] ?? null,
            'rules_en' => $data['rules_en'] ?? null,
            'star_rating' => $data['star_rating'] ?? null,
            'status' => $data['status'],
            'logo' => $data['logo'] ?? $property->logo,
            'images' => $data['images'],
        ]);

        if (!empty($data['has_custom_settings'])) {
            PropertySetting::updateOrCreate(
                ['property_id' => $property->id],
                [
                    'check_in_time' => $data['check_in_time'] ?? '14:00:00',
                    'check_out_time' => $data['check_out_time'] ?? '11:00:00',
                    'cancellation_policy_ar' => $data['cancellation_policy_ar'] ?? 'مرنة - إلغاء مجاني حتى 24 ساعة',
                    'cancellation_policy_en' => $data['cancellation_policy_en'] ?? 'Flexible - Free cancellation up to 24h',
                    'min_advance_booking_days' => $data['min_advance_booking_days'] ?? 1,
                    'max_advance_booking_days' => $data['max_advance_booking_days'] ?? 365,
                    'allow_instant_booking' => !empty($data['allow_instant_booking']),
                    'requires_id_verification' => !empty($data['requires_id_verification']),
                ]
            );
        } else {
            PropertySetting::where('property_id', $property->id)->delete();
        }

        DashboardActivityLog::log('property.updated', $property);

        return redirect()->route('dashboard.orgs.show', $property->org_id)
            ->with('success', 'تم تحديث بيانات العقار والصور بنجاح.');
    }

    public function propertyDelete($id)
    {
        $user = $this->currentUser();
        $property = Property::findOrFail($id);

        if (!$user->isAdmin() && (int)$property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $property->units()->delete();
        $property->delete();

        DashboardActivityLog::log('property.deleted', null, ['property_id' => $id]);

        return back()->with('success', 'تم حذف العقار ووحداته التابعة بنجاح.');
    }

    public function unitStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('manage_units')) {
            abort(403);
        }

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'pricing_mode' => 'required|in:per_night,per_hour,per_slot',
            'max_guests' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'price_sar' => 'required|numeric|min:0',
            'price_yer_n' => 'nullable|numeric|min:0',
            'price_yer_s' => 'nullable|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,available,unavailable,maintenance',
            'amenity_ids' => 'nullable|array',
            'amenity_quantities' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $status = in_array($data['status'], ['active', 'available']) ? 'active' : 'inactive';

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
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'pricing_mode' => $data['pricing_mode'],
            'max_guests' => $data['max_guests'],
            'quantity' => $data['quantity'],
            'images' => $uploadedImages,
            'status' => $status,
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
            $syncData = [];
            foreach ($data['amenity_ids'] as $amId) {
                $qty = (int) ($data['amenity_quantities'][$amId] ?? 1);
                $syncData[$amId] = ['quantity' => max(1, $qty)];
            }
            $unit->amenities()->sync($syncData);
        }

        DashboardActivityLog::log('unit.created', $unit);

        return back()->with('success', 'تم إضافة وحدة الإيواء وتسجيل أسعار العملات والصور والمرافق بنجاح.');
    }

    public function unitEdit($id)
    {
        $user = $this->currentUser();
        $unit = Unit::with(['property.org', 'prices', 'amenities'])->findOrFail($id);

        if (!$user->isAdmin() && (int)$unit->property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $properties = $user->isAdmin() ? Property::all() : Property::where('org_id', $user->org_id)->get();
        $allAmenities = Amenity::all();
        $defaultPrice = $unit->prices->firstWhere('price_type', 'default');

        return view('dashboard.units.edit', compact('unit', 'properties', 'allAmenities', 'defaultPrice', 'user'));
    }

    public function unitUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        $unit = Unit::with('property')->findOrFail($id);

        if (!$user->isAdmin() && (int)$unit->property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'pricing_mode' => 'required|in:per_night,per_hour,per_slot',
            'max_guests' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,available,unavailable',
            'price_sar' => 'required|numeric|min:0',
            'price_yer_n' => 'nullable|numeric|min:0',
            'price_yer_s' => 'nullable|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'amenity_ids' => 'nullable|array',
            'amenity_quantities' => 'nullable|array',
            'existing_images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $status = in_array($data['status'], ['active', 'available']) ? 'active' : 'inactive';

        $retainedImages = $request->input('existing_images', []);
        if (!is_array($retainedImages)) {
            $retainedImages = [];
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = uniqid('unit_') . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('units', $filename, 'public');
                $retainedImages[] = 'storage/' . $path;
            }
        }

        $unit->update([
            'property_id' => $data['property_id'],
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'description_ar' => $data['description_ar'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'pricing_mode' => $data['pricing_mode'],
            'max_guests' => $data['max_guests'],
            'quantity' => $data['quantity'],
            'status' => $status,
            'images' => array_values($retainedImages),
        ]);

        Price::updateOrCreate(
            [
                'priceable_id' => $unit->id,
                'priceable_type' => Unit::class,
                'price_type' => Price::TYPE_DEFAULT,
            ],
            [
                'name' => 'السعر الأساسي',
                'price_sar' => $data['price_sar'],
                'price_yer_n' => $data['price_yer_n'] ?? 0.00,
                'price_yer_s' => $data['price_yer_s'] ?? 0.00,
                'price_usd' => $data['price_usd'] ?? 0.00,
            ]
        );

        if (!empty($data['amenity_ids'])) {
            $syncData = [];
            foreach ($data['amenity_ids'] as $amId) {
                $qty = (int) ($data['amenity_quantities'][$amId] ?? 1);
                $syncData[$amId] = ['quantity' => max(1, $qty)];
            }
            $unit->amenities()->sync($syncData);
        } else {
            $unit->amenities()->detach();
        }

        DashboardActivityLog::log('unit.updated', $unit);

        return redirect()->route('dashboard.orgs.show', $unit->property->org_id)
            ->with('success', 'تم تحديث بيانات وحدة الإيواء بنجاح.');
    }

    public function unitDelete($id)
    {
        $user = $this->currentUser();
        $unit = Unit::with('property')->findOrFail($id);

        if (!$user->isAdmin() && (int)$unit->property->org_id !== (int)$user->org_id) {
            abort(403);
        }

        $unit->delete();

        DashboardActivityLog::log('unit.deleted', null, ['unit_id' => $id]);

        return back()->with('success', 'تم حذف وحدة الإيواء بنجاح.');
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

    public function typeUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $type = Type::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);

        $type->update($data);
        DashboardActivityLog::log('type.updated', $type);

        return back()->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function typeDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $type = Type::findOrFail($id);
        $type->delete();

        DashboardActivityLog::log('type.deleted', null, ['type_id' => $id]);

        return back()->with('success', 'تم حذف التصنيف بنجاح.');
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
        ]);

        $amenity = Amenity::create([
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
        ]);

        DashboardActivityLog::log('amenity.created', $amenity);

        return back()->with('success', 'تم إضافة مرفق جديد بنجاح.');
    }

    public function amenityUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $amenity = Amenity::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $amenity->update($data);
        DashboardActivityLog::log('amenity.updated', $amenity);

        return back()->with('success', 'تم تحديث المرفق بنجاح.');
    }

    public function amenityDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $amenity = Amenity::findOrFail($id);
        $amenity->delete();

        DashboardActivityLog::log('amenity.deleted', null, ['amenity_id' => $id]);

        return back()->with('success', 'تم حذف المرفق بنجاح.');
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
            'status' => 'active',
        ]);

        DashboardActivityLog::log('country.created', $country);

        return back()->with('success', 'تم إضافة دولة جديدة بنجاح.');
    }

    public function countryUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $country = Country::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $country->update($data);
        DashboardActivityLog::log('country.updated', $country);

        return back()->with('success', 'تم تحديث الدولة بنجاح.');
    }

    public function countryDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $country = Country::findOrFail($id);
        $country->delete();

        DashboardActivityLog::log('country.deleted', null, ['country_id' => $id]);

        return back()->with('success', 'تم حذف الدولة بنجاح.');
    }

    public function cityStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $countryId = $data['country_id'] ?? Country::first()?->id;
        if (!$countryId) {
            $country = Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 'active']);
            $countryId = $country->id;
        }

        $city = City::create([
            'country_id' => $countryId,
            'name_ar' => $data['name_ar'],
            'name_en' => $data['name_en'] ?? $data['name_ar'],
            'status' => 'active',
        ]);

        DashboardActivityLog::log('city.created', $city);

        return back()->with('success', 'تم إضافة مدينة جديدة بنجاح.');
    }

    public function cityUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $city = City::findOrFail($id);
        $data = $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $city->update($data);
        DashboardActivityLog::log('city.updated', $city);

        return back()->with('success', 'تم تحديث المدينة بنجاح.');
    }

    public function cityDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $city = City::findOrFail($id);
        $city->delete();

        DashboardActivityLog::log('city.deleted', null, ['city_id' => $id]);

        return back()->with('success', 'تم حذف المدينة بنجاح.');
    }

    public function neighborhoodStore(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'city_id' => 'required|exists:cities,id',
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

    public function neighborhoodUpdate(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $neighborhood = Neighborhood::findOrFail($id);
        $data = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

        $neighborhood->update($data);
        DashboardActivityLog::log('neighborhood.updated', $neighborhood);

        return back()->with('success', 'تم تحديث الحي بنجاح.');
    }

    public function neighborhoodDelete($id)
    {
        $user = $this->currentUser();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->delete();

        DashboardActivityLog::log('neighborhood.deleted', null, ['neighborhood_id' => $id]);

        return back()->with('success', 'تم حذف الحي بنجاح.');
    }

    public function financeOverview(Request $request)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('view_financials')) {
            abort(403);
        }

        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = Booking::whereIn('status', ['confirmed', 'completed', 'paid'])
            ->with(['org.settings', 'transaction']);

        if (!$user->isAdmin()) {
            $query->where('org_id', $user->org_id);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $bookings = $query->get();

        $totalsByCurrency = $bookings->groupBy(function ($b) {
            return $b->transaction ? $b->transaction->currency : ($b->currency ?? 'SAR');
        })->map(function ($items, $currency) {
            $totalRevenue = $items->sum(function ($b) {
                return $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
            });
            $jacEarned = $items->sum(function ($b) {
                if ($b->transaction && $b->transaction->admin_commission > 0) {
                    return (float)$b->transaction->admin_commission;
                }
                $commissionRate = $b->org ? ($b->org->commission / 100) : 0.10;
                $sales = $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
                return $sales * $commissionRate;
            });
            $orgPayouts = $totalRevenue - $jacEarned;

            return (object)[
                'currency' => $currency,
                'total_revenue' => $totalRevenue,
                'jac_earned' => $jacEarned,
                'org_payouts' => $orgPayouts,
                'bookings_count' => $items->count(),
            ];
        });

        $orgBreakdownQuery = Org::with(['settings'])->withCount(['bookings']);
        if (!$user->isAdmin()) {
            $orgBreakdownQuery->where('id', $user->org_id);
        }
        $orgs = $orgBreakdownQuery->get();

        $orgsFinance = $orgs->map(function ($org) use ($bookings) {
            $orgBookings = $bookings->where('org_id', $org->id);
            $totalSales = $orgBookings->sum(function ($b) {
                return $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
            });
            $appCommission = $orgBookings->sum(function ($b) use ($org) {
                if ($b->transaction && $b->transaction->admin_commission > 0) {
                    return (float)$b->transaction->admin_commission;
                }
                $sales = $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
                return $sales * ($org->commission / 100);
            });
            $vendorPayout = $totalSales - $appCommission;

            $org->total_sales = $totalSales;
            $org->app_commission = $appCommission;
            $org->vendor_payout = $vendorPayout;
            $org->paid_bookings_count = $orgBookings->count();
            $org->payout_frequency = $org->settings->payout_frequency ?? 'monthly';
            $org->currency = $org->preferred_currency ?? 'SAR';

            return $org;
        });

        return view('dashboard.finance.overview', compact('totalsByCurrency', 'orgsFinance', 'user', 'fromDate', 'toDate'));
    }

    public function financeOrgDetail(Request $request, $id)
    {
        $user = $this->currentUser();
        if (!$user->hasPermission('view_financials')) {
            abort(403);
        }

        if (!$user->isAdmin() && (int)$user->org_id !== (int)$id) {
            abort(403);
        }

        $org = Org::with(['settings', 'dashboardUser'])->findOrFail($id);
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = Booking::where('org_id', $id)
            ->whereIn('status', ['confirmed', 'completed', 'paid'])
            ->with(['property', 'unit', 'transaction']);

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $allPaidBookings = (clone $query)->get();

        $totalsByCurrency = $allPaidBookings->groupBy(function ($b) {
            return $b->transaction ? $b->transaction->currency : ($b->currency ?? 'SAR');
        })->map(function ($items, $currency) use ($org) {
            $totalRevenue = $items->sum(function ($b) {
                return $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
            });
            $jacEarned = $items->sum(function ($b) use ($org) {
                if ($b->transaction && $b->transaction->admin_commission > 0) {
                    return (float)$b->transaction->admin_commission;
                }
                $sales = $b->transaction ? (float)$b->transaction->total_amount : (float)($b->unit_price * $b->nights_count);
                return $sales * ($org->commission / 100);
            });
            $orgPayouts = $totalRevenue - $jacEarned;

            return (object)[
                'currency' => $currency,
                'total_revenue' => $totalRevenue,
                'jac_earned' => $jacEarned,
                'org_payouts' => $orgPayouts,
                'bookings_count' => $items->count(),
            ];
        });

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.finance.org', compact('org', 'totalsByCurrency', 'bookings', 'user', 'fromDate', 'toDate'));
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
