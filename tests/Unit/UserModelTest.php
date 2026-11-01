<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for User Model
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_wallet_relationship()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Wallet::class, $user->wallet);
        $this->assertEquals($wallet->id, $user->wallet->id);
    }

    public function test_user_password_is_hidden()
    {
        $user = User::factory()->make();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
    }

    public function test_user_can_have_transactions()
    {
        $user = User::factory()->create();
        $wallet = Wallet::factory()->create([
            'user_id' => $user->id,
        ]);

        Transactions::factory()->count(3)->create([
            'morphed_id' => $user->id,
            'morphed_type' => User::class,
        ]);

        $this->assertCount(3, $user->transactions);
    }

    public function test_user_has_jwt_methods()
    {
        $user = User::factory()->make();

        $this->assertIsInt($user->getJWTIdentifier());
        $this->assertIsArray($user->getJWTCustomClaims());
    }
}
