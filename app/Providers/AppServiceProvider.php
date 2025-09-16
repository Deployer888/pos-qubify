<?php

namespace App\Providers;

use App\Models\SystemSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\Payments\PaypalService;
use App\Services\Payments\StripeService;
use App\Services\Product\ProductService;
use App\Services\Utils\FileUploadService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FileUploadService::class, function ($app) {
            return new FileUploadService();
        });

        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService();
        });

        $this->app->singleton(StripeService::class, function ($app) {
            return new StripeService();
        });
        $this->app->singleton(PaypalService::class, function ($app) {
            return new PaypalService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Schema::defaultStringLength(191);
            $settings = SystemSettings::all();
            $settings_array = convertDbSettingsToConfig($settings);
            Config::set($settings_array);
            // Set Pusher credentials dynamically
            Config::set([
                'broadcasting.connections.pusher.key' => config('PUSHER_APP_KEY', $settings_array['app_key'] ?? null),
                'broadcasting.connections.pusher.secret' => config('PUSHER_APP_SECRET', $settings_array['app_secret'] ?? null),
                'broadcasting.connections.pusher.app_id' => config('PUSHER_APP_ID', $settings_array['app_id'] ?? null),
                'broadcasting.connections.pusher.options.cluster' => config('PUSHER_APP_CLUSTER', $settings_array['app_cluster'] ?? null),
            ]);


            Config::set([
                // Stripe
                'stripe.currency_code' => 'USD',
                // Paypal
                'paypal.currency_code' => 'USD',
            ]);

            // set default time zone
            $timezone = config('timezone') ?? config('app.timezone');
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
