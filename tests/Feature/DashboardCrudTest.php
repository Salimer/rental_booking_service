<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\City;
use App\Models\Country;
use App\Models\DashboardUser;
use App\Models\Neighborhood;
use App\Models\Org;
use App\Models\Price;
use App\Models\Property;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardCrudTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardUser $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = DashboardUser::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'phone' => '123456789',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'permissions' => array_fill_keys(array_keys(DashboardUser::ALL_PERMISSIONS), true),
            'status' => true,
        ]);
    }

    protected function actAsAdmin()
    {
        \Illuminate\Support\Facades\Auth::guard('dashboard')->login($this->adminUser);
        return $this->withSession([
            'dashboard_user' => $this->adminUser,
            'dashboard_user_id' => $this->adminUser->id,
        ])->from(route('dashboard.settings.index'));
    }

    protected function actAsUser(DashboardUser $user)
    {
        \Illuminate\Support\Facades\Auth::guard('dashboard')->login($user);
        return $this->withSession([
            'dashboard_user' => $user,
            'dashboard_user_id' => $user->id,
        ]);
    }

    public function test_org_crud_and_images_and_deletion()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('logo.jpg');
        $cover = UploadedFile::fake()->image('cover.jpg');

        // Store Org
        $response = $this->actAsAdmin()->post(route('dashboard.orgs.store'), [
            'name_ar' => 'منظمة التجربة',
            'name_en' => 'Test Org',
            'contact_phone' => '770000000',
            'contact_email' => 'org@test.com',
            'city' => 'صنعاء',
            'address_ar' => 'شارع حدة',
            'commission' => 15.5,
            'status' => 'active',
            'owner_name' => 'مالك المنظمة',
            'owner_email' => 'owner@test.com',
            'owner_password' => 'password123',
            'logo' => $logo,
            'cover_photo' => $cover,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orgs', ['name_ar' => 'منظمة التجربة', 'commission' => 15.5]);
        $org = Org::where('name_ar', 'منظمة التجربة')->first();
        $this->assertNotNull($org->logo);
        $this->assertNotNull($org->cover_photo);

        // Show Org
        $response = $this->actAsAdmin()->get(route('dashboard.orgs.show', $org->id));
        $response->assertStatus(200);
        $response->assertSee('منظمة التجربة');

        // Update Org
        $response = $this->actAsAdmin()->post(route('dashboard.orgs.update', $org->id), [
            'name_ar' => 'منظمة التجربة المحدثة',
            'name_en' => 'Test Org Updated',
            'contact_phone' => '771111111',
            'city' => 'عدن',
            'commission' => 12.0,
            'status' => 'active',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('orgs', ['id' => $org->id, 'name_ar' => 'منظمة التجربة المحدثة']);

        // Soft Delete Org
        $response = $this->actAsAdmin()->post(route('dashboard.orgs.delete', $org->id));
        $response->assertRedirect(route('dashboard.orgs.list'));
        $this->assertSoftDeleted('orgs', ['id' => $org->id]);

        // Hard Delete Org
        $response = $this->actAsAdmin()->post(route('dashboard.orgs.hard-delete', $org->id));
        $response->assertRedirect(route('dashboard.orgs.list'));
        $this->assertDatabaseMissing('orgs', ['id' => $org->id]);
    }

    public function test_property_crud_and_gallery_images()
    {
        Storage::fake('public');

        $org = Org::create(['name_ar' => 'منظمة العقارات', 'code' => 'ORG-PROP']);
        $type = Type::create(['name_ar' => 'فندق', 'name_en' => 'Hotel', 'status' => true]);
        $country = Country::create(['name_ar' => 'اليمن', 'name_en' => 'Yemen', 'status' => 'active']);
        $city = City::create(['country_id' => $country->id, 'name_ar' => 'إب', 'name_en' => 'Ibb', 'status' => 'active']);

        $logo = UploadedFile::fake()->image('prop_logo.png');
        $gallery1 = UploadedFile::fake()->image('g1.jpg');
        $gallery2 = UploadedFile::fake()->image('g2.jpg');

        // Store Property
        $response = $this->actAsAdmin()->post(route('dashboard.properties.store'), [
            'org_id' => $org->id,
            'type_id' => $type->id,
            'name_ar' => 'فندق قصر الرياض',
            'name_en' => 'Riyadh Palace Hotel',
            'city_id' => $city->id,
            'address_ar' => 'شارع تعز',
            'status' => 'active',
            'logo' => $logo,
            'images' => [$gallery1, $gallery2],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', ['title_ar' => 'فندق قصر الرياض', 'type_id' => $type->id]);
        $property = Property::where('title_ar', 'فندق قصر الرياض')->first();
        $this->assertCount(2, $property->images);

        // Edit Page
        $response = $this->actAsAdmin()->get(route('dashboard.properties.edit', $property->id));
        $response->assertStatus(200);
        $response->assertSee('فندق قصر الرياض');

        // Update Property & remove 1 gallery image
        $retainedImage = $property->images[0];
        $newGalleryImage = UploadedFile::fake()->image('g3.jpg');

        $response = $this->actAsAdmin()->post(route('dashboard.properties.update', $property->id), [
            'title_ar' => 'فندق قصر الرياض المعدل',
            'type_id' => $type->id,
            'city_id' => $city->id,
            'status' => 'active',
            'existing_images' => [$retainedImage],
            'images' => [$newGalleryImage],
            'has_custom_settings' => '1',
            'check_in_time' => '15:00',
            'check_out_time' => '12:00',
            'cancellation_policy_ar' => 'إلغاء مجاني',
        ]);

        $response->assertRedirect(route('dashboard.orgs.show', $org->id));
        $property->refresh();
        $this->assertEquals('فندق قصر الرياض المعدل', $property->title_ar);
        $this->assertCount(2, $property->images);
        $this->assertEquals($retainedImage, $property->images[0]);
        $this->assertNotNull($property->settings);

        // Delete Property
        $response = $this->actAsAdmin()->post(route('dashboard.properties.delete', $property->id));
        $response->assertRedirect();
        $this->assertSoftDeleted('properties', ['id' => $property->id]);
    }

    public function test_unit_crud_prices_and_amenity_quantities()
    {
        Storage::fake('public');

        $org = Org::create(['name_ar' => 'منظمة الوحدات', 'code' => 'ORG-UNIT']);
        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $property = Property::create([
            'org_id' => $org->id,
            'type_id' => $type->id,
            'title_ar' => 'عقار الوحدات',
            'status' => 'active',
        ]);
        $amenity1 = Amenity::create(['name_ar' => 'سرير إضافي']);
        $amenity2 = Amenity::create(['name_ar' => 'تلفزيون']);

        $img1 = UploadedFile::fake()->image('u1.jpg');

        // Store Unit
        $response = $this->actAsAdmin()->post(route('dashboard.units.store'), [
            'property_id' => $property->id,
            'name_ar' => 'جناح ملكي فاخر',
            'name_en' => 'Luxury Royal Suite',
            'pricing_mode' => 'per_night',
            'max_guests' => 4,
            'quantity' => 2,
            'price_sar' => 300,
            'price_yer_n' => 45000,
            'price_yer_s' => 130000,
            'price_usd' => 80,
            'status' => 'active',
            'amenity_ids' => [$amenity1->id, $amenity2->id],
            'amenity_quantities' => [
                $amenity1->id => 2,
                $amenity2->id => 1,
            ],
            'images' => [$img1],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('units', ['name_ar' => 'جناح ملكي فاخر', 'quantity' => 2]);
        $unit = Unit::where('name_ar', 'جناح ملكي فاخر')->first();

        $defaultPrice = Price::where('priceable_id', $unit->id)->where('priceable_type', Unit::class)->first();
        $this->assertEquals(300, $defaultPrice->price_sar);
        $this->assertEquals(45000, $defaultPrice->price_yer_n);

        $this->assertDatabaseHas('unit_amenity', [
            'unit_id' => $unit->id,
            'amenity_id' => $amenity1->id,
            'quantity' => 2,
        ]);

        // Edit Unit View
        $response = $this->actAsAdmin()->get(route('dashboard.units.edit', $unit->id));
        $response->assertStatus(200);
        $response->assertSee('جناح ملكي فاخر');

        // Update Unit
        $response = $this->actAsAdmin()->post(route('dashboard.units.update', $unit->id), [
            'property_id' => $property->id,
            'name_ar' => 'جناح ملكي فاخر معدل',
            'pricing_mode' => 'per_night',
            'max_guests' => 5,
            'quantity' => 3,
            'status' => 'active',
            'price_sar' => 350,
            'amenity_ids' => [$amenity1->id],
            'amenity_quantities' => [$amenity1->id => 3],
        ]);

        $response->assertRedirect(route('dashboard.orgs.show', $org->id));
        $unit->refresh();
        $this->assertEquals('جناح ملكي فاخر معدل', $unit->name_ar);
        $this->assertEquals(3, $unit->quantity);
        $this->assertDatabaseHas('unit_amenity', ['unit_id' => $unit->id, 'amenity_id' => $amenity1->id, 'quantity' => 3]);

        // Delete Unit
        $response = $this->actAsAdmin()->post(route('dashboard.units.delete', $unit->id));
        $response->assertRedirect();
        $this->assertSoftDeleted('units', ['id' => $unit->id]);
    }

    public function test_settings_crud_operations()
    {
        // Type Store, Update, Delete
        $response = $this->actAsAdmin()->post(route('dashboard.settings.types.store'), ['name_ar' => 'فيلا']);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard.settings.index'));
        $type = Type::first();
        $this->assertNotNull($type, 'Type is null after store');

        $response = $this->actAsAdmin()->post(route('dashboard.settings.types.update', $type->id), ['name_ar' => 'فيلا مفروشة']);
        $response->assertRedirect(route('dashboard.settings.index'));
        $this->assertDatabaseHas('types', ['id' => $type->id, 'name_ar' => 'فيلا مفروشة']);

        $response = $this->actAsAdmin()->post(route('dashboard.settings.types.delete', $type->id));
        $response->assertRedirect(route('dashboard.settings.index'));
        $this->assertDatabaseMissing('types', ['id' => $type->id]);

        // Amenity Store, Update, Delete
        $response = $this->actAsAdmin()->post(route('dashboard.settings.amenities.store'), ['name_ar' => 'جاكوزي']);
        $amenity = Amenity::where('name_ar', 'جاكوزي')->first();
        $this->assertNotNull($amenity, 'Amenity is null after store');

        $response = $this->actAsAdmin()->post(route('dashboard.settings.amenities.update', $amenity->id), ['name_ar' => 'جاكوزي ساخن']);
        $this->assertDatabaseHas('amenities', ['id' => $amenity->id, 'name_ar' => 'جاكوزي ساخن']);

        $response = $this->actAsAdmin()->post(route('dashboard.settings.amenities.delete', $amenity->id));
        $this->assertDatabaseMissing('amenities', ['id' => $amenity->id]);

        // Country, City, Neighborhood Store, Update, Delete
        $response = $this->actAsAdmin()->post(route('dashboard.settings.countries.store'), ['name_ar' => 'قطر']);
        $country = Country::where('name_ar', 'قطر')->first();
        $response = $this->actAsAdmin()->post(route('dashboard.settings.countries.update', $country->id), ['name_ar' => 'دولة قطر']);
        $this->assertDatabaseHas('countries', ['id' => $country->id, 'name_ar' => 'دولة قطر']);

        $response = $this->actAsAdmin()->post(route('dashboard.settings.cities.store'), ['country_id' => $country->id, 'name_ar' => 'الحديدة']);
        $city = City::where('name_ar', 'الحديدة')->first();
        $this->assertNotNull($city);
        $response = $this->actAsAdmin()->post(route('dashboard.settings.cities.update', $city->id), ['country_id' => $country->id, 'name_ar' => 'مدينة الحديدة']);
        $this->assertDatabaseHas('cities', ['id' => $city->id, 'name_ar' => 'مدينة الحديدة']);

        $response = $this->actAsAdmin()->post(route('dashboard.settings.neighborhoods.store'), ['city_id' => $city->id, 'name_ar' => 'الكورنيش']);
        $neighborhood = Neighborhood::where('name_ar', 'الكورنيش')->first();
        $this->assertNotNull($neighborhood);
        $response = $this->actAsAdmin()->post(route('dashboard.settings.neighborhoods.update', $neighborhood->id), ['city_id' => $city->id, 'name_ar' => 'حي الكورنيش']);
        $this->assertDatabaseHas('neighborhoods', ['id' => $neighborhood->id, 'name_ar' => 'حي الكورنيش']);
        $this->actAsAdmin()->post(route('dashboard.settings.neighborhoods.delete', $neighborhood->id));
        $this->assertDatabaseMissing('neighborhoods', ['id' => $neighborhood->id]);

        $this->actAsAdmin()->post(route('dashboard.settings.countries.delete', $country->id));
        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
    }

    public function test_finance_overview_and_org_detail_view()
    {
        $org = Org::create(['name_ar' => 'منظمة مالية', 'code' => 'ORG-FIN', 'commission' => 10.0]);

        $response = $this->actAsAdmin()->get(route('dashboard.finance.overview'));
        $response->assertStatus(200);
        $response->assertSee('منظمة مالية');

        $response = $this->actAsAdmin()->get(route('dashboard.finance.org', $org->id));
        $response->assertStatus(200);
        $response->assertSee('ORG-FIN');
    }

    public function test_admin_can_impersonate_and_stop_impersonation()
    {
        $admin = $this->adminUser;
        $owner = DashboardUser::create([
            'name' => 'Owner Impersonate Test',
            'email' => 'owner_imp_' . uniqid() . '@test.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'status' => true,
        ]);

        // Non-admin cannot impersonate
        $res = $this->actAsUser($owner)->post(route('dashboard.staff.impersonate', $admin->id));
        $res->assertStatus(403);

        // Admin impersonates owner
        $res = $this->actAsAdmin()->post(route('dashboard.staff.impersonate', $owner->id));
        $res->assertRedirect(route('dashboard.home'));
        $this->assertEquals($owner->id, session('dashboard_user_id'));
        $this->assertEquals($admin->id, session('impersonator_id'));

        // Stop impersonating
        $res = $this->post(route('dashboard.impersonate.stop'));
        $res->assertRedirect(route('dashboard.home'));
        $this->assertEquals($admin->id, session('dashboard_user_id'));
        $this->assertNull(session('impersonator_id'));
    }

    public function test_admin_can_reset_staff_password()
    {
        $owner = DashboardUser::create([
            'name' => 'Owner Reset Password Test',
            'email' => 'owner_reset_' . uniqid() . '@test.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'status' => true,
        ]);

        // Non-admin cannot reset password
        $res = $this->actAsUser($owner)->post(route('dashboard.staff.reset-password', $owner->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $res->assertStatus(403);

        // Admin resets password
        $res = $this->actAsAdmin()->post(route('dashboard.staff.reset-password', $owner->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $res->assertSessionHasNoErrors();

        $owner->refresh();
        $this->assertTrue(Hash::check('newpassword123', $owner->password));
    }
}
