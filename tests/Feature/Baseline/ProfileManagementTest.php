<?php

namespace Tests\Feature\Baseline;

use Tests\BaselineTestCase;
use App\Models\Profile;
use App\Models\User;
use App\Models\Card;

/**
 * Baseline tests for Profile (Subscriber) Management
 * Testing current Profile functionality
 */
class ProfileManagementTest extends BaselineTestCase
{
    public function test_profile_can_be_created()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'card_id' => $card->id,
            'name' => 'أحمد محمد',
            'phone_number' => '07901234567',
        ]);

        $this->assertDatabaseHas('profile', [
            'name' => 'أحمد محمد',
            'phone_number' => '07901234567',
        ]);
    }

    public function test_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    public function test_profile_belongs_to_card()
    {
        $card = Card::factory()->create();
        $profile = Profile::factory()->create([
            'card_id' => $card->id,
        ]);

        $this->assertInstanceOf(Card::class, $profile->card);
        $this->assertEquals($card->id, $profile->card->id);
    }

    public function test_profile_has_unique_card_number()
    {
        Profile::factory()->create([
            'card_number' => 'CARD-123456',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Profile::factory()->create([
            'card_number' => 'CARD-123456',
        ]);
    }

    public function test_profile_can_have_family_members()
    {
        $profile = Profile::factory()->create([
            'family_name' => 'سارة أحمد, محمد أحمد, فاطمة أحمد',
        ]);

        $this->assertNotNull($profile->family_name);
        $this->assertStringContainsString('سارة أحمد', $profile->family_name);
    }
}
