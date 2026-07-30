<?php

namespace Tests\Feature\Api;

use App\Models\Org;
use App\Models\Property;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FavouriteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_toggle_favourite_adds_and_removes_favourite_for_authenticated_user()
    {
        $token = 'test-passport-token';

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 42,
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Beach Villa', 'title_en' => 'Beach Villa']);

        // 1. Toggle ON
        $response = $this->withToken($token)
            ->postJson('/api/v1/favourites/toggle', ['property_id' => $property->id]);

        $response->assertStatus(200)
            ->assertJson(['is_favourite' => true]);

        $this->assertDatabaseHas('favourites', [
            'user_id' => 42,
            'property_id' => $property->id,
        ]);

        // 2. Toggle OFF
        $response2 = $this->withToken($token)
            ->postJson('/api/v1/favourites/toggle', ['property_id' => $property->id]);

        $response2->assertStatus(200)
            ->assertJson(['is_favourite' => false]);

        $this->assertDatabaseMissing('favourites', [
            'user_id' => 42,
            'property_id' => $property->id,
        ]);
    }

    public function test_list_favourites_returns_user_favourited_properties()
    {
        $token = 'test-passport-token-2';

        Http::fake([
            '*/api/v1/auth/me' => Http::response([
                'id' => 50,
                'name' => 'Jane',
                'email' => 'jane@example.com',
            ], 200),
        ]);

        $type = Type::create(['name_ar' => 'شقة', 'name_en' => 'Apartment']);
        $org = Org::create(['name_ar' => 'Org', 'name_en' => 'Org']);
        $property = Property::create(['org_id' => $org->id, 'type_id' => $type->id, 'title_ar' => 'Mountain Lodge', 'title_en' => 'Mountain Lodge']);

        $this->withToken($token)
            ->postJson('/api/v1/favourites/toggle', ['property_id' => $property->id]);

        $response = $this->withToken($token)->getJson('/api/v1/favourites');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
