<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SystemSettings;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use GeoSot\EnvEditor\Facades\EnvEditor;

/**
 * defaultTax
 *
 * @return void
 */
function getDefaultTax()
{
    return config('default_tax') ?? 0;
}

/**
 * calculateDue
 *
 * @param  mixed $total
 * @param  mixed $total_paid
 * @return void
 */
function calculateDue($total, $total_paid)
{
    if ($total > $total_paid) {
        return $total - $total_paid;
    } else {
        return 0;
    }
}

/**
 * currencySymbol
 *
 * @return void
 */
function currencySymbol()
{
    return (config('currency_symbol') ?? '$') . ' ';
}

/**
 * checkPermission
 *
 * @param  mixed $permissions
 * @return void
 */
function checkPermission($permissions)
{
    if (!auth()->user()->can($permissions)) {
        abort(403);
    }
}

/**
 * generateBarcode
 *
 * @return void
 */
function generateBarcode($prefix = '', $model = null, $field = 'barcode', $length = 8): string
{
    do {
        // Generate random alphanumeric string
        $randomString = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length));
        $barcode = $prefix . $randomString;
        
        // If no model specified, return the generated barcode
        if (!$model) {
            return $barcode;
        }
        
        // Check uniqueness against the specified model
        $exists = $model::where($field, $barcode)->exists();
        
    } while ($exists);

    return $barcode;
}

function generateDepotStockBarcode($depotId = null): string
{
    $prefix = 'DPT';
    if ($depotId) {
        $prefix .= str_pad($depotId, 2, '0', STR_PAD_LEFT);
    }
    
    return generateBarcode($prefix, \App\Models\DepotStock::class, 'barcode', 6);
}

function generateInvoiceBarcode($invoiceNumber): string
{
    // Use the invoice number as the barcode
    // You can modify this logic as needed
    return strtoupper(str_replace(['-', '_', ' '], '', $invoiceNumber));
}

function generateProductBarcode(): string
{
    // Generate 12 digits
    $code = '';
    for ($i = 0; $i < 12; $i++) {
        $code .= mt_rand(0, 9);
    }
    
    // Calculate check digit
    $checkDigit = calculateEAN13CheckDigit($code);
    
    return $code . $checkDigit;
}

function calculateEAN13CheckDigit($code): int
{
    $sum = 0;
    
    for ($i = 0; $i < 12; $i++) {
        $digit = (int) $code[$i];
        $sum += ($i % 2 === 0) ? $digit : $digit * 3;
    }
    
    $checkDigit = (10 - ($sum % 10)) % 10;
    
    return $checkDigit;
}

function formatBarcode($barcode, $format = 'display'): string
{
    switch ($format) {
        case 'display':
            // Add spaces for better readability
            return chunk_split($barcode, 4, ' ');
            
        case 'print':
            // Remove any non-alphanumeric characters for printing
            return preg_replace('/[^A-Za-z0-9]/', '', $barcode);
            
        case 'web':
            // URL-safe format
            return urlencode($barcode);
            
        default:
            return $barcode;
    }
}

function validateBarcode($barcode, $type = 'general'): bool
{
    switch ($type) {
        case 'depot':
            // Depot barcodes should start with DPT and be 9-12 characters
            return preg_match('/^DPT[A-Z0-9]{6,9}$/', $barcode);
            
        case 'product':
            // Product barcodes should be 13 digits (EAN-13)
            return preg_match('/^\d{13}$/', $barcode);
            
        case 'invoice':
            // Invoice barcodes are alphanumeric, 8-20 characters
            return preg_match('/^[A-Z0-9]{8,20}$/', $barcode);
            
        default:
            // General validation: alphanumeric, 4-30 characters
            return preg_match('/^[A-Za-z0-9]{4,30}$/', $barcode);
    }
}

function getBarcodeImage($barcode, $type = 'C128', $width = 2, $height = 50): string
{
    try {
        $generator = new \Milon\Barcode\DNS1D();
        return $generator->getBarcodePNG($barcode, $type, $width, $height);
    } catch (\Exception $e) {
        \Log::error('Barcode generation failed: ' . $e->getMessage());
        return '';
    }
}

