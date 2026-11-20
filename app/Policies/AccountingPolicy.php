<?php

namespace App\Policies;

use App\Models\User;

class AccountingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('accounting.view')
            || $user->hasAnyRole(['super_admin', 'admin', 'accountant', 'account']);
    }

    public function manage(User $user): bool
    {
        return $user->can('accounting.manage')
            || $user->hasAnyRole(['super_admin', 'admin', 'accountant', 'account']);
    }

    public function pay(User $user): bool
    {
        return $user->can('invoice.pay')
            || $user->hasAnyRole(['super_admin', 'admin', 'accountant', 'account']);
    }
}
