<?php

namespace App\Http\Middleware;

use App\Models\SystemSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;


class Local
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Cache::forget('translations');

        Cache::rememberForever('translations', function () {

            $default_lang =  config('default_language') ?? Config::get('app.locale');

            $locale = Session::get('lang', $default_lang);

            App::setLocale($locale);

            $translations = collect();

            $locale = app()->getLocale();

            $translations[$locale] = [
                'php' => $this->phpTranslations($locale),
                'json' => $this->jsonTranslations($locale),
            ];

            return $translations;
        });

        return $next($request);

    }

    private function phpTranslations($locale)
    {
        $path = resource_path("lang/$locale");

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        });
    }

    private function jsonTranslations($locale)
    {
        $path = resource_path("lang/$locale.json");

        if (is_string($path) && is_readable($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }
}
