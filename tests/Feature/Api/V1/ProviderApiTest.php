<?php

namespace Tests\Feature\Api\V1;

use App\Models\Card;
use App\Models\CardService;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProviderApiTest extends BaselineTestCase
{
    public function test_list_providers_syncs_from_legacy_when_empty()
    {
        $card = Card::factory()->active()->create();
        CardService::factory()->count(2)->create([
            'card_id' => $card->id,
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
        ]);

        $this->getJson('/api/v1/providers')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name_ar', 'name_en'],
                ],
            ]);
    }

    public function test_verify_coverage_endpoint()
    {
        $user = User::factory()->create();
        $card = Card::factory()->active()->create();
        $cardService = CardService::factory()->create([
            'card_id' => $card->id,
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
        ]);

        app(SubscriptionService::class)->createFromLegacyPayload([
            'name' => 'علي',
            'phone_number' => '07902223344',
            'card_number' => 'CARD-API-COV',
            'card_id' => $card->id,
            'user_id' => $user->id,
            'results' => 1,
        ]);

        $providerService = app(\App\Services\Provider\LegacyProviderSyncService::class)
            ->syncCardService($cardService);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/coverage/verify', [
                'subscription_number' => 'CARD-API-COV',
                'provider_service_id' => $providerService->id,
            ])
            ->assertOk()
            ->assertJsonPath('data.covered', true);
    }

    public function test_record_lab_and_doctor_visits()
    {
        $user = User::factory()->create();
        $profile = \App\Models\Profile::factory()->create(['user_id' => $user->id]);
        $token = JWTAuth::fromUser($user);

        $lab = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/visits/lab', [
                'profile_id' => $profile->id,
                'husband_hb' => '14',
                'wife_hb' => '12',
            ]);

        $lab->assertCreated()
            ->assertJsonPath('data.visit_type', 'lab')
            ->assertJsonPath('data.legacy_profile_id', $profile->id);

        $doctor = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/visits/doctor', [
                'profile_id' => $profile->id,
                'husband_results' => 'ok',
                'wife_results' => 'ok',
            ]);

        $doctor->assertCreated()->assertJsonPath('data.visit_type', 'doctor');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/visits')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
