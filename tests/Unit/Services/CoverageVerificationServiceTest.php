<?php

namespace Tests\Unit\Services;

use App\Models\Card;
use App\Models\ProviderService;
use App\Models\User;
use App\Services\Provider\CoverageVerificationService;
use App\Services\Provider\LegacyProviderSyncService;
use App\Services\Subscription\SubscriptionService;
use Tests\BaselineTestCase;

class CoverageVerificationServiceTest extends BaselineTestCase
{
    public function test_coverage_is_true_when_subscription_type_matches()
    {
        $user = User::factory()->create();
        $card = Card::factory()->active()->create();
        $cardService = \App\Models\CardService::factory()->create([
            'card_id' => $card->id,
            'expir_date' => now()->addMonths(6),
            'show_on_app' => true,
        ]);

        $subscriptions = app(SubscriptionService::class);
        $subscriptions->createFromLegacyPayload([
            'name' => 'مشترك',
            'phone_number' => '07901110000',
            'card_number' => 'CARD-COV-1',
            'card_id' => $card->id,
            'user_id' => $user->id,
            'results' => 1,
        ]);

        $providerService = app(LegacyProviderSyncService::class)->syncCardService($cardService);

        $result = app(CoverageVerificationService::class)
            ->verify('CARD-COV-1', $providerService->id);

        $this->assertTrue($result['covered']);
        $this->assertEquals('ok', $result['reason']);
    }

    public function test_coverage_false_for_unknown_subscription()
    {
        $providerService = ProviderService::factory()->create();

        $result = app(CoverageVerificationService::class)
            ->verify('MISSING-NUMBER', $providerService->id);

        $this->assertFalse($result['covered']);
        $this->assertEquals('subscription_not_found', $result['reason']);
    }
}