/**
 * invoiceStatusBadge
 *
 * @param  mixed $status
 * @return void
 */
function invoiceStatusBadge($status)
{
    switch ($status) {
        case Invoice::STATUS_PAID:
            return '<span class="badge badge-success">' . Invoice::INVOICE_ALL_STATUS[$status] . '</span>';
            break;
        case Invoice::STATUS_PARTIALLY_PAID:
            return '<span class="badge badge-info">' . Invoice::INVOICE_ALL_STATUS[$status] . '</span>';
            break;
        case Invoice::STATUS_OVERDUE:
            return '<span class="badge badge-warning">' . Invoice::INVOICE_ALL_STATUS[$status] . '</span>';
            break;
        case Invoice::STATUS_CANCELED:
            return '<span class="badge badge-danger">' . Invoice::INVOICE_ALL_STATUS[$status] . '</span>';
            break;

        default:
            return '<span class="badge badge-warning">' . Invoice::INVOICE_ALL_STATUS[$status] . '</span>';
            break;
    }
}
function invoiceDeliveryStatusBadge($status)
{
    switch ($status) {
        case Invoice::DELIVERY_STATUS_DELIVERED:
            return '<span class="badge badge-success">' . ucfirst(Invoice::DELIVERY_STATUS_DELIVERED) . '</span>';
            break;
        case Invoice::DELIVERY_STATUS_PROCESSING:
            return '<span class="badge badge-info">' . ucfirst(Invoice::DELIVERY_STATUS_PROCESSING) . '</span>';
            break;
        case Invoice::DELIVERY_STATUS_PENDING:
            return '<span class="badge badge-warning">' . ucfirst(Invoice::DELIVERY_STATUS_PENDING) . '</span>';
            break;
        case Invoice::DELIVERY_STATUS_CANCELED:
            return '<span class="badge badge-danger">' . ucfirst(Invoice::DELIVERY_STATUS_CANCELED) . '</span>';
            break;

        default:
            return '<span class="badge badge-success">' . ucfirst(Invoice::DELIVERY_STATUS_DELIVERED) . '</span>';
            break;
    }
}

function returnRequestStatusBadge($status)
{
    switch ($status) {
        case \App\Models\SaleReturnRequest::STATUS_ACCEPTED:
            return '<span class="badge badge-success">' . ucfirst($status) . '</span>';
            break;
        case \App\Models\SaleReturnRequest::STATUS_PENDING:
            return '<span class="badge badge-warning">' . ucfirst($status) . '</span>';
            break;
        case \App\Models\SaleReturnRequest::STATUS_REJECTED:
            return '<span class="badge badge-danger">' . ucfirst($status) . '</span>';
            break;
        default:
            return '<span class="badge badge-warning">' . ucfirst($status) . '</span>';
            break;
    }
}

// Make minimum 8 digits
/**
 * make8digits
 *
 * @param  mixed $num
 * @return void
 */
function make8digits($num)
{
    return sprintf("%08d", $num);
}

// Make minimum 2 digits
/**
 * make2digits
 *
 * @param  mixed $num
 * @return void
 */
function make2digits($num)
{
    return sprintf("%02d", $num);
}

/**
 * make2decimal
 *
 * @param  mixed $number
 * @return void
 */
function make2dec($number)
{
    return number_format((float)$number, 2, '.', '');
}
function make2decimal($number)
{
    return formatNumber($number);
    //return number_format((float)$number, 2, '.', '');
}

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        $locale = config('decimal_separator') ?? 'en-US';
        $decimals = config('no_of_decimals') ?? 2;
        $localeSettings = [
            'en-US' => [',', '.'],
            'en-IN' => [',', '.'],
            'es-ES' => ['.', ','],
            'fr-FR' => [' ', ','],
            'it-CH' => ['’', '.'],
            'bn-BD' => [',', '.'],
            'ar-SA' => ['٬', '٫'],
        ];
        [$thousandsSeparator, $decimalSeparator] = $localeSettings[$locale] ?? [',', '.'];
        return number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}

