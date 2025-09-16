<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\SystemSettings;
use App\Traits\ApiReturnFormatTrait;
use Closure;
use Illuminate\Http\Request;

class CheckApiKeyMiddleware
{
    use ApiReturnFormatTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        logger('CheckApiKeyMiddleware',[
            $request->header('Api-Key'),
            $request->headers->all(),
            $request->header('Authorization'),
            $request
        ]);

        if ($request->hasHeader('Api-Key')) {
            $settings = SystemSettings::where('settings_key', 'api_key')->first();

            if ($settings && $request->header('Api-Key') === $settings->settings_value) {
                return $next($request);
            }
        }

        return $this->responseWithError(__('API key invalid'), [], 403);
    }
}
