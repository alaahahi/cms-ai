<?php

namespace Tests\Feature\Api\V1;

use App\Models\Card;
use App\Models\Subscription;
use App\Models\User;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubscriptionApiTest extends BaselineTestCase
{
    public function test_list_subscription_types()
    {
        Card::factory()->active()->count(2)->create();

        $this->getJson('/api/v1/subscription-types')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name_ar', 'name_en', 'price'],
                ],
            ]);
    }

    public function test_create_and_list_subscriptions()
    {
        $user = User::factory()->create();
        $card = Card::factory()->active()->create();
        $token = JWTAuth::fromUser($user);

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/subscriptions', [
                'name' => 'علي حسن',
                'phone_number' => '07905556677',
                'card_number' => 'CARD-API-100',
                'card_id' => $card->id,
                'family_name' => 'مريم, يوسف',
            ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.subscription_number', 'CARD-API-100');

        $this->assertDatabaseHas('subscriptions', [
            'subscription_number' => 'CARD-API-100',
            'subscriber_id' => $user->id,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/subscriptions')
            ->assertOk()
            ->assertJsonPath('success', true);

        $id = $create->json('data.id');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/subscriptions/'.$id)
            ->assertOk()
            ->assertJsonPath('data.id', $id);

        $this->getJson('/api/v1/subscriptions/by-number/CARD-API-100')
            ->assertOk()
            ->assertJsonPath('data.subscription_number', 'CARD-API-100');
    }

    public function test_show_falls_back_from_legacy_profile_id()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create();

        // Create only legacy profile without going through dual-write service
        $profile = \App\Models\Profile::factory()->create([
            'user_id' => $user->id,
            'card_id' => $card->id,
            'card_number' => 'CARD-FALLBACK-1',
            'name' => 'Fallback User',
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/subscriptions/'.$profile->id);

        // find() falls back to profile id and syncs into subscriptions
        $response->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('subscriptions', [
            'legacy_profile_id' => $profile->id,
            'subscription_number' => 'CARD-FALLBACK-1',
        ]);
    }
}
