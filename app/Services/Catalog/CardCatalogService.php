<?php

namespace App\Services\Catalog;

use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class CardCatalogService extends BaseService
{
    public function listActive(): Collection
    {
        return Card::active()->get();
    }

    public function profilesForUser(User $user): Collection
    {
        return Profile::with('card')
            ->where('phone_number', $user->phone_number)
            ->get();
    }
}