/**
 * commaSeparateObjectItem
 *
 * @param  mixed $object
 * @param  mixed $field
 * @return void
 */
function commaSeparateObjectItem($object, $field)
{
    $total = count($object);
    $data_string = '';

    for ($i = 0; $i < $total; $i++) {
        $string = $data_string . $object[$i]->$field;
        if ($i < $total - 1) $string .= ', ';

        $data_string = $string;
    }
    return $data_string;
}

/**
 * convertDbSettingsToConfig
 *
 * @param  mixed $data
 * @return void
 */
function convertDbSettingsToConfig($settings)
{
    $settings_array = [];
    foreach ($settings as $s) {
        if (is_array($s->settings_value)) {
            foreach ($s->settings_value as $key => $value) {
                $settings_array[$key] = $value;
            }
        } else {
            $settings_array[$s->settings_key] = $s->settings_value;
        }
    }
    return $settings_array;
}

/**
 * generateSlug
 *
 * @param  mixed $value
 * @return void
 */
function generateSlug($value)
{
    try {
        return preg_replace('/\s+/u', '-', trim($value));
    } catch (\Exception $e) {
        return '';
    }
}

/**
 * rootDir
 *
 * @return void
 */
function rootDir()
{
    $paths = explode(DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']);
    array_pop($paths);
    return implode(DIRECTORY_SEPARATOR, $paths);
}

/**
 * getDefaultImage
 *
 * @return void
 */
function getDefaultImage()
{
    return static_asset('images/default.png');
}

/**
 * getUserDefaultImage
 *
 * @return void
 */
function getUserDefaultImage()
{
    return static_asset('images/user_default.png');
}

/**
 * getStorageImage
 *
 * @param  mixed $path
 * @param  mixed $name
 * @param  mixed $is_user
 * @return void
 */
function getStorageImage($path, $name, $is_user = false)
{
    if ($name && Storage::exists($path . '/' . $name)) {
        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) :
            if (config('filesystems.default') == 'public_path'):
                return app('url')->asset('files/' . $path . '/' . $name);
            else:
                return app('url')->asset('storage/' . $path . '/' . $name);
            endif;
        else:
            if (config('filesystems.default') == 'public_path'):
                return app('url')->asset('public/files/' . $path . '/' . $name);
            else:
                return app('url')->asset('public/storage/' . $path . '/' . $name);
            endif;
        //            return app('url')->asset('public/storage/' . $path . '/' . $name);
        endif;
    }
    return $is_user ? getUserDefaultImage() : getDefaultImage();
}

/**
 * getStorageFile
 *
 * @param  mixed $path
 * @param  mixed $name
 * @return void
 */
function getStorageFile($path, $name)
{
    if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) :
        return app('url')->asset('storage/' . $path . '/' . $name);
    else:
        return app('url')->asset('public/storage/' . $path . '/' . $name);
    endif;
    //    return Storage::url($path . '/' . $name);
}

/**
 * get_page_meta
 *
 * @param  mixed $metaName
 * @return void
 */
function get_page_meta($metaName = "title")
{
    if (session()->has('page_meta_' . $metaName)) {
        $title = session()->get("page_meta_" . $metaName);
        session()->forget("page_meta_" . $metaName);
        return $title;
    }
    return null;
}

/**
 * set_page_meta
 *
 * @param  mixed $content
 * @param  mixed $metaName
 * @return void
 */
function set_page_meta($content = null, $metaName = "title")
{
    if ($content && $metaName == "title") {
        session()->put('page_meta_' . $metaName, $content . ' |');
    } else {
        session()->put('page_meta_' . $metaName, $content);
    }
}

/**
 * custom_datetime
 *
 * @param  mixed $datetime
 * @param  mixed $format
 * @return void
 */
function custom_datetime($datetime, $format = null)
{
    if ($format) return date($format, strtotime($datetime));

    return date('Y-m-d g:i A', strtotime($datetime));
}

/**
 * custom_date
 *
 * @param  mixed $datetime
 * @param  mixed $format
 * @return void
 */
