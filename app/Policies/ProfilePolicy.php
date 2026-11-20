<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

class ProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'admin',
            'subscription_manager',
            'data_entry',
            'seles',
            'sales_rep',
            'sales_manager',
            'customer_service_agent',
        ]) || $user->can('profile.manage');
    }

    public function view(User $user, Profile $profile): bool
    {
        if ($user->id === $profile->user_id || $user->id === $profile->cardHolder_id) {
            return true;
        }

        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('profile.manage')
            || $user->can('subscription.create')
            || $user->hasAnyRole(['super_admin', 'admin', 'data_entry', 'seles', 'sales_rep']);
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->can('profile.manage') || $user->hasAnyRole(['super_admin', 'admin', 'data_entry']);
    }

    public function delete(User $user, Profile $profile): bool
    {
        return $user->can('profile.manage') || $user->hasAnyRole(['super_admin', 'admin']);
    }
}
