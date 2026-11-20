<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        $permissions = [
            // Subscription module
            'subscription.view',
            'subscription.create',
            'subscription.update',
            'subscription.delete',
            'subscription.renew',
            // Provider module
            'provider.view',
            'provider.create',
            'provider.update',
            'provider.delete',
            'provider.verify_coverage',
            'visit.record',
            'visit.approve',
            // Sales module
            'sales.view',
            'sales.create',
            'sales.manage_reps',
            'commission.view',
            // Customer service
            'ticket.view',
            'ticket.create',
            'ticket.assign',
            'ticket.resolve',
            // Accounting
            'invoice.view',
            'invoice.create',
            'invoice.pay',
            'accounting.view',
            'accounting.manage',
            // Analytics / users / cards (current system)
            'analytics.view',
            'user.view',
            'user.manage',
            'card.view',
            'card.manage',
            'profile.view',
            'profile.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, $guard);
        }

        $rolePermissions = [
            'super_admin' => $permissions,
            'subscription_manager' => [
                'subscription.view', 'subscription.create', 'subscription.update', 'subscription.delete', 'subscription.renew',
                'profile.view', 'profile.manage', 'card.view', 'card.manage',
            ],
            'provider_coordinator' => [
                'provider.view', 'provider.create', 'provider.update', 'provider.delete', 'provider.verify_coverage',
                'visit.record', 'visit.approve', 'card.view',
            ],
            'sales_manager' => [
                'sales.view', 'sales.create', 'sales.manage_reps', 'commission.view',
                'subscription.view', 'subscription.create', 'profile.view',
            ],
            'sales_rep' => [
                'sales.view', 'sales.create', 'commission.view',
                'subscription.view', 'subscription.create', 'profile.view',
            ],
            'customer_service_agent' => [
                'ticket.view', 'ticket.create', 'ticket.assign', 'ticket.resolve',
                'profile.view', 'subscription.view',
            ],
            'accountant' => [
                'invoice.view', 'invoice.create', 'invoice.pay',
                'accounting.view', 'accounting.manage', 'commission.view',
            ],
            'analyst' => [
                'analytics.view', 'subscription.view', 'sales.view', 'invoice.view', 'provider.view',
            ],
            'provider' => [
                'visit.record', 'provider.view', 'card.view',
            ],
            'subscriber' => [
                'subscription.view', 'profile.view', 'ticket.create', 'card.view',
            ],
            // Legacy aliases mapped from current user_type names
            'admin' => $permissions,
            'data_entry' => [
                'subscription.view', 'subscription.create', 'subscription.update',
                'profile.view', 'profile.manage', 'card.view',
            ],
            'seles' => [
                'sales.view', 'sales.create', 'commission.view',
                'subscription.view', 'subscription.create', 'profile.view',
            ],
            'doctor' => [
                'visit.record', 'provider.view', 'card.view',
            ],
            'account' => [
                'invoice.view', 'invoice.create', 'invoice.pay',
                'accounting.view', 'accounting.manage',
            ],
            'hospital' => [
                'visit.record', 'provider.view', 'card.view', 'profile.view',
            ],
            'mobile' => [
                'subscription.view', 'profile.view', 'ticket.create', 'card.view',
            ],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::findOrCreate($roleName, $guard);
            $role->syncPermissions($perms);
        }
    }
}
