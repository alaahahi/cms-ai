<?php

namespace Tests\Unit\Services;

use App\Events\Subscription\ProfileRegistered;
use App\Models\Card;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Event;
use Tests\BaselineTestCase;

class SubscriptionServiceTest extends BaselineTestCase
{
    public function test_create_from_legacy_payload_dual_writes_profile_and_subscription()
    {
        Event::fake([ProfileRegistered::class]);

        $user = User::factory()->create();
        $card = Card::factory()->create();

        $service = app(SubscriptionService::class);

        $profile = $service->createFromLegacyPayload([
            'name' => 'مشترك تجريبي',
            'phone_number' => '07901112233',
            'card_number' => 'CARD-9001',
            'card_id' => $card->id,
            'user_id' => $user->id,
            'family_name' => 'سارة, أحمد',
            'results' => 1,
            'created' => now()->format('Y-m-d'),
            'source' => 'test',
        ]);

        $this->assertDatabaseHas('profile', [
            'id' => $profile->id,
            'card_number' => 'CARD-9001',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'legacy_profile_id' => $profile->id,
            'subscription_number' => 'CARD-9001',
            'status' => Subscription::STATUS_ACTIVE,
        ]);

        $subscription = Subscription::where('legacy_profile_id', $profile->id)->first();
        $this->assertCount(3, $subscription->members); // self + 2 family

        Event::assertDispatched(ProfileRegistered::class);
    }

    public function test_find_by_card_number_prefers_subscription_table()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create();
        $service = app(SubscriptionService::class);

        $service->createFromLegacyPayload([
            'name' => 'بحث',
            'phone_number' => '07904445566',
            'card_number' => 'CARD-FIND-1',
            'card_id' => $card->id,
            'user_id' => $user->id,
            'results' => 1,
        ]);

        $found = $service->findByCardNumber('CARD-FIND-1');

        $this->assertInstanceOf(Subscription::class, $found);
        $this->assertEquals('CARD-FIND-1', $found->subscription_number);
    }
}
