<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('user.view') || $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function view(User $actor, User $model): bool
    {
        if ($actor->id === $model->id) {
            return true;
        }

        return $this->viewAny($actor);
    }

    public function create(User $user): bool
    {
        return $user->can('user.manage') || $user->hasAnyRole(['super_admin', 'admin']);
    }

    public function update(User $actor, User $model): bool
    {
        if ($actor->id === $model->id) {
            return true;
        }

        return $this->create($actor);
    }

    public function delete(User $actor, User $model): bool
    {
        return $actor->can('user.manage') || $actor->hasAnyRole(['super_admin', 'admin']);
    }
}
