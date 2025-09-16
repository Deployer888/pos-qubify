<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class CheckPermissionOrSuperAdmin
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $guard = 'web'; // Specify the guard
        
        if (!Auth::guard($guard)->check()) {
            return redirect('/login');
        }

        $user = Auth::guard($guard)->user();

        // If user is Super Admin, allow access
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check if permission exists first
        $permissionExists = Permission::where('name', $permission)
                                    ->where('guard_name', $guard)
                                    ->exists();

        if (!$permissionExists) {
            // Log the missing permission
            \Log::warning("Permission '{$permission}' does not exist for guard '{$guard}'");
            
            // Allow access for depot managers to depot dashboard for now
            if (str_contains($permission, 'Depot Dashboard') && $user->hasRole('Depot Manager')) {
                return $next($request);
            }
            
            // Otherwise deny access
            abort(403, "Permission '{$permission}' is not configured in the system.");
        }

        // Check for specific permission
        if ($user->hasPermissionTo($permission)) {
            return $next($request);
        }

        abort(403, 'You do not have the required permission to access this resource.');
    }
}