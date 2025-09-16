<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class NotInstallCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        try {
//            DB::connection()->getPdo();
//        } catch (\Exception $e) {
//
//            return response()->view('install.index');
//        }

        if (check_db_and_table_exist() && Schema::hasTable('system_settings') && Schema::hasTable('users') && isInstalled()) {
            return redirect('/');
        }else{
            if (\Route::current()->getName() == 'install.finalize') {
                return $next($request);
            }else{
                return response()->view('install.index');
            }
        }
        return $next($request);
    }
}
