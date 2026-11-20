<?php

namespace Tests\Feature\Api\V1;

use App\Models\Card;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Tests\BaselineTestCase;

class DigitalCardApiTest extends BaselineTestCase
{
    public function test_digital_card_payload_for_subscription()
    {
        $user = User::factory()->create();
        $card = Card::factory()->active()->create();

        app(SubscriptionService::class)->createFromLegacyPayload([
            'name' => 'حامل البطاقة',
            'phone_number' => '07909998877',
            'card_number' => 'CARD-DIGITAL-1',
            'card_id' => $card->id,
            'user_id' => $user->id,
            'results' => 1,
        ]);

        $this->getJson('/api/v1/digital-card/CARD-DIGITAL-1')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.subscription_number', 'CARD-DIGITAL-1')
            ->assertJsonStructure([
                'data' => [
                    'subscription_number',
                    'status',
                    'verify_url',
                    'members_count',
                ],
            ]);
    }

    public function test_digital_card_not_found()
    {
        $this->getJson('/api/v1/digital-card/MISSING-CARD')
            ->assertStatus(404)
            ->assertJsonPath('success', false);
    }
}