function custom_date($datetime, $format = null)
{
    if ($format) return date($format, strtotime($datetime));

    return date('Y-m-d', strtotime($datetime));
}

/**
 * getAppLogo
 *
 * @return void
 */
function getAppLogo()
{
    return static_asset('images/logo.png');
}


if (!function_exists('__t')) {

    /**
     * __t
     *
     * @param  mixed $key
     * @param  mixed $options
     * @param  mixed $isCapitalized
     * @return void
     */
    function __t($key = '', $options = [], $isCapitalized = false)
    {

        $vars = count($options) ? array_merge(...array_map(function ($k) use ($options) {
            $value = __("custom.$options[$k]");
            return [
                "{" . $k . "}" =>  $value,
                "{ $k }" =>  $value,
                "{ $k}" => $value,
                "{" . $k . " }" =>  $value,
                ":$k" => $value
            ];
        }, array_keys($options))) : [];

        $string = strtr(__("custom.{$key}"), $vars);
        return $isCapitalized ? ucwords($string) : $string;
    }
}

if (!function_exists('site_logo')) {

    function site_logo()
    {
        $array = explode('/', config('site_logo'));
        if (config('site_logo') && config('site_logo') != null && Storage::exists('settings/' . $array[count($array) - 1])) {
            return config('site_logo');
        } else {
            return static_asset('admin/images/logo.png');
        }
    }
}
if (!function_exists('static_asset')) {

    function static_asset($path = null, $secure = null)
    {
        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) :
            return app('url')->asset($path, $secure);
        else:
            $all_null = ($path == null && $secure == null) ? '/' : '';
            return app('url')->asset('public/' . $path, $secure) . $all_null;
        endif;
    }
}
if (!function_exists('favicon')) {

    function favicon()
    {
        $array = explode('/', config('favicon'));

        if (config('favicon') && config('favicon') != null && Storage::exists('settings/' . $array[count($array) - 1])) {

            return config('favicon');
        } else {

            return static_asset('admin/images/favicon.png');
        }
    }
}
if (!function_exists('envWrite')) {
    function envWrite($key, $value)
    {
        $env_value = (is_numeric($value) || is_bool($value)) ? $value : '"' . $value . '"';

        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, $env_value);
        } else {
            EnvEditor::addKey($key, $env_value);
        }
    }
}

if (!function_exists('verify_purchase_code')):
    function verify_purchase_code($code)
    {
        $verified = false;
        define('VERSION', config('app.version'));

        $script_url = str_replace("install/process", "", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

        $fields = array(
            'purchase_code'     => urlencode($code),
            'domain'            => urlencode($_SERVER['SERVER_NAME']),
            'remote_addr'       => urlencode($_SERVER['REMOTE_ADDR']),
            'url'               => urlencode($script_url),
            'app_version'       => urlencode(VERSION),
            'user_agent'        => request()->header('User-Agent'),
            'email'             => Session::get('email'),
            'ip'                => getIp(),
            'source'            => 'codecanyon',
            'product_id'        => 34897071,
        );
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        $url = "https://itclanbd.com/api/v100/check-installation";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $curl_response = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);

        $curl_response = json_decode($curl_response);
        if ($curl_info["http_code"] == "200"):

            if ($curl_response->status == true && $curl_response->message == "Purchased Product Verified Successfully"):
                envWrite('APP_VERSION', urlencode(VERSION));
                return true;
            else:
                return $curl_response->message;
            endif;
        else:
            if (isset($curl_response->message)) {
                return $curl_response->message;
            } else {
                return __('There is a problem to connect with server.Make sure you have active internet connection!');
            }
        endif;

        return false;
    }
endif;
if (!function_exists('getIp')) {
    function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
}

if (!function_exists('isInstalled')) {

    function isInstalled(): bool
    {
        if (
            config('app.app_installed') == true && config('app.app_installed') != null &&
            config('app.app_purchase_code') != null &&
            preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", config('app.app_purchase_code'))
        ) {
            if (Storage::exists('installed.txt') && Storage::get('installed.txt') == config('app.app_purchase_code')) {
                return true;
            } else {
                try {
                    $verify = verify_purchase_code(config('app.app_purchase_code'));

                    if ($verify === true) {
                        Storage::put('installed.txt', config('app.app_purchase_code'));
                        storePurchaseCode(config('app.app_purchase_code'));
                        return true;
                    } else {
                        return false;
                    }
                } catch (\Exception $e) {
                    return false;
                }
            }
        }
        return false;
    }
}

