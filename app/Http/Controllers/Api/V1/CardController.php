<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CardResource;
use App\Http\Resources\V1\ProfileResource;
use App\Services\Catalog\CardCatalogService;
use Illuminate\Support\Facades\Auth;

class CardController extends BaseApiController
{
    public function __construct(private CardCatalogService $cards)
    {
    }

    public function active()
    {
        return $this->success(CardResource::collection($this->cards->listActive()));
    }

    public function me()
    {
        $profiles = $this->cards->profilesForUser(Auth::user());

        return $this->success(ProfileResource::collection($profiles));
    }
}
