<?php

namespace Tests\Feature\Auth;

use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Gate;
use Tests\BaselineTestCase;

/**
 * Phase 1 Week 1: Spatie roles/permissions + legacy type_id parallel run.
 */
class PermissionTest extends BaselineTestCase
{
    public function test_admin_can_access_all_modules_via_gate()
    {
        $adminType = UserType::where('name', 'admin')->firstOrFail();
        $admin = User::factory()->create(['type_id' => $adminType->id]);
        $admin->assignRole('super_admin');

        $this->assertTrue($admin->hasRole('super_admin'));
        $this->assertTrue(Gate::forUser($admin)->allows('accounting.manage'));
        $this->assertTrue(Gate::forUser($admin)->allows('user.manage'));
    }

    public function test_sales_rep_can_only_access_sales_permissions()
    {
        $salesType = UserType::where('name', 'seles')->firstOrFail();
        $sales = User::factory()->create(['type_id' => $salesType->id]);
        $sales->syncRoles(['sales_rep']);

        $this->assertTrue($sales->hasPermissionTo('sales.create'));
        $this->assertTrue($sales->hasPermissionTo('subscription.create'));
        $this->assertFalse($sales->hasPermissionTo('accounting.manage'));
        $this->assertFalse($sales->hasPermissionTo('user.manage'));
    }

    public function test_subscriber_can_only_access_their_data()
    {
        $mobileType = UserType::where('name', 'mobile')->firstOrFail();
        $subscriber = User::factory()->create(['type_id' => $mobileType->id]);
        $subscriber->syncRoles(['subscriber']);

        $other = User::factory()->create(['type_id' => $mobileType->id]);
        $other->syncRoles(['subscriber']);

        $ownProfile = Profile::factory()->create(['user_id' => $subscriber->id]);
        $otherProfile = Profile::factory()->create(['user_id' => $other->id]);

        $this->assertTrue($subscriber->can('view', $ownProfile));
        $this->assertFalse($subscriber->can('view', $otherProfile));
        $this->assertFalse($subscriber->hasPermissionTo('card.manage'));
    }

    public function test_legacy_type_id_still_maps_to_role_on_create()
    {
        $selesType = UserType::where('name', 'seles')->firstOrFail();
        $user = User::factory()->create(['type_id' => $selesType->id]);

        $this->assertTrue($user->roles()->where('name', 'seles')->exists());
        $this->assertEquals($selesType->id, $user->type_id);
    }

    public function test_card_policy_allows_admin_and_denies_subscriber_update()
    {
        $card = Card::factory()->create();

        $adminType = UserType::where('name', 'admin')->firstOrFail();
        $admin = User::factory()->create(['type_id' => $adminType->id]);
        $admin->assignRole('admin');

        $mobileType = UserType::where('name', 'mobile')->firstOrFail();
        $subscriber = User::factory()->create(['type_id' => $mobileType->id]);
        $subscriber->syncRoles(['subscriber']);

        $this->assertTrue($admin->can('view', $card));
        $this->assertTrue($admin->can('update', $card));
        $this->assertTrue($subscriber->can('view', $card));
        $this->assertFalse($subscriber->can('update', $card));
    }

    public function test_role_or_type_middleware_accepts_legacy_type()
    {
        $doctorType = UserType::where('name', 'doctor')->firstOrFail();
        $user = User::factory()->create(['type_id' => $doctorType->id]);
        $user->roles()->detach();

        $this->assertEquals('doctor', $user->fresh()->userType->name);

        \Illuminate\Support\Facades\Route::middleware(['web', 'auth', 'role_or_type:doctor,provider'])
            ->get('/_test/doctor-only', fn () => response('ok'));

        $response = $this->actingAs($user)->get('/_test/doctor-only');
        $response->assertOk();
    }
}
