<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Admin\Coupon\CouponController;
use App\Http\Controllers\Admin\Product\ProductsController;
use App\Http\Controllers\Customer\Profile\ProfileController;
use App\Http\Controllers\Customer\Invoice\invoicesController;
use App\Http\Controllers\Customer\Employee\EmployeeController;
use App\Http\Controllers\Customer\Invoice\DraftInvoiceController;

// CUSTOMER AUTHORIZATION
Route::middleware('isInstalled')->group(function () {
    Route::get('/customer/login', 'Customer\Auth\LoginController@login')->name('customer.auth.login');
    Route::post('/customer/login', 'Customer\Auth\LoginController@loginSubmit')->name('customer.auth.loginSubmit');
    Route::post('/customer/logout', 'Customer\Auth\LoginController@logout')->name('customer.auth.logout');

    Route::get('/customer/registration', 'Customer\Auth\LoginController@registration')->name('customer.auth.registration');
    Route::post('/customer/registration/post', 'Customer\Auth\LoginController@storeCustomer')->name('customer.auth.store.customer');


    Route::get('/customer/reset-password', 'Customer\Auth\ResetPasswordController@resetPassword')->name('customer.auth.reset-password');
    Route::post('/customer/reset-password', 'Customer\Auth\ResetPasswordController@processPassword')->name('customer.auth.resetPasswordSave');
});

Route::namespace('Customer')->prefix('customer')->as('customer.')->middleware(['customer', 'isInstalled'])->group(function () {

    Route::get('set-lang', 'DashboardController@setLang')->name('set-lang');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::put('profile/{profile}', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::resource('invoices', 'Invoice\InvoicesController');
    Route::resource('employee', EmployeeController::class);
    Route::get('employee/verify/{id}', [EmployeeController::class, 'verifyUnverify'])->name('employee.verify');
    Route::get('invoices/download/{id}', [InvoicesController::class, 'download'])->name('invoices.download');
    Route::get('invoices/payments/{invoice_id}', [InvoicesController::class, 'getPayments'])->name('invoices.get_payments');
    // SALE RETURN
    Route::resource('products-return-request', 'ReturnRequest\ProductReturnController')->except(['create']);
    Route::get('products-return-request/{sale_id}/create', 'ReturnRequest\ProductReturnController@create')
        ->name('products-return-request.create');
    Route::get('products-return-request-create', 'ReturnRequest\ProductReturnController@createList')
        ->name('products-return-request.createable_list');
    Route::get('invoices-print/{id}', [InvoicesController::class, 'print'])->name('invoice.print');

    //Notifications
    Route::get('customer/markasread-all', [NotificationController::class, 'customerMarkAsReadAll'])->name('markasreadall');
    Route::get('customer/all-wallet-amount', [NotificationController::class, 'allWalletAmount'])->name('allwalletamount');
    Route::post('customer/notifications/read', [NotificationController::class, 'customerMarkAsRead'])->name('notifications.read');
    //DRAFT INVOICE
    Route::resource('draft-invoices', 'Invoice\DraftInvoiceController');
    Route::get('draft-to-invoice/{id}', [DraftInvoiceController::class, 'draftToInvoice'])->name('draft-invoices.draft-to-invoice');
    Route::post('invoices/store/from-draft/{id}', [DraftInvoiceController::class, 'storeDraftToInvoice'])->name('draft-invoices.store.from-draft');


    Route::get('reports/purchase', 'Report\ReportsController@purchases')->name('reports.purchase');
    Route::get('reports/export/purchase', 'Report\ReportsController@exportPurchases')->name('reports.export.purchase');
    Route::get('reports/payments', 'Report\ReportsController@payments')->name('reports.payments');
    Route::get('reports/export/payments', 'Report\ReportsController@exportPayments')->name('reports.export.payments');

    // HANDLE API REQUEST
    Route::prefix('app/api')->as('app-api.')->group(function () {
        // TODO: Because this api only handle forntend API and ajax
        // TODO: request so added "app" at prefix. its done for future.
        // TODO: If user try to build full feature API they can use "/api" endpint
        // Thanks me later


        // Search product by sku
        Route::get('/products/skus/search/{query}', [ProductsController::class, 'productSkuSearch']);
        // Search product by name and sku
        Route::get('/products/name-sku/search/{query}', [ProductsController::class, 'productSearchByNameSku']);
        Route::get('/product-stocks/name-sku/search/{query}/{warehouse_id}', [ProductsController::class, 'productStockSearchByNameSku']);
        Route::get('/products/warehouse/search/{query}', [ProductsController::class, 'productSearchByWarehouse']);
        Route::get('/product-stocks/warehouse/search/{id}', [ProductsController::class, 'productStockSearchByWarehouse']);

        // Get product by category
        Route::get('/products/category/{id}', [ProductsController::class, 'getByCategory']);
        Route::get('/product-stocks/category/{id}/{warehouse_id}', [ProductsController::class, 'getProductStockByCategory']);
        // Get product by barcode
        Route::get('/products/barcode/{barcode}', [ProductsController::class, 'getByBarcode']);
        Route::get('/product-stocks/barcode/{barcode}', [ProductsController::class, 'getProductStockByBarcode']);

        Route::get('/active-coupon/{code}', [CouponController::class, 'getActiveCouponByCode']);


        // Dashboard product filter by (month, year, week)
        Route::get('top-product', 'DashboardController@getTopProduct')
            ->name('get-top-product');
    });
});