if (!function_exists('storePurchaseCode')) {
    function storePurchaseCode($code)
    {
        $purchase_info = SystemSettings::where('settings_key', 'purchase_info')->first();

        if ($purchase_info) {
            $purchase_info->update([
                'settings_value' =>  [
                    'purchase_code'     => session()->get('purchase_code'),
                    'install_at'        => now()->toDateTimeString(),
                    'domain'            => url('/'),
                    'product_version'   => config('app.version'),
                ],
            ]);
        } else {
            SystemSettings::create([
                'settings_key'      => 'purchase_info',
                'settings_value'    => [
                    'purchase_code'     => session()->get('purchase_code'),
                    'install_at'        => now()->toDateTimeString(),
                    'domain'            => url('/'),
                ],
            ]);
        }
    }
}

if (!function_exists('check_db_and_table_exist')) {

    function check_db_and_table_exist()
    {
        try {
            if (DB::connection()->getPdo() && DB::connection()->getDatabaseName() && Schema::hasTable('products')) {

                return true;
            }
        } catch (Exception $exception) {
            if (file_exists(storage_path('installed'))) {
                unlink(storage_path('installed'));
            }
            return false;
        }
    }
}

if (!function_exists('all_timezones')) {
    function all_timezones()
    {
        if (Cache::has('all_timezones')) {
            $timezones = Cache::get('all_timezones');
        } else {
            Cache::put('all_timezones', config('clanvent_config.timezone'), \Carbon\Carbon::now()->addMonth(1));
            $timezones = Cache::get('all_timezones');
        }
        return $timezones;
    }
}

if (!function_exists('convertAmountWithCurrencyConverter')) {
    function convertAmountWithCurrencyConverter($amount): float
    {

        $exhangeRate        = config('currency_exchange_rate') ?? 1;
        $convertedAmount    = $amount * $exhangeRate;

        return $convertedAmount;
    }
}
if (!function_exists('user_type')) {
    function user_type()
    {
        if (auth()->guard('web')->check()) {
            return 'admin';
        } elseif (auth()->guard('customer')->check()) {
            $customer = Customer::where('id', auth()->guard('customer')->user()->id)->first();
            if ($customer->type == 'customer') {
                return Customer::TYPE_CUSTOMER;
            } else {
                return Customer::TYPER_EMPLOYEE;
            }
        }
        return null;
    }
}
if (!function_exists('user_id')) {
    function user_id()
    {
        if (auth()->guard('customer')->check()) {
            if (auth()->guard('customer')->user()->type == 'customer') {
                return auth()->guard('customer')->user()->id;
            } else {
                return auth()->guard('customer')->user()->customer_id;
            }
        }
        return null;
    }
}
if (!function_exists('customer_id')) {
    function customer_id($id)
    {
        $user = Customer::where('id', $id)->first();
        if ($user->type == 'customer') {
            return $user->id;
        } else {
            return $user->customer_id;
        }
        return null;
    }
}
if (!function_exists('api_user_id')) {
    function api_user_id()
    {
        if (auth()->guard('api_customer')->check()) {
            if (auth()->guard('api_customer')->user()->type == 'customer') {
                return auth()->guard('api_customer')->user()->id;
            } else {
                return auth()->guard('api_customer')->user()->customer_id;
            }
        }
        return null;
    }
}
if (!function_exists('get_parent_user')) {
    function get_parent_user()
    {
        if (auth()->guard('customer')->check()) {
            if (auth()->guard('customer')->user()->type == 'customer') {
                return auth()->guard('customer')->user();
            } else {
                $customer = Customer::where('id', auth()->guard('customer')->user()->customer_id)->first();
                return $customer;
            }
        }
        return null;
    }
}
