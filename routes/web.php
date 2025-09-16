<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Notifications\GlobalNotification;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('send-event', function(){
    $data = [
        'title' => 'New Invoice Created',
         'message' => sprintf(
            '%s %s has created a new invoice. Please view the invoice for more information.',
            'test',
            'user'
        ),
        'url' => route('customer.invoices.index'),
    ];
    $user = Customer::find(1);

    // foreach ($users as $user) {

            $user->notify(new GlobalNotification($data));

    // }
    return response()->json(['status' => 'Message sent successfully']);
});
// Route::get('/test',function(){
//     dd(config('broadcasting'));
// });

Route::middleware('isInstalled')->group(function () {
    Route::get('/', function () {
        if (check_db_and_table_exist()) {
            return redirect('/admin/login');
        } else {
            return redirect()->route('install.initialize');
        }
    });


    Route::get('/change-layout', function () {
        $layout = session()->get('layout');

        if ($layout != '') {
            session()->put('layout', !$layout);
        } else {
            session()->put('layout', 'left');
        }

        return back();
    });

    Auth::routes([
        'register' => false, // Registration Routes...
        'reset' => false, // Password Reset Routes...
        'verify' => false, // Email Verification Routes...
    ]);

    Route::get('/login', function () {
        return redirect('/admin/login');
    })->name('login');

// ADMIN AUTHORIZATION
    Route::get('/admin/login', 'Admin\Auth\LoginController@login')->name('admin.auth.login');
    Route::post('/admin/login', 'Admin\Auth\LoginController@loginSubmit')->name('admin.auth.loginSubmit');
    Route::get('/admin/logout', 'Admin\Auth\LoginController@logout')->name('admin.auth.logout');

//Reset Password

    Route::get('/admin/reset-password', 'Admin\Auth\ResetPasswordController@resetPassword')->name('admin.auth.reset-password');
    Route::post('/admin/reset-password', 'Admin\Auth\ResetPasswordController@processPassword')->name('admin.auth.resetPasswordSave');


// Invoice live link
    Route::get('invoice/live/{token}', 'Admin\Invoice\InvoicesController@getByToken')->name('invoice.live_url');
    Route::post('invoices/pay/stripe', 'Admin\Invoice\InvoicesController@payStripe')->name('invoices.pay_stripe');
    Route::get('payment/stripe/success', 'Admin\Invoice\InvoicesController@stripeSuccess')->name('stripe.success');
    Route::get('payment/stripe/cancel', 'Admin\Invoice\InvoicesController@stripeCancel')->name('stripe.cancel');
    Route::get('payment/paypal/success', 'Admin\Invoice\InvoicesController@paypalSuccess')->name('paypal.success');
    Route::get('payment/success', 'Admin\Invoice\InvoicesController@paymentSuccess')->name('payment.success');

// LOCATION
    Route::prefix('locations')->as('locations.')->group(function () {


        // ..............HANDLE AJAX REQUEST
        Route::get('api/country-wise-state', 'Location\StatesController@getCountryWiseState');
        Route::get('api/state-wise-city', 'Location\CitiesController@getStateWiseCity');
    });

    Route::get('artisan/cache/clear', function () {
        \Artisan::call('optimize:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');

        // SESSION_DRIVER=file
        $directory = config('session.files');
        $ignoreFiles = ['.gitignore', '.', '..'];

        $files = scandir($directory);

        foreach ( $files as $file ) {
            if( !in_array($file,$ignoreFiles) ) {
                unlink($directory . '/' . $file);
            }
        }
        \Session::flush();;

        echo true;
    });
    Route::get('artisan/migrate', function () {
        \Artisan::call('migrate');
        echo true;
    });
    Route::get('artisan/storage-link', function () {
        \Artisan::call('storage:link');
        echo true;
    });

    Route::get('/send-stock-alert', function () {

        try {

            Artisan::call('send:stock-alert');

            Log::info('Stock Alert Send Successful');

            return true;

        } catch (\Exception $exception) {

            Log::error($exception->getMessage());

            return false;
        }

    });


    Route::get('/reset-db', function () {
        try {
//            Artisan::call("backup:run --only-db --disable-notifications");

//            Log::info("DB backup successful");
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        try {
            // Drop all table
            Schema::disableForeignKeyConstraints();
            foreach (DB::select('SHOW TABLES') as $table) {
                $table_array = get_object_vars($table);
                $table = $table_array[key($table_array)];
                $table = str_replace('ic_', '', $table);
                Schema::drop($table);
            }

            // Upload db
            $sql_path = base_path('icdemo.sql');
            DB::unprepared(file_get_contents($sql_path));

            Log::info("DB uploaded");
        } catch (\Exception $ex) {

        }

        return redirect('/');
    });
});
Route::get('app-logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
