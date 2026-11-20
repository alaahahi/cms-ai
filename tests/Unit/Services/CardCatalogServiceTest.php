<?php

namespace Tests\Unit\Services;

use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use App\Services\Catalog\CardCatalogService;
use Tests\BaselineTestCase;

class CardCatalogServiceTest extends BaselineTestCase
{
    public function test_list_active_returns_only_active_cards()
    {
        Card::factory()->active()->count(3)->create();
        Card::factory()->expired()->count(2)->create();

        $service = app(CardCatalogService::class);

        $this->assertCount(3, $service->listActive());
    }

    public function test_profiles_for_user_filters_by_phone()
    {
        $user = User::factory()->create(['phone_number' => '07901230000']);
        Profile::factory()->count(2)->create([
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
        ]);
        Profile::factory()->create(['phone_number' => '07909999999']);

        $service = app(CardCatalogService::class);

        $this->assertCount(2, $service->profilesForUser($user));
    }
}
