<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Parallel-run middleware: accepts Spatie roles OR legacy user_type names via type_id.
 */
class EnsureRoleOrUserType
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) {
            return $next($request);
        }

        $typeName = optional($user->userType)->name;
        if ($typeName && in_array($typeName, $roles, true)) {
            return $next($request);
        }

        abort(403);
    }
}
