<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Mobile\DigitalCardService;

class DigitalCardController extends BaseApiController
{
    public function __construct(private DigitalCardService $cards)
    {
    }

    public function show(string $number)
    {
        $card = $this->cards->build($number);
        if (! $card) {
            return $this->error('Digital card not found', [], 404);
        }

        return $this->success($card);
    }
}
