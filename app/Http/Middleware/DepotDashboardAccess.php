<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DepotDashboardAccess
{
    /**
     * Handle an incoming request for depot dashboard access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            Log::warning('Unauthenticated depot dashboard access attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Authentication required'], 401);
            }
            
            return redirect()->route('login')->with('error', 'Please login to access the depot dashboard.');
        }
        
        // Check if user has required roles
        if (!$user->hasAnyRole(['Super Admin', 'Depot Manager'])) {
            Log::warning('Unauthorized depot dashboard access attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Insufficient permissions'], 403);
            }
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'You do not have permission to access the depot dashboard.');
        }
        
        // Additional security checks for Depot Managers
        if ($user->hasRole('Depot Manager') && !$user->hasRole('Super Admin')) {
            // Check if depot manager has an assigned depot
            $hasDepot = \App\Models\Depot::where('user_id', $user->id)->exists();
            
            if (!$hasDepot) {
                Log::info('Depot Manager without assigned depot accessing dashboard', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                
                // Allow access but they'll see the "no depot assigned" message
            }
        }
        
        // Rate limiting check for API requests
        if ($request->expectsJson()) {
            $rateLimitKey = 'dashboard_access_' . $user->id;
            $attempts = cache()->get($rateLimitKey, 0);
            
            if ($attempts >= 60) { // Max 60 requests per minute
                Log::warning('Dashboard access rate limit exceeded', [
                    'user_id' => $user->id,
                    'attempts' => $attempts,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'error' => 'Too many requests. Please wait before trying again.',
                    'retry_after' => 60
                ], 429);
            }
            
            cache()->put($rateLimitKey, $attempts + 1, 60);
        }
        
        // Log successful access
        Log::info('Depot dashboard access granted', [
            'user_id' => $user->id,
            'role' => $user->getRoleNames()->first(),
            'ip' => $request->ip(),
            'is_ajax' => $request->expectsJson()
        ]);
        
        return $next($request);
    }
}