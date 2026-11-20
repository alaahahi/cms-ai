<?php

namespace Tests\Feature\Api\V1;

use App\Models\Commission;
use App\Models\SalesRep;
use App\Models\User;
use App\Models\UserType;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SalesApiTest extends BaselineTestCase
{
    public function test_list_sales_reps_syncs_from_legacy_seles_users()
    {
        $selesType = UserType::where('name', 'seles')->firstOrFail();
        User::factory()->create([
            'type_id' => $selesType->id,
            'percentage' => 12,
        ]);

        $admin = User::factory()->create();
        $token = JWTAuth::fromUser($admin);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/sales-reps')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'user_id', 'commission_rate'],
                ],
            ]);
    }

    public function test_create_sale_and_pay_commission()
    {
        $rep = SalesRep::factory()->create(['commission_rate' => 10]);
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/sales', [
                'sales_rep_id' => $rep->id,
                'amount' => 500000,
                'cards_count' => 1,
            ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.amount', '500000.00');

        $commissionId = Commission::where('sales_rep_id', $rep->id)->value('id');
        $this->assertNotNull($commissionId);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/commissions/'.$commissionId.'/pay')
            ->assertOk()
            ->assertJsonPath('data.status', 'paid');
    }

    public function test_set_and_list_targets()
    {
        $rep = SalesRep::factory()->create();
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/targets', [
                'sales_rep_id' => $rep->id,
                'year' => 2026,
                'month' => 11,
                'target_amount' => 3000000,
                'target_cards' => 20,
            ])
            ->assertCreated()
            ->assertJsonPath('data.target_cards', 20);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/targets?sales_rep_id='.$rep->id)
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
