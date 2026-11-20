<?php

namespace Tests\Feature\Api\V1;

use App\Models\Card;
use App\Models\CardService;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CardApiTest extends BaselineTestCase
{
    public function test_v1_active_cards_use_resource_shape()
    {
        Card::factory()->active()->count(2)->create();

        $response = $this->getJson('/api/v1/cards/active');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'success',
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name_ar', 'name_en', 'price'],
                ],
            ]);
    }

    public function test_v1_cards_me_requires_auth()
    {
        $this->getJson('/api/v1/cards/me')->assertStatus(401);
    }

    public function test_v1_cards_me_returns_profiles_for_authenticated_user()
    {
        $user = User::factory()->create(['phone_number' => '07909998877']);
        Profile::factory()->count(2)->create([
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
        ]);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/cards/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'phone_number', 'card_number'],
                ],
            ]);
    }

    public function test_v1_card_services_active_with_category_tree()
    {
        $card = Card::factory()->active()->create();
        $parent = Category::factory()->create(['card_id' => $card->id, 'parent_id' => null]);
        $child = Category::factory()->create(['card_id' => $card->id, 'parent_id' => $parent->id]);
        CardService::factory()->count(2)->create([
            'card_id' => $card->id,
            'category_id' => $child->id,
        ]);

        $this->getJson('/api/v1/card-services/active?card_id='.$card->id)
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data']);
    }

    public function test_v1_card_services_search()
    {
        $card = Card::factory()->active()->create();
        CardService::factory()->create([
            'card_id' => $card->id,
            'service_name_ar' => 'فحص عيون',
            'service_name_en' => 'Eye Check',
        ]);

        $this->getJson('/api/v1/card-services/search?search_term=عيون')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'service_name_ar', 'service_name_en'],
                ],
            ]);
    }

    public function test_v1_profile_me()
    {
        $user = User::factory()->create(['name' => 'API User']);
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/profile/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'API User')
            ->assertJsonStructure([
                'data' => ['id', 'name', 'phone_number', 'type_id'],
            ]);
    }
}
