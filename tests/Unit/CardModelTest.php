<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for Card Model
 */
class CardModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_card_has_name_attribute_accessor()
    {
        $card = Card::factory()->make([
            'name_ar' => 'بطاقة ذهبية',
            'name_en' => 'Golden Card',
        ]);

        app()->setLocale('ar');
        $this->assertEquals('بطاقة ذهبية', $card->name);

        app()->setLocale('en');
        $this->assertEquals('Golden Card', $card->name);
    }

    public function test_card_has_description_attribute_accessor()
    {
        $card = Card::factory()->make([
            'description_ar' => 'وصف بالعربي',
            'description_en' => 'English description',
        ]);

        app()->setLocale('ar');
        $this->assertEquals('وصف بالعربي', $card->description);

        app()->setLocale('en');
        $this->assertEquals('English description', $card->description);
    }

    public function test_card_expiry_date_is_cast_to_carbon()
    {
        $card = Card::factory()->make([
            'expir_date' => '2024-12-31',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $card->expir_date);
    }

    public function test_show_on_app_is_cast_to_boolean()
    {
        $card = Card::factory()->make([
            'show_on_app' => 1,
        ]);

        $this->assertIsBool($card->show_on_app);
        $this->assertTrue($card->show_on_app);
    }

    public function test_formatted_expir_date_attribute()
    {
        $card = Card::factory()->make([
            'expir_date' => '2024-12-31',
        ]);

        $this->assertEquals('31-12-2024', $card->formatted_expir_date);
    }
}
