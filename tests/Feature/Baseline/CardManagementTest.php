<?php

namespace Tests\Feature\Baseline;

use Tests\BaselineTestCase;
use App\Models\Card;
use App\Models\CardService;
use App\Models\Category;

/**
 * Baseline tests for Card Management
 * Testing current Card and CardService functionality
 */
class CardManagementTest extends BaselineTestCase
{
    public function test_card_can_be_created()
    {
        $card = Card::factory()->create([
            'name_ar' => 'بطاقة ذهبية',
            'price' => 850000,
        ]);

        $this->assertDatabaseHas('card', [
            'name_ar' => 'بطاقة ذهبية',
            'price' => 850000,
        ]);
    }

    public function test_active_scope_returns_only_active_cards()
    {
        Card::factory()->active()->count(3)->create();
        Card::factory()->expired()->count(2)->create();

        $activeCards = Card::active()->get();

        $this->assertCount(3, $activeCards);
    }

    public function test_expired_scope_returns_only_expired_cards()
    {
        Card::factory()->active()->count(3)->create();
        Card::factory()->expired()->count(2)->create();

        $expiredCards = Card::expired()->get();

        $this->assertCount(2, $expiredCards);
    }

    public function test_card_service_belongs_to_card()
    {
        $card = Card::factory()->create();
        $service = CardService::factory()->create([
            'card_id' => $card->id,
        ]);

        $this->assertInstanceOf(Card::class, $service->card);
        $this->assertEquals($card->id, $service->card->id);
    }

    public function test_card_has_many_services()
    {
        $card = Card::factory()->create();
        CardService::factory()->count(5)->create([
            'card_id' => $card->id,
        ]);

        $this->assertCount(5, $card->services);
    }

    public function test_card_service_can_be_popular()
    {
        $service = CardService::factory()->popular()->create();

        $this->assertTrue($service->is_popular);
    }

    public function test_category_can_have_children()
    {
        $parent = Category::factory()->parent()->create();
        $child = Category::factory()->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertEquals($parent->id, $child->parent_id);
    }
}
