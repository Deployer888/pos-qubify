<?php

namespace App\Http\Controllers\Api\v100\Common;

use DB;
use Illuminate\Http\Request;
use App\Models\SystemSettings;
use App\Http\Controllers\Controller;
use App\Services\Brand\BrandService;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CitiesResource;
use App\Http\Resources\StatesResource;
use App\Http\Resources\CountriesResource;
use App\Http\Resources\WarehouseResource;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\ManufacturerResource;
use App\Models\SystemCity;
use App\Models\SystemState;
use App\Services\Warehouse\WarehouseService;
use App\Services\Product\ProductCategoryService;
use App\Services\Manufacturer\ManufacturerService;

class CommonController extends Controller
{
    use ApiReturnFormatTrait;
    protected $warehouseService;
    protected $productCategoryService;
    protected $brandService;
    protected $manufacturerService;
    public function __construct(WarehouseService $warehouseService, ProductCategoryService $productCategoryService, BrandService $brandService, ManufacturerService $manufacturerService)
    {
        $this->warehouseService = $warehouseService;
        $this->productCategoryService = $productCategoryService;
        $this->brandService = $brandService;
        $this->manufacturerService = $manufacturerService;
    }

    public function getSettings()
    {
        $generalSettings = SystemSettings::where('settings_key', 'general')->first();
        $settings = [
            'general_settings' => $generalSettings->settings_value,
        ];
        return $this->responseWithSuccess(__('Settings'), $settings);
    }
    public function getCountries()
    {
        $countries = CountriesResource::collection(DB::table('system_countries')->get());
        return $this->responseWithSuccess(__('Country List'), $countries);
    }
    public function getStateByCountry(Request $request)
    {
        if ($request->country_id == '' || $request->country_id == null) {
            return $this->responseWithError(__('Country not found'), [], 404);
        }
        // dd(DB::table('system_states')->where('country_id', $request->country_id)->get());
        $states = StatesResource::collection(SystemState::where('country_id', $request->country_id)->orderBy('name')->get());
        return $this->responseWithSuccess(__('State List'), $states);
    }
    public function getCitiesByState(Request $request)
    {
        if ($request->state_id == '' || $request->state_id == null) {
            return $this->responseWithError(__('State not found'), [], 404);
        }
        $cities = CitiesResource::collection(SystemCity::where('state_id', $request->state_id)->orderBy('name')->get());
        return $this->responseWithSuccess(__('City List'), $cities);
    }
    public function warehouses()
    {
        $warehouses = WarehouseResource::collection($this->warehouseService->get(scope: ['active']));
        return $this->responseWithSuccess(__('Warehouse List'), $warehouses);
    }
    public function categories()
    {
        $categories = CategoriesResource::collection($this->productCategoryService->getActiveData(null, 'subCategory')->where('parent_id', null));
        return $this->responseWithSuccess(__('Category List'), $categories);
    }
    public function brands()
    {
        try {
            $brands = BrandResource::collection($this->brandService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Brand List'), $brands);

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function manufacturers()
    {

        try {

            $manufacturers = ManufacturerResource::collection($this->manufacturerService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Manufacturer List'), $manufacturers);
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }

    }
    public function storeGeneralInfo(Request $request)
    {
        try {

            $data = $request->only([
                'general.site_title',
                'general.timezone',
                'general.site_logo',
                'general.favicon',
                'general.primary_color',
                'general.secondary_color',
                'general.currency_symbol',
                'general.currency_exchange_rate',
                'general.currency_exchange_from',
                'general.default_tax',
                'general.currency_convert_form_api',
                'general.is_logo_show_in_invoice',
                'general.store_name',
                'general.brand_slogan',
                'general.store_address',
                'general.store_mobile',
                'general.store_website',
                'general.invoice_footer',
                'general.tin',
                'general.terms_and_conditions',

            ]);
           // dd($data);
            $systemSettingsController = resolve('App\Http\Controllers\Admin\Settings\SystemSettingsController');
            // Set site logo
            $data = $systemSettingsController->uploadImage($data, 'site_logo');
            // Set favicon
            $data = $systemSettingsController->uploadImage($data, 'favicon');
            $key = key($data);
            $settings = SystemSettings::where('settings_key', $key)->first();
             $temp_array = array_merge($settings->settings_value ?? [],$data[$key]);
             $data[$key] = $temp_array;

            if (!$settings) {
                $settings = new SystemSettings();
            }

            $settings->settings_key = $key;
            $settings->settings_value = isset($data[$key]) ? $data[$key] : '';
            $settings->save();
            if (array_key_exists('timezone', $data['general'])) {
                envWrite('APP_TIMEZONE', $data['general']['timezone']);
            }
            if ($key == "general") {
                // then check timezone value is available or not
                $timezone = $data['general']['timezone'] ? $data['general']['timezone'] : config('app.timezone');

                config(['app.timezone' => $timezone]);
                date_default_timezone_set($timezone);
            }
            return $this->responseWithSuccess('Updated Successfully');

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
    public function storeLoginSetting(Request $request)
    {

        try {

            $data = $request->only([
                'general.login_background',
                'general.login_message_system',
                'general.login_slider_image_1',
                'general.login_slider_image_2',
                'general.login_slider_image_3',
                'general.login_slider_image_m_1',
                'general.login_slider_image_m_2',
                'general.login_slider_image_m_3',
            ]);
            $systemSettingsController = resolve('App\Http\Controllers\Admin\Settings\SystemSettingsController');
            // Set login background
            $data = $systemSettingsController->uploadImage($data, 'login_background');
            // Set login slider image
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_1');
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_2');
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_3');
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_m_1');
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_m_2');
            $data = $systemSettingsController->uploadImage($data, 'login_slider_image_m_3');
            $key = key($data);
            $settings = SystemSettings::where('settings_key', $key)->first();
            $temp_array = array_merge($settings->settings_value ?? [],$data[$key]);
            $data[$key] = $temp_array;
            if (!$settings) {
                $settings = new SystemSettings();
            }

            $settings->settings_key = $key;
            $settings->settings_value = isset($data[$key]) ? $data[$key] : '';
            $settings->save();

            return $this->responseWithSuccess('Updated Successfully');

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function storePaymentMethod(Request $request)
    {
        try {

            $data = [
                'paypal' => [
                    'paypal.baseUrl' => $request->paypal['paypal.baseUrl'],
                    'paypal.clientId' => $request->paypal['paypal.clientId'],
                    'paypal.secret' => $request->paypal['paypal.secret'],
                ],
                'stripe' => [
                    'stripe.public_key' => $request->stripe['stripe.public_key'],
                    'stripe.secret_key' => $request->stripe['stripe.secret_key'],
                ],
            ];

            $systemSettingsController = resolve('App\Http\Controllers\Admin\Settings\SystemSettingsController');
            // Set login background

            $keys = array_keys($data);
            foreach ($keys as $key) {
                $settings = SystemSettings::where('settings_key', $key)->first();
                if (!$settings) {
                    $settings = new SystemSettings();
                }

                $settings->settings_key = $key;
                $settings->settings_value = isset($data[$key]) ? $data[$key] : '';
                $settings->save();
            }

            return $this->responseWithSuccess('Updated Successfully');

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function storeSMTP(Request $request)
    {
        try {

            $data = [
                'mail' => [
                    'mail.mailers.smtp.host' => $request->mail['mail.mailers.smtp.host'],
                    'mail.mailers.smtp.port' => $request->mail['mail.mailers.smtp.port'],
                    'mail.mailers.smtp.encryption' => $request->mail['mail.mailers.smtp.encryption'],
                    'mail.mailers.smtp.username' => $request->mail['mail.mailers.smtp.username'],
                    'mail.mailers.smtp.password' => $request->mail['mail.mailers.smtp.password'],
                    'mail.from.address' => $request->mail['mail.from.address'],
                    'mail.from.name' => $request->mail['mail.from.name'],

                ],

            ];

            $systemSettingsController = resolve('App\Http\Controllers\Admin\Settings\SystemSettingsController');
            // Set login background

            $key = key($data);

            $settings = SystemSettings::where('settings_key', $key)->first();
            if (!$settings) {
                $settings = new SystemSettings();
            }

            $settings->settings_key = $key;
            $settings->settings_value = isset($data[$key]) ? $data[$key] : '';
            $settings->save();

            return $this->responseWithSuccess('Updated Successfully');

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function storeProductSetting(Request $request)
    {
        try {

            $data = [
                'product_setting' => [
                    'sku.auto' => $request->product_setting['sku.auto'],
                    'sku.editable' => $request->product_setting['sku.editable'],
                    'sku.prefix' => $request->product_setting['sku.prefix'],
                    'sku.suffix' => $request->product_setting['sku.suffix'],
                ],
                'stock_alert_mail_getter' => $request->stock_alert_mail_getter,

            ];

            $systemSettingsController = resolve('App\Http\Controllers\Admin\Settings\SystemSettingsController');
            // Set login background

            $keys = array_keys($data);
            foreach ($keys as $key) {
                $settings = SystemSettings::where('settings_key', $key)->first();
                if (!$settings) {
                    $settings = new SystemSettings();
                }

                $settings->settings_key = $key;
                $settings->settings_value = isset($data[$key]) ? $data[$key] : '';
                $settings->save();
            }

            return $this->responseWithSuccess('Updated Successfully');

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

}
