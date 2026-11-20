<?php

namespace App\Providers;

use App\Models\Card;
use App\Models\Profile;
use App\Models\User;
use App\Policies\AccountingPolicy;
use App\Policies\CardPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Card::class => CardPolicy::class,
        Profile::class => ProfilePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('accounting.view', [AccountingPolicy::class, 'viewAny']);
        Gate::define('accounting.manage', [AccountingPolicy::class, 'manage']);
        Gate::define('accounting.pay', [AccountingPolicy::class, 'pay']);

        // Super admin bypass — use relation cache-friendly exists check
        Gate::before(function (User $user, string $ability) {
            static $adminBypass = [];

            $key = (string) $user->getKey();
            if ($key === '') {
                return null;
            }

            if (! array_key_exists($key, $adminBypass)) {
                try {
                    $adminBypass[$key] = $user->roles()
                        ->whereIn('name', ['super_admin', 'admin'])
                        ->exists();
                } catch (\Throwable $e) {
                    $adminBypass[$key] = false;
                }
            }

            return $adminBypass[$key] ? true : null;
        });
    }
}
