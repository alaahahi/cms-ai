<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

/**
 * Parallel migration: assign Spatie roles from existing user_type / type_id.
 * Keeps type_id intact for backward compatibility.
 */
return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasTable('user_type')) {
            return;
        }

        $userTypes = DB::table('user_type')->get();

        foreach ($userTypes as $type) {
            Role::findOrCreate($type->name, 'web');
        }

        // Target architecture roles (in addition to legacy names)
        foreach ([
            'super_admin',
            'subscription_manager',
            'provider_coordinator',
            'sales_manager',
            'sales_rep',
            'customer_service_agent',
            'accountant',
            'analyst',
            'provider',
            'subscriber',
        ] as $roleName) {
            Role::findOrCreate($roleName, 'web');
        }

        User::withTrashed()->whereNotNull('type_id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $type = DB::table('user_type')->where('id', $user->type_id)->first();
                if (! $type) {
                    continue;
                }

                if (! $user->hasRole($type->name)) {
                    $user->assignRole($type->name);
                }

                // Bridge legacy admin to super_admin
                if ($type->name === 'admin' && ! $user->hasRole('super_admin')) {
                    $user->assignRole('super_admin');
                }
            }
        });
    }

    public function down()
    {
        // Roles stay; type_id remains source of truth during parallel run.
        // Intentionally no destructive rollback of role assignments.
    }
};
