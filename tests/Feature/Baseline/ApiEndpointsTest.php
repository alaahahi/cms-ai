<?php

namespace Tests\Feature\Baseline;

use Tests\BaselineTestCase;
use App\Models\User;
use App\Models\Card;
use App\Models\CardService;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;

/**
 * Baseline tests for existing API endpoints
 * Testing current API behavior
 */
class ApiEndpointsTest extends BaselineTestCase
{
    public function test_api_returns_active_cards()
    {
        Card::factory()->active()->count(3)->create();

        $response = $this->getJson('/api/v1/cards/active');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name_ar', 'name_en', 'price']
                ]
            ]);
    }

    public function test_api_returns_active_card_services()
    {
        $card = Card::factory()->active()->create();
        CardService::factory()->count(5)->create([
            'card_id' => $card->id,
        ]);

        $response = $this->getJson('/api/v1/card-services/active?card_id=' . $card->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    public function test_authenticated_user_can_access_profile_cards()
    {
        $user = User::factory()->create();
        Profile::factory()->count(2)->create([
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/cards/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    public function test_api_can_search_card_services()
    {
        $card = Card::factory()->active()->create();
        CardService::factory()->create([
            'card_id' => $card->id,
            'service_name_ar' => 'فحص أسنان',
            'service_name_en' => 'Dental Checkup',
        ]);

        $response = $this->getJson('/api/v1/card-services/search?search_term=أسنان');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_api()
    {
        $response = $this->getJson('/api/v1/cards/me');

        $response->assertStatus(401);
    }
}
