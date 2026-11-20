<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\User;

class CardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('card.view') || $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function view(User $user, Card $card): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('card.manage') || $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function update(User $user, Card $card): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, Card $card): bool
    {
        return $this->create($user);
    }
}
